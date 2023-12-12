<?php

use dateXFondoPlugin\MasterCitiesRepository;


function create_endpoint_datefondo_edit_cities_user()
{

    register_rest_route('datexfondoplugin/v1', 'citiesuser', array(
        'methods' => 'POST',
        'callback' => 'edit_cities_user'
    ));


}

function edit_cities_user($params)
{
    $data =  (new dateXFondoPlugin\MasterCitiesRepository)->edit_cities_user($params);
    $data = ['data'=> $data, 'message' => 'Operazione andata a buon fine'];
    $response = new WP_REST_Response($data);
    $response->set_status(201);
    return $response;
}

add_action('rest_api_init', 'create_endpoint_datefondo_edit_cities_user');

function create_endpoint_datefondo_change_selected_user()
{

    register_rest_route('datexfondoplugin/v1', 'selectuser', array(
        'methods' => 'POST',
        'callback' => 'change_selected_user'
    ));


}

function change_selected_user($params)
{
    $data =  (new dateXFondoPlugin\MasterCitiesRepository)->getCheckedCities($params);
    $data = ['data'=> $data, 'message' => 'Operazione andata a buon fine'];
    $response = new WP_REST_Response($data);
    $response->set_status(201);
    return $response;
}

add_action('rest_api_init', 'create_endpoint_datefondo_change_selected_user');