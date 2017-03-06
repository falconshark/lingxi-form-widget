<?php
require '../vendor/autoload.php';
require '../lib/lingxi.php';

use Noodlehaus\Config;
use Lingxi\Signature\Client;

$conf = new Config('config.json');
$api_key = $conf->get('api_key');
$api_secret = $conf->get('api_secret');
$form_id = $conf->get('test_form_id');
$api_client = new Client($api_key, $api_secret);

$count = 0;
$page = 1;

$data = ['form_id' => $form_id];
$response = $api_client->get('/form/form_fill/list', $data);
$result = $response->getMeta();
var_dump($result);

 ?>
