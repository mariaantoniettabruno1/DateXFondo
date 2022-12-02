<?php

function create_endpoint_datefondo_edit_header_regioni_document()
{

    register_rest_route('datexfondoplugin/v1', 'regionidocumentheader', array(
        'methods' => 'POST',
        'callback' => 'esegui_modifica_header_regioni_document'
    ));


}

function esegui_modifica_header_regioni_document($params)
{
    \dateXFondoPlugin\RegioniDocumentRepository::edit_regioni_document_header($params);
    $data = ['message' => 'Modifica header documento effettuata correttamente'];
    $response = new WP_REST_Response($data);
    $response->set_status(201);
    return $response;
}

add_action('rest_api_init', 'create_endpoint_datefondo_edit_header_regioni_document');