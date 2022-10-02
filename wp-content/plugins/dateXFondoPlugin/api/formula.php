<?php
function create_endpoint_datefondo_creazione_formula()
{

    register_rest_route('datexfondoplugin/v1', 'formula', array(
        'methods' => 'POST',
        'callback' => 'esegui_creazione_formula'
    ));
}

function esegui_creazione_formula($params)
{
    if (!isset($params['visibile'])) {
        $params['visibile'] = 1;
    }

    $insert_id = FormulaRepository::create_formula($params);
    $data = ['id' => $insert_id, 'message' => 'Formula creata correttamente'];
    $response = new WP_REST_Response($data);
    $response->set_status(201);
    return $response;
}

add_action('rest_api_init', 'create_endpoint_datefondo_creazione_formula');
