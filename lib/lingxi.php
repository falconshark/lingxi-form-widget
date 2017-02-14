<?php

/**
* getFormList Get form list from Lingxi.
* @param  object $apiClient API client of Lingxi with api key and secret.
* @return object $result Result which contain form data
*/
function getFormList($apiClient){
    try {
        $response = $apiClient->get('/form/list');
        $result = $response->getData();
        return $result;

    } catch (\Exception $e) {
        echo $e->getMessage();
    }
}

/**
* getFormFill Get form list from Lingxi.
* @param  object $apiClient API client of Lingxi with api key and secret.
* @return object $result Result which contain form fill -data
*/
function getFormFill($apiClient, $id){
    try {
        $data = ['form_id' => $id];
        $response = $apiClient->get('/form/form_fill/list', $data);
        $result = $response->getData();
        return $result;

    } catch (\Exception $e) {
        echo $e->getMessage();
    }
}

?>
