<?php
// Quick CLI test to exercise generate-barcode.php (counter mode, multiple items -> should create ZIP)
chdir(__DIR__);
$_SERVER['REQUEST_METHOD'] = 'POST';
$_POST = [
    'mode' => 'counter',
    'prefix' => 'TST',
    'start' => '1000',
    'increment' => '1',
    'count' => '3',
    'symbology' => 'code128',
    'width' => '600',
    'height' => '120',
    'fgColor' => '#000000',
    'bgColor' => '#ffffff',
    'fontSize' => '12',
    'preventDuplicates' => '0',
];

include 'generate-barcode.php';
