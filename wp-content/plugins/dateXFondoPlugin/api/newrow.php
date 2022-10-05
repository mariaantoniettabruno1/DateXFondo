<?php
function create_endpoint_datefondo_creazione_riga()
{

    register_rest_route('datexfondoplugin/v1', 'newrow', array(
        'methods' => 'POST',
        'callback' => 'esegui_creazione_riga'
    ));


}

function esegui_creazione_riga($params)
{
    $insert_id = \dateXFondoPlugin\MasterTemplateRowRepository::create_new_row($params);
    $data = ['id' => $insert_id, 'message' => 'Riga creata correttamente'];
    $response = new WP_REST_Response($data);
    $response->set_status(201);
    return $response;
}

add_action('rest_api_init', 'create_endpoint_datefondo_creazione_riga');

function create_endpoint_datefondo_creazione_riga_decurtazione()
{

    register_rest_route('datexfondoplugin/v1', 'newdec', array(
        'methods' => 'POST',
        'callback' => 'esegui_creazione_riga_decurtazione'
    ));


}

function esegui_creazione_riga_decurtazione($params)
{
    $insert_id = \dateXFondoPlugin\MasterTemplateRowRepository::create_new_dec($params);
    $data = ['id' => $insert_id, 'message' => 'Riga decurtazione creata correttamente'];
    $response = new WP_REST_Response($data);
    $response->set_status(201);
    return $response;
}

add_action('rest_api_init', 'create_endpoint_datefondo_creazione_riga_decurtazione');

function create_endpoint_datefondo_creazione_riga_speciale()
{

    register_rest_route('datexfondoplugin/v1', 'newrowsp', array(
        'methods' => 'POST',
        'callback' => 'esegui_creazione_riga_speciale'
    ));


}

function esegui_creazione_riga_speciale($params)
{
    $insert_id = \dateXFondoPlugin\MasterTemplateRowRepository::create_new_row($params);
    $data = ['id' => $insert_id, 'message' => 'Creazione riga speciale effettuata correttamente'];
    $response = new WP_REST_Response($data);
    $response->set_status(201);
    return $response;
}

add_action('rest_api_init', 'create_endpoint_datefondo_creazione_riga_speciale');

function create_endpoint_datefondo_disattiva_riga()
{

    register_rest_route('datexfondoplugin/v1', 'delrow', array(
        'methods' => 'POST',
        'callback' => 'esegui_cancellazione_riga'
    ));


}

function esegui_cancellazione_riga($params)
{
     \dateXFondoPlugin\MasterTemplateRowRepository::delete_row($params);
    $data = [ 'message' => 'Riga cancellata correttamente'];
    $response = new WP_REST_Response($data);
    $response->set_status(201);
    return $response;
}

add_action('rest_api_init', 'create_endpoint_datefondo_disattiva_riga');