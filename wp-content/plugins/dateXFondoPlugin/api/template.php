<?php


function create_endpoint_datefondo_edit_header_template()
{

    register_rest_route('datexfondoplugin/v1', 'table/headertemplate', array(
        'methods' => 'POST',
        'callback' => 'esegui_modifica_header_template'
    ));


}

function esegui_modifica_header_template($params)
{
    \dateXFondoPlugin\MasterTemplateRepository::edit_header_template($params);
    $data = ['message' => 'Modifica fondo e anno effettuata correttamente'];
    $response = new WP_REST_Response($data);
    $response->set_status(201);
    return $response;
}

add_action('rest_api_init', 'esegui_modifica_header_template');