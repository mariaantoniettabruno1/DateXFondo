<?php
function create_endpoint_datefondo_edit_document_row()
{

    register_rest_route('datexfondoplugin/v1', 'document/row', array(
        'methods' => 'POST',
        'callback' => 'modifica_riga_documento'
    ));


}

function modifica_riga_documento($params)
{
    $bool_res = DocumentRepository::edit_document_row($params);
    $data = ['update' => $bool_res, 'message' => 'Modifica riga del documento effettuata correttamente'];
    $response = new WP_REST_Response($data);
    $response->set_status(201);
    return $response;
}

add_action('rest_api_init', 'create_endpoint_datefondo_edit_document_row');

function create_endpoint_datefondo_disattiva_riga_documento()
{

    register_rest_route('datexfondoplugin/v1', 'document/row/del', array(
        'methods' => 'POST',
        'callback' => 'esegui_cancellazione_riga_documento'
    ));


}

function esegui_cancellazione_riga_documento($params)
{
    DocumentRepository::delete_document_row($params);
    $data = ['message' => 'Riga cancellata correttamente'];
    $response = new WP_REST_Response($data);
    $response->set_status(201);
    return $response;
}

add_action('rest_api_init', 'create_endpoint_datefondo_disattiva_riga_documento');