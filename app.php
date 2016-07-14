<?php

require_once('models/Error.php');
require_once('features/bootstrap/Discounter.php');

$error = new Models\Error;

array_shift($argv);

if ( 0 >= count($argv) || !preg_match('/offer/', $argv[0]) || !preg_match('/path/', $argv[1]) ) {
    die($error->get('command'));
}

$code = str_replace('offer:', '',  $argv[0]);
$path = str_replace('path:', '', $argv[1]);

if ( !file_exists($path) ) {
    die($error->get('Invalid file supplied'));
}

$discounter = new Discounter();
$order = simplexml_load_file($path) or die($error['Cannot create object']);
$data = $discounter->make($order);

if ( !$discounter->validateKeys($data) ) {
    die($error->get('Invalid keys'));
}

switch ( $code ) {
    case '3for2':
        $offers = $discounter->find($code, 'code');
        $total = $discounter->total($code, true);
        break;
    default:
        $total = (float) $order->total;
        break;
}

die($total);

?>
