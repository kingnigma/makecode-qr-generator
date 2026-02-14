<?php

/**
 * Server-side barcode generator
 * - Supports: Code 128 (Code Set B), Code 39, EAN-13 (and UPC-A via leading 0)
 * - Modes: counter (prefix + start + increment + count) or file (uploaded .txt/.csv)
 * - Writes generated PNGs to uploads/bar-codes/ and records entries in `qr_codes` (qr_type='barcode')
 * - Prevent-duplicate option via barcode_history.json
 */

require_once 'config.php';
header('Content-Type: application/json');

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    echo json_encode(['success' => false, 'error' => 'Invalid request method']);
    exit;
}

// Helper: return error
function jsonError($msg)
{
    echo json_encode(['success' => false, 'error' => $msg]);
    exit;
}

// get POST values (some may be sent via FormData)
$mode = $_POST['mode'] ?? 'single'; // single | counter | file
$projectName = trim($_POST['projectName'] ?? '');
$symbology = strtolower($_POST['symbology'] ?? 'code128');
$preventDup = isset($_POST['preventDuplicates']) && ($_POST['preventDuplicates'] === 'true' || $_POST['preventDuplicates'] === '1');

// customization - use form values or defaults
$width = intval($_POST['width'] ?? 440);
$height = intval($_POST['height'] ?? 306);
$fg = $_POST['fgColor'] ?? '#000000';
$bg = $_POST['bgColor'] ?? '#FFFFFF';
$fontSize = intval($_POST['fontSize'] ?? 12);

// validate symbology
$supported = ['code128', 'code39', 'ean13', 'upc-a', 'upc_a'];
if (!in_array($symbology, $supported)) {
    jsonError('Unsupported symbology');
}
// normalize upc variants
if ($symbology === 'upc-a') $symbology = 'upc_a';

// barcode history file (prevent duplicates)
$historyFile = __DIR__ . '/barcode_history.json';
if (!file_exists($historyFile)) {
    file_put_contents($historyFile, json_encode([]));
}
$history = json_decode(file_get_contents($historyFile), true) ?: [];

$toGenerate = []; // array of data strings to encode

if ($mode === 'counter') {
    $prefix = $_POST['prefix'] ?? '';
    $start = intval($_POST['start'] ?? 1);
    $increment = intval($_POST['increment'] ?? 1);
    $count = intval($_POST['count'] ?? 1);

    if ($count < 1) jsonError('Count must be at least 1');

    for ($i = 0; $i < $count; $i++) {
        $num = $start + ($i * $increment);
        $toGenerate[] = $prefix . $num;
    }
} elseif ($mode === 'file') {
    // accept uploaded file (dataFile) or dataList[] lines
    if (isset($_FILES['dataFile']) && is_uploaded_file($_FILES['dataFile']['tmp_name'])) {
        $tmp = $_FILES['dataFile']['tmp_name'];
        $content = file_get_contents($tmp);
        // split lines and trim
        $lines = preg_split('/\r?\n/', $content);
        foreach ($lines as $line) {
            $line = trim($line);
            if ($line !== '') $toGenerate[] = $line;
        }
    } elseif (!empty($_POST['dataList'])) {
        // client may send JSON-encoded array
        $raw = $_POST['dataList'];
        $arr = json_decode($raw, true);
        if (is_array($arr)) {
            foreach ($arr as $v) {
                $v = trim((string)$v);
                if ($v !== '') $toGenerate[] = $v;
            }
        }
    } else {
        jsonError('No file or dataList provided for file mode');
    }
} else {
    // single (preview or single barcode)
    $single = trim($_POST['data'] ?? '');
    if ($single === '') jsonError('No data provided for barcode');
    $toGenerate[] = $single;
}

if (empty($toGenerate)) jsonError('No barcode data to generate');

// Helpers for validation & generation
function isValidEAN13Input($s)
{
    return preg_match('/^[0-9]{12}$/', $s) || preg_match('/^[0-9]{13}$/', $s);
}

function calculateEAN13CheckDigit($digits12)
{
    $sum = 0;
    for ($i = 0; $i < 12; $i++) {
        $n = intval($digits12[$i]);
        $sum += ($i % 2 === 0) ? $n : 3 * $n;
    }
    $mod = $sum % 10;
    return ($mod === 0) ? 0 : (10 - $mod);
}

// ---------- Code 39 mapping ----------
$code39_map = [
    '0' => 'nnnwwnwnn',
    '1' => 'wnnwnnnnw',
    '2' => 'nnwwnnnnw',
    '3' => 'wnwwnnnnn',
    '4' => 'nnnwwnnnw',
    '5' => 'wnnwwnnnn',
    '6' => 'nnwwwnnnn',
    '7' => 'nnnwnnwnw',
    '8' => 'wnnwnnwnn',
    '9' => 'nnwwnnwnn',
    'A' => 'wnnnnwnnw',
    'B' => 'nwnnnwnnw',
    'C' => 'wwnnnwnnn',
    'D' => 'nwnnwnnnw',
    'E' => 'wwnnwnnnn',
    'F' => 'nwnnwnnwn',
    'G' => 'nwnnnnwwn',
    'H' => 'wwnnnnwnn',
    'I' => 'nwnnwnnwn',
    'J' => 'nwnnwnnwn',
    'K' => 'wnnnnwnwn',
    'L' => 'nwnnnwnwn',
    'M' => 'wwnnnwnnn',
    'N' => 'nwnnwnnnw',
    'O' => 'wwnnwnnnn',
    'P' => 'nwnnwnnwn',
    'Q' => 'nwnnnnwwn',
    'R' => 'wwnnnnwnn',
    'S' => 'nwnnwnnwn',
    'T' => 'nwnnwnnwn',
    'U' => 'wnnnnnnww',
    'V' => 'nwnnnnnww',
    'W' => 'wwnnnnnwn',
    'X' => 'nwnnwnnww',
    'Y' => 'wwnnwnnwn',
    'Z' => 'nwnnwnnww',
    '-' => 'nwnnnnwnw',
    '.' => 'wnnnnnwnn',
    ' ' => 'nwnnwnnwn',
    '$' => 'nwnwnwnnn',
    '/' => 'nwnwnnnwn',
    '+' => 'nwnnnwnwn',
    '%' => 'nnnwnwnwn',
    '*' => 'nwnnwnwnn' // '*' is start/stop
];
// Note: the mapping above is a compact representation; implementation below will be tolerant to allowed characters

// ---------- Code 128 pattern table (values 0..106) ----------
$code128_patterns = [
    "212222",
    "222122",
    "222221",
    "121223",
    "121322",
    "131222",
    "122213",
    "122312",
    "132212",
    "221213",
    "221312",
    "231212",
    "112232",
    "122132",
    "122231",
    "113222",
    "123122",
    "123221",
    "223211",
    "221132",
    "221231",
    "213212",
    "223112",
    "312131",
    "311222",
    "321122",
    "321221",
    "312212",
    "322112",
    "322211",
    "212123",
    "212321",
    "232121",
    "111323",
    "131123",
    "131321",
    "112313",
    "132113",
    "132311",
    "211313",
    "231113",
    "231311",
    "112133",
    "112331",
    "132131",
    "113123",
    "113321",
    "133121",
    "313121",
    "211331",
    "231131",
    "213113",
    "213311",
    "213131",
    "311123",
    "311321",
    "331121",
    "312113",
    "312311",
    "332111",
    "314111",
    "221411",
    "431111",
    "111224",
    "111422",
    "121124",
    "121421",
    "141122",
    "141221",
    "112214",
    "112412",
    "122114",
    "122411",
    "142112",
    "142211",
    "241211",
    "221114",
    "413111",
    "241112",
    "134111",
    "111242",
    "121142",
    "121241",
    "114212",
    "124112",
    "124211",
    "411212",
    "421112",
    "421211",
    "212141",
    "214121",
    "412121",
    "111143",
    "111341",
    "131141",
    "114113",
    "114311",
    "411113",
    "411311",
    "113141",
    "114131",
    "311141",
    "411131",
    "211412",
    "211214",
    "211232",
    "2331112" // stop (value 106)
];

// Utility: create PNG for a sequence of module widths (bars and spaces alternating)
function renderFromPatternSequence($patterns, $moduleWidth, $height, $fgColor, $bgColor, $text = '', $quietModules = 10)
{
    $totalModules = 0;
    foreach ($patterns as $pat) {
        // pattern is string of digits
        for ($i = 0; $i < strlen($pat); $i++) {
            $totalModules += intval($pat[$i]);
        }
    }

    $quietZone = ($quietModules / 2) * $moduleWidth; // left/right quiet zone
    $imgWidth = $quietZone * 2 + $totalModules * $moduleWidth;
    $textHeight = ($text !== '') ? 16 : 0;
    $imgHeight = $height + $textHeight + 8;

    $img = imagecreatetruecolor($imgWidth, $imgHeight);
    list($r, $g, $b) = sscanf($bgColor, "#%02x%02x%02x");
    $bg = imagecolorallocate($img, $r, $g, $b);
    list($r, $g, $b) = sscanf($fgColor, "#%02x%02x%02x");
    $fg = imagecolorallocate($img, $r, $g, $b);
    imagefilledrectangle($img, 0, 0, $imgWidth, $imgHeight, $bg);

    $x = $quietZone;
    $drawBar = true; // patterns alternate: bar,space,bar,space...
    foreach ($patterns as $pat) {
        for ($i = 0; $i < strlen($pat); $i++) {
            $w = intval($pat[$i]) * $moduleWidth;
            if ($drawBar) {
                imagefilledrectangle($img, $x, 0, $x + $w - 1, $height, $fg);
            }
            $x += $w;
            $drawBar = !$drawBar;
        }
    }

    // Add human readable text centered
    if ($text !== '') {
        $textColor = $fg;
        $font = 5; // built-in font
        $textBoxWidth = imagefontwidth($font) * strlen($text);
        $textX = intval(($imgWidth - $textBoxWidth) / 2);
        $textY = $height + 4;
        imagestring($img, $font, $textX, $textY, $text, $textColor);
    }

    return $img;
}

// Code 39 encoder -> pattern sequence
function encodeCode39($data)
{
    global $code39_map;
    $data = strtoupper($data);
    // validate allowed characters
    if (!preg_match('/^[0-9A-Z \-\.$\/\+%]+$/', $data)) {
        return false;
    }
    $chars = str_split($data);
    $patterns = [];
    // start char '*'
    $patterns[] = $code39_map['*'];
    foreach ($chars as $c) {
        $patterns[] = $code39_map[$c] ?? null;
        // inter-character narrow space (represented here as 'n')
        $patterns[] = 'n';
    }
    // stop char '*'
    $patterns[] = $code39_map['*'];
    return $patterns;
}

// Code 128 (Set B) encoder -> pattern sequence
function encodeCode128B($data)
{
    global $code128_patterns;
    // validate characters (32..127 allowed for Code128 B)
    $bytes = str_split($data);

    $values = [];
    foreach ($bytes as $ch) {
        $ord = ord($ch);
        if ($ord < 32 || $ord > 127) return false;
        $values[] = $ord - 32;
    }
    $sequence = [];
    // start code B = 104
    $sequence[] = 104;
    // data values
    foreach ($values as $v) $sequence[] = $v;
    // checksum
    $checksum = 104; // start value
    for ($i = 0; $i < count($values); $i++) {
        $checksum += ($i + 1) * $values[$i];
    }
    $checksum = $checksum % 103;
    $sequence[] = $checksum;
    // stop code 106
    $sequence[] = 106;



    // convert to patterns
    $patterns = [];
    foreach ($sequence as $val) {
        if (!isset($code128_patterns[$val])) return false;
        $patterns[] = $code128_patterns[$val];
    }
    return $patterns;
}

// EAN-13 encoder -> pattern sequence
function encodeEAN13($digits13)
{
    // digits13 should be 13-digit string
    $L = [
        '0' => ['0001101', '0011001', '0010011', '0111101', '0100011', '0110001', '0101111', '0111011', '0110111', '0001011'],
        '1' => ['0100111', '0110011', '0011011', '0100001', '0011101', '0111001', '0000101', '0010001', '0001001', '0010111'],
        '2' => ['1110010', '1100110', '1101100', '1000010', '1011100', '1001110', '1010000', '1000100', '1001000', '1110100']
    ];
    // Actually L/G/R tables are derived from standard; for left side encoding we need parity table
    $Lcode = [
        '0' => ['0001101', '0011001', '0010011', '0111101', '0100011', '0110001', '0101111', '0111011', '0110111', '0001011'],
        'G' => ['0100111', '0110011', '0011011', '0100001', '0011101', '0111001', '0000101', '0010001', '0001001', '0010111'],
        'R' => ['1110010', '1100110', '1101100', '1000010', '1011100', '1001110', '1010000', '1000100', '1001000', '1110100']
    ];
    // parity pattern for first digit
    $parity = [
        '0' => ['L', 'L', 'L', 'L', 'L', 'L'],
        '1' => ['L', 'L', 'G', 'L', 'G', 'G'],
        '2' => ['L', 'L', 'G', 'G', 'L', 'G'],
        '3' => ['L', 'L', 'G', 'G', 'G', 'L'],
        '4' => ['L', 'G', 'L', 'L', 'G', 'G'],
        '5' => ['L', 'G', 'G', 'L', 'L', 'G'],
        '6' => ['L', 'G', 'G', 'G', 'L', 'L'],
        '7' => ['L', 'G', 'L', 'G', 'L', 'G'],
        '8' => ['L', 'G', 'L', 'G', 'G', 'L'],
        '9' => ['L', 'G', 'G', 'L', 'G', 'L']
    ];

    if (!preg_match('/^[0-9]{13}$/', $digits13)) return false;

    $first = $digits13[0];
    $left6 = substr($digits13, 1, 6);
    $right6 = substr($digits13, 7, 6);

    $patterns = [];
    // start guard
    $patterns[] = '101';
    // left 6 digits
    $par = $parity[$first];
    for ($i = 0; $i < 6; $i++) {
        $d = intval($left6[$i]);
        $codeType = $par[$i];
        if ($codeType === 'L') $patterns[] = $Lcode['0'][$d];
        else $patterns[] = $Lcode['G'][$d];
    }
    // center guard
    $patterns[] = '01010';
    // right 6 digits (R-code)
    for ($i = 0; $i < 6; $i++) {
        $d = intval($right6[$i]);
        $patterns[] = $Lcode['R'][$d];
    }
    // end guard
    $patterns[] = '101';
    return $patterns;
}

// Render EAN-13 pattern (pattern strings of '0'/'1' with width=1 module per char)
function renderFromBinaryStrings($binPatterns, $moduleWidth, $height, $fgColor, $bgColor, $text = '')
{
    // convert binary strings to sequence of digits (1 module per char)
    $patterns = [];
    foreach ($binPatterns as $bp) {
        // break bp (string of 0/1) into groups of consecutive chars -> width counts
        $len = strlen($bp);
        $seq = '';
        $current = $bp[0];
        $count = 1;
        for ($i = 1; $i < $len; $i++) {
            if ($bp[$i] === $current) $count++;
            else {
                $seq .= $count;
                $current = $bp[$i];
                $count = 1;
            }
        }
        $seq .= $count;
        $patterns[] = $seq;
    }
    return renderFromPatternSequence($patterns, $moduleWidth, $height, $fgColor, $bgColor, $text);
}

// Validate and filter duplicates
$finalList = [];
$skipped = 0;
foreach ($toGenerate as $item) {
    $item = trim((string)$item);
    if ($item === '') continue;

    // normalize for UPC-A/EAN-13
    if ($symbology === 'upc_a') {
        // UPC-A requires 12 digits; if 11 given, compute check digit; if 12 used, validate
        $digits = preg_replace('/[^0-9]/', '', $item);
        if (strlen($digits) === 11) {
            $check = calculateEAN13CheckDigit('0' . $digits); // treat as EAN-13 leading 0
            $digits .= $check;
            $item = $digits;
        }
        if (strlen($digits) !== 12) {
            // skip invalid
            continue;
        }
    }
    if ($symbology === 'ean13') {
        $digits = preg_replace('/[^0-9]/', '', $item);
        if (strlen($digits) === 12) {
            $check = calculateEAN13CheckDigit($digits);
            $digits .= $check;
            $item = $digits;
        }
        if (strlen($digits) !== 13) {
            continue; // invalid
        }
    }

    if ($preventDup && in_array($item, $history, true)) {
        $skipped++;
        continue;
    }

    $finalList[] = $item;
}

if (empty($finalList)) {
    jsonError('No valid barcodes to generate (all filtered or invalid)');
}



// Generate images
$generated = []; // each entry: ['data'=>$data, 'path'=>'/uploads/bar-codes/...']
try {
    foreach ($finalList as $dataStr) {
        $img = null;

        if ($symbology === 'code39') {
            $patterns = encodeCode39($dataStr);
            if ($patterns === false) continue;
            // expand 'n' -> '1'
            $norm = [];
            foreach ($patterns as $p) {
                if ($p === 'n') $norm[] = '1';
                else $norm[] = $p;
            }
            $img = renderFromPatternSequence($norm, 2, $height, $fg, $bg, $dataStr);
        } elseif ($symbology === 'code128') {
            $patterns = encodeCode128B($dataStr);
            if ($patterns === false) continue;
            //$img = renderFromPatternSequence($patterns, 1, $height, $fg, $bg, $dataStr);

            //update code for size starts here
            // Target width from POST (fallback to 300px)
            $targetWidth = $width > 0 ? $width : 300;

            // Calculate total modules in barcode
            $totalModules = 0;
            foreach ($patterns as $pat) {
                for ($i = 0; $i < strlen($pat); $i++) {
                    $totalModules += intval($pat[$i]);
                }
            }

            // Code 128 requires 10 modules total quiet zone
            $quietModules = 10;

            // Dynamically calculate module width
            $moduleWidth = floor($targetWidth / ($totalModules + $quietModules));
            if ($moduleWidth < 1) $moduleWidth = 1;

            // Render with normalized width
            $img = renderFromPatternSequence(
                $patterns,
                $moduleWidth,
                $height,
                $fg,
                $bg,
                $dataStr
            );
            //update code for size ends here
        } elseif ($symbology === 'ean13' || $symbology === 'upc_a') {
            $digits13 = $dataStr;
            if ($symbology === 'upc_a') {
                // convert UPC-A (12) to EAN-13 by prepending 0
                if (strlen($digits13) === 12) $digits13 = '0' . $digits13;
            }
            $binPatterns = encodeEAN13($digits13);
            if ($binPatterns === false) continue;
            $img = renderFromBinaryStrings($binPatterns, 2, $height, $fg, $bg, $dataStr);
        } else {
            continue; // unsupported
        }

        if ($img === null) continue;

        $filename = 'barcode_' . preg_replace('/[^A-Za-z0-9_\-]/', '_', $dataStr) . '_' . uniqid() . '.png';
        $filepath = BARCODE_UPLOAD_DIR . $filename;
        imagepng($img, $filepath);
        imagedestroy($img);

        // store reference in DB (qr_codes table) for user (if session user exists)
        $pdo = getDbConnection();
        if ($pdo) {
            $stmt = $pdo->prepare("INSERT INTO qr_codes (user_id, qr_type, data_type, content, qr_image_path) VALUES (?, 'barcode', ?, ?, ?)");
            $userId = $_SESSION['user_id'] ?? null;
            $dataType = $symbology;
            $urlPath = 'uploads/bar-codes/' . $filename;
            $stmt->execute([$userId, $dataType, $dataStr, $urlPath]);
        } else {
            $urlPath = 'uploads/bar-codes/' . $filename;
        }

        // append to history if required
        if ($preventDup) {
            $history[] = $dataStr;
        }

        $generated[] = ['data' => $dataStr, 'url' => $urlPath];
    }

    // save history
    if ($preventDup) {
        file_put_contents($historyFile, json_encode(array_values(array_unique($history))));
    }

    if (empty($generated)) {
        jsonError('No barcodes were generated (possible invalid inputs)');
    }

    // If more than one generated, create zip
    if (count($generated) > 1) {
        // graceful fallback when ZipArchive not available
        if (!class_exists('ZipArchive')) {
            echo json_encode(['success' => true, 'generated' => $generated, 'skippedDuplicates' => $skipped, 'warning' => 'zip_not_available']);
            exit;
        }

        $zipName = 'barcodes_' . uniqid() . '.zip';
        $zipPath = BARCODE_UPLOAD_DIR . $zipName;
        $zip = new ZipArchive();
        if ($zip->open($zipPath, ZipArchive::CREATE) !== TRUE) {
            jsonError('Failed to create zip archive');
        }
        foreach ($generated as $g) {
            $local = ltrim($g['url'], '/');
            $zip->addFile(__DIR__ . '/' . $local, basename($local));
        }
        $zip->close();
        echo json_encode(['success' => true, 'zipUrl' => 'uploads/bar-codes/' . $zipName, 'generated' => $generated, 'skippedDuplicates' => $skipped]);
        exit;
    }

    // single result
    echo json_encode(['success' => true, 'generated' => $generated, 'skippedDuplicates' => $skipped]);
    exit;
} catch (Exception $e) {
    jsonError('Server error: ' . $e->getMessage());
}
