<?php

/**
* get_form_list Get form list from Lingxi.
* @param  object $api_client API client of Lingxi with api key and secret.
* @return object $result Result which contain form list
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
* get_form Get form data from Lingxi.
* @param  object $api_client API client of Lingxi with api key and secret.
* @param  int $id Target form id.
* @return object $result Result which contain form data
*/
function get_form($api_client, $id){
	try {
		$data = ['id' => $id];
		$response = $api_client->get('/form/show', $data);
		$result = $response->getData();
		return $result;

	} catch (\Exception $e) {
		echo $e->getMessage();
	}
}

/**
* get_form_fill Get form fill list from Lingxi.
* @param  object $api_client API client of Lingxi with api key and secret.
* @param  int $id Target form id.
* @return object $result Result which contain form fill list.
*/
function get_form_fill($api_client, $id){
	try {
		$data = ['form_id' => $id];
		$response = $api_client->get('/form/form_fill/list', $data);
		$result = $response->getData();
		return $result;

	} catch (\Exception $e) {
		if($e->getMessage() !== 'Lingxi Api return error: Not found model'){
			echo $e->getMessage();	
		}
	}
}

?>
