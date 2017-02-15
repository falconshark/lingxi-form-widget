<?php

/**
* get_form_list Get form list from Lingxi.
* @param  object $api_client API client of Lingxi with api key and secret.
* @return object $result Result which contain form data
*/
function get_form_list($api_client){
    try {
        $response = $api_client->get('/form/list');
        $result = $response->getData();
        return $result;

    } catch (\Exception $e) {
        echo $e->getMessage();
    }
}

/**
* get_form_fill Get form list from Lingxi.
* @param  object $api_client API client of Lingxi with api key and secret.
* @return object $result Result which contain form fill -data
*/
function get_form_fill($api_client, $id){
    try {
        $data = ['form_id' => $id];
        $response = $api_client->get('/form/form_fill/list', $data);
        $result = $response->getData();
        return $result;

    } catch (\Exception $e) {
        echo $e->getMessage();
    }
}

?>
