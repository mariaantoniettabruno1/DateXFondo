<?php

//endpoint of the editing of template fondo header
function create_endpoint_datefondo_edit_header_template()
{

    register_rest_route('datexfondoplugin/v1', 'templateheader', array(
        'methods' => 'POST',
        'callback' => 'esegui_modifica_header_template'
    ));


}

function esegui_modifica_header_template($params)
{
    \dateXFondoPlugin\MasterTemplateRepository::edit_header_template($params);
    $data = ['message' => 'Modifica header effettuata correttamente'];
    $response = new WP_REST_Response($data);
    $response->set_status(201);
    return $response;
}

add_action('rest_api_init', 'create_endpoint_datefondo_edit_header_template');

//endpoint of the editing of template fondo row

function create_endpoint_datefondo_edit_row()
{

    register_rest_route('datexfondoplugin/v1', 'editrow', array(
        'methods' => 'POST',
        'callback' => 'esegui_modifica_riga'
    ));


}

function esegui_modifica_riga($params)
{
    $bool_res = \dateXFondoPlugin\MasterTemplateRepository::edit_row($params);
    $data = ['update' => $bool_res, 'message' => 'Modifica riga effettuata correttamente'];
    $response = new WP_REST_Response($data);
    $response->set_status(201);
    return $response;
}

add_action('rest_api_init', 'create_endpoint_datefondo_edit_row');

//endpoint of the freeze of template fondo (disable the editing)

function create_endpoint_datefondo_not_editable_template()
{

    register_rest_route('datexfondoplugin/v1', 'disabletemplate', array(
        'methods' => 'POST',
        'callback' => 'esegui_blocca_modifica_template'
    ));


}

function esegui_blocca_modifica_template($params)
{
    $success = \dateXFondoPlugin\MasterTemplateRepository::set_template_not_editable($params);
    if($success){
        $data = [ 'message' => 'Blocco modifica template andato a buon fine'];
        $response = new WP_REST_Response($data);
        $response->set_status(200);
        return $response;
    } else {
        $data = [ 'message' => 'Blocco modifica template non andato a buon fine'];
        $response = new WP_REST_Response($data);
        $response->set_status(400);
        return $response;
    }
}

add_action('rest_api_init', 'create_endpoint_datefondo_not_editable_template');

//endpoint of the activation of template fondo deleted row (logical deletion)

function create_endpoint_datefondo_active_row()
{

    register_rest_route('datexfondoplugin/v1', 'activerow', array(
        'methods' => 'POST',
        'callback' => 'esegui_attiva_riga'
    ));


}

function esegui_attiva_riga($params)
{
    $bool_res = \dateXFondoPlugin\MasterTemplateRepository::active_row($params);
    $data = ['update' => $bool_res, 'message' => 'Riga attivata correttamente'];
    $response = new WP_REST_Response($data);
    $response->set_status(201);
    return $response;
}

add_action('rest_api_init', 'create_endpoint_datefondo_active_row');

//endpoint of the duplication of the template fondo
function create_endpoint_datefondo_duplicate_template()
{

    register_rest_route('datexfondoplugin/v1', 'duplicatetemplate', array(
        'methods' => 'POST',
        'callback' => 'esegui_duplicazione_template'
    ));


}

function esegui_duplicazione_template($params)
{
    $bool_res = \dateXFondoPlugin\MasterTemplateRepository::duplicate_template($params);
    $data = ['duplicated template' => $bool_res, 'message' => 'Template duplicato correttamente'];
    $response = new WP_REST_Response($data);
    $response->set_status(201);
    return $response;
}

add_action('rest_api_init', 'create_endpoint_datefondo_duplicate_template');



