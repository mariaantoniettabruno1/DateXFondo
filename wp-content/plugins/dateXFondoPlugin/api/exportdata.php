<?php
function create_endpoint_datefondo_export_to_slave()
{

    register_rest_route('datexfondoplugin/v1', 'exportdata', array(
        'methods' => 'POST',
        'callback' => 'export_data_slave'
    ));


}

function export_data_slave($params)
{
    $bool_res = \dateXFondoPlugin\ExportDataRepository::export_data($params);
    $data = ['export' => $bool_res, 'message' => 'Esportazione andata a buon fine'];
    $response = new WP_REST_Response($data);
    $response->set_status(201);
    return $response;
}

add_action('rest_api_init', 'create_endpoint_datefondo_export_to_slave');
