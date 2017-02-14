<?php
require '../vendor/autoload.php';
require '../lib/lingxi.php';

use Noodlehaus\Config;
use Lingxi\Signature\Client;
use PHPUnit\Framework\TestCase;

class LingxiTest extends TestCase{
    public function testGetFormList(){
        $conf = new Config('config.json');
        $apiKey = $conf->get('api_key');
        $apiSecret = $conf->get('api_secret');
        $apiClient = new Client($apiKey, $apiSecret);
        $formList = getFormList($apiClient);
        $this->assertNotEmpty($formList);
    }

    public function testGetFormFill(){
        $conf = new Config('config.json');
        $apiKey = $conf->get('api_key');
        $apiSecret = $conf->get('api_secret');
        $formId = $conf->get('test_form_id');
        $apiClient = new Client($apiKey, $apiSecret);
        $fillList = getFormFill($apiClient, $formId);
        $this->assertNotEmpty($fillList);
    }
}
?>
