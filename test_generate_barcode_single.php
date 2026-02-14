<?php
chdir(__DIR__);
$_SERVER['REQUEST_METHOD'] = 'POST';
$_POST = [
    'mode' => 'single',
    'data' => 'ABC123',
    'symbology' => 'code128',
    'width' => '600',
    'height' => '120',
    'fgColor' => '#000000',
    'bgColor' => '#ffffff',
    'fontSize' => '12',
];
include 'generate-barcode.php';
