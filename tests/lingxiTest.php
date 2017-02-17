<?php
require '../vendor/autoload.php';
require '../lib/lingxi.php';

use Noodlehaus\Config;
use Lingxi\Signature\Client;
use PHPUnit\Framework\TestCase;

class LingxiTest extends TestCase{
    public function testGetFormList(){
        $conf = new Config('config.json');
        $api_key = $conf->get('api_key');
        $api_secret = $conf->get('api_secret');
        $api_client = new Client($api_key, $api_secret);
        $form_list = get_form_list($api_client);
        $this->assertNotEmpty($form_list);
    }

    public function testGetForm(){
        $conf = new Config('config.json');
        $api_key = $conf->get('api_key');
        $api_secret = $conf->get('api_secret');
        $form_id = $conf->get('test_form_id');
        $api_client = new Client($api_key, $api_secret);
        $form = get_form($api_client, $form_id);
        $this->assertNotEmpty($form);
    }

    public function testGetFormFill(){
        $conf = new Config('config.json');
        $api_key = $conf->get('api_key');
        $api_secret = $conf->get('api_secret');
        $form_id = $conf->get('test_form_id');
        $api_client = new Client($api_key, $api_secret);
        $fill_list = get_form_fill($api_client, $form_id);
        $this->assertNotEmpty($fill_list);
    }
}
?>
