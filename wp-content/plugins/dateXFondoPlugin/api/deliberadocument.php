<?php

use dateXFondoPlugin\DeliberaDocumentRepository;

function create_endpoint_datefondo_edit_delibera_document()
{

    register_rest_route('datexfondoplugin/v1', 'deliberadocument', array(
        'methods' => 'POST',
        'callback' => 'edit_delibera_document'
    ));


}

function edit_delibera_document($params)
{
    $affected_rows = DeliberaDocumentRepository::edit_delibera_document($params);
    $data = [ 'rows' => $affected_rows, 'message' => 'Modifica effettuata correttamente'];
    $response = new WP_REST_Response($data);
    $response->set_status(201);
    return $response;
}

add_action('rest_api_init', 'create_endpoint_datefondo_edit_delibera_document');

function create_endpoint_datefondo_edit_determina_document()
{

    register_rest_route('datexfondoplugin/v1', 'determinadocument', array(
        'methods' => 'POST',
        'callback' => 'edit_determina_document'
    ));


}

function edit_determina_document($params)
{
    $affected_rows = DeliberaDocumentRepository::edit_determina_document($params);
    $data = [ 'rows' => $affected_rows, 'message' => 'Modifica effettuata correttamente'];
    $response = new WP_REST_Response($data);
    $response->set_status(201);
    return $response;
}

add_action('rest_api_init', 'create_endpoint_datefondo_edit_determina_document');

function create_endpoint_datefondo_edit_delibera_header()
{

    register_rest_route('datexfondoplugin/v1', 'documentheader', array(
        'methods' => 'POST',
        'callback' => 'edit_delibera_header'
    ));


}

function edit_delibera_header($params)
{
    DeliberaDocumentRepository::edit_delibera_header($params);
    $data = [ 'message' => 'Modifica effettuata correttamente'];
    $response = new WP_REST_Response($data);
    $response->set_status(201);
    return $response;
}

add_action('rest_api_init', 'create_endpoint_datefondo_edit_delibera_header');