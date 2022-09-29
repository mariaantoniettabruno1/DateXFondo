<?php
/***
 * Plugin Name: dateXFondo Plugin
 * Plugin URI:
 * Description:
 * Version: 0.1
 * Author: MG3
 * Author URI:
 */
require_once(plugin_dir_path(__FILE__) . 'table/CustomTable.php');
require_once(plugin_dir_path(__FILE__) . 'common.php');
require_once(plugin_dir_path(__FILE__) . 'database/Connection.php');
require_once(plugin_dir_path(__FILE__) . 'table/CustomTable.php');
require_once(plugin_dir_path(__FILE__) . 'table/ShortCodeCustomTable.php');
require_once(plugin_dir_path(__FILE__) . 'table/ShortCodeTable.php');
require_once(plugin_dir_path(__FILE__) . 'fondo/CreateFondo.php');
require_once(plugin_dir_path(__FILE__) . 'fondo/ShortCodeCreateFondo.php');
require_once(plugin_dir_path(__FILE__) . 'template/ShortCodeCreateNewTemplate.php');
require_once(plugin_dir_path(__FILE__) . 'template/DuplicateOldTemplate.php');
require_once(plugin_dir_path(__FILE__) . 'template/ShortCodeDuplicateOldTemplate.php');
require_once(plugin_dir_path(__FILE__) . 'formula/ShortCodeFormulaTable.php');
require_once(plugin_dir_path(__FILE__) . 'formula/FormulaTable.php');
require_once(plugin_dir_path(__FILE__) . 'formula/SlaveFormulaTable.php');
require_once(plugin_dir_path(__FILE__) . 'formula/SlaveShortCodeFormulaTable.php');
require_once(plugin_dir_path(__FILE__) . 'document/DocumentTable.php');
require_once(plugin_dir_path(__FILE__) . 'document/ShortCodeDocumentTable.php');
require_once(plugin_dir_path(__FILE__) . 'table/live_edit.php');


/**
 * Aggiungo librerie javascript a wordpress
 */


function custom_scripts_method()
{
    wp_register_script('customscripts', DateXFondoCommon::get_base_url() . '/libs/jquery.min.js', array('jquery'), '1.0.0', true);
    wp_enqueue_script('customscripts');
}

/**
 * Action per l'inizializzazione di tutte le function collegate agli shortcode del plugin
 */


add_action('init', 'shortcodes_init');

function shortcodes_init()
{
    add_shortcode('post_custom_table', 'call_custom_table');
    add_shortcode('post_table', 'call_table');
    add_shortcode('post_create_fondo', 'create_new_fondo');
    add_shortcode('post_duplicate_old_template', 'duplicate_old_template');
    add_shortcode('post_visualize_old_template', 'visualize_old_template');
    add_shortcode('post_visualize_formula_template', 'visualize_formula_template');
    add_shortcode('post_visualize_slave_formula_template', 'visualize_slave_formula_template');
    add_shortcode('post_document_template', 'document_template');
}


function call_custom_table()
{
    \dateXFondoPlugin\ShortCodeCustomTable::visualize_custom_table();

}

function call_table()
{
    \dateXFondoPlugin\ShortCodeTable::visualize_table();

}

function create_new_fondo()
{
    \dateXFondoPlugin\ShortCodeCreateFondo::create_fondo();

}

function visualize_old_template()
{
    \dateXFondoPlugin\ShortCodeDuplicateOldTemplate::visualize_old_template();

}

function visualize_formula_template()
{
    \dateXFondoPlugin\ShortCodeFormulaTable::visualize_formula_template();

}

function visualize_slave_formula_template()
{
    \dateXFondoPlugin\SlaveShortCodeFormulaTable::visualize_slave_formula_template();

}

function duplicate_old_template()
{
    \dateXFondoPlugin\ShortCodeDuplicateOldTemplate::duplicate_old_template();

}

function document_template()
{
    \dateXFondoPlugin\ShortCodeDocumentTable::visualize_document_template();

}

//route ed endpoint per far funzionare la modifica campi della table contenente i dati dell'anno corrente per il master
function create_endpoint_datefondo()
{

    register_rest_route('datexfondoplugin/v1', 'table/edit', array(
        'methods' => 'POST',
        'callback' => 'esegui_modifica_campi'
    ));


}

function esegui_modifica_campi($params)
{
    \dateXFondoPlugin\modifica_campi($params);
    $data = ['params' => $params, 'message' => 'Endpoint di edit'];
    $response = new WP_REST_Response($data);
    $response->set_status(200);
    return $response;
}

add_action('rest_api_init', 'create_endpoint_datefondo');

//route ed endpoint per far funzionare la modifica campi della table contenente i dati dell'anno corrente per lo svale
function create_endpoint_datefondo_slave()
{

    register_rest_route('datexfondoplugin/v1', 'table/editslave', array(
        'methods' => 'POST',
        'callback' => 'esegui_modifica_campi_slave'
    ));


}

function esegui_modifica_campi_slave($params)
{
    \dateXFondoPlugin\modifica_campi_slave($params);
    $data = ['params' => $params, 'message' => 'Endpoint di edit per lo slave'];
    $response = new WP_REST_Response($data);
    $response->set_status(200);
    return $response;
}

add_action('rest_api_init', 'create_endpoint_datefondo_slave');

//route ed endpoint per far funzionare la modifica campi del table template che viene duplicato in fase di creazione di un nuovo fondo
function create_endpoint_datefondo_nuovo()
{

    register_rest_route('datexfondoplugin/v1', 'table/editnewfondo', array(
        'methods' => 'POST',
        'callback' => 'esegui_modifica_campi_nuovo_template'
    ));


}

function esegui_modifica_campi_nuovo_template($params)
{
    return \dateXFondoPlugin\modifica_campi_nuovo_template($params);
}

add_action('rest_api_init', 'create_endpoint_datefondo_nuovo');

function create_endpoint_datefondo_creazione_riga()
{

    register_rest_route('datexfondoplugin/v1', 'table/newrow', array(
        'methods' => 'POST',
        'callback' => 'esegui_creazione_riga'
    ));


}

function esegui_creazione_riga($params)
{
    $insert_id = \dateXFondoPlugin\creazione_nuova_riga($params);
    $data = ['id' => $insert_id, 'message' => 'Bello DateXFondo'];
    $response = new WP_REST_Response($data);
    $response->set_status(201);
    return $response;
}

add_action('rest_api_init', 'create_endpoint_datefondo_creazione_riga');
function create_endpoint_datefondo_creazione_riga_decurtazione()
{

    register_rest_route('datexfondoplugin/v1', 'table/newrowdec', array(
        'methods' => 'POST',
        'callback' => 'esegui_creazione_riga_decurtazione'
    ));


}

function esegui_creazione_riga_decurtazione($params)
{
    $insert_id = \dateXFondoPlugin\creazione_nuova_riga_dec($params);
    $data = ['id' => $insert_id, 'message' => 'Riga decurtazione creata correttamente'];
    $response = new WP_REST_Response($data);
    $response->set_status(201);
    return $response;
}

add_action('rest_api_init', 'create_endpoint_datefondo_creazione_riga_decurtazione ');
function create_endpoint_datefondo_caricamento()
{

    register_rest_route('datexfondoplugin/v1', 'table/new', array(
        'methods' => 'POST',
        'callback' => 'esegui_caricamento_campi'
    ));


}

function esegui_caricamento_campi($params)
{
    return \dateXFondoPlugin\caricamento_campi($params);
}

add_action('rest_api_init', 'create_endpoint_datefondo_caricamento');

function create_endpoint_datefondo_disattiva_riga()
{

    register_rest_route('datexfondoplugin/v1', 'table/deleterow', array(
        'methods' => 'POST',
        'callback' => 'esegui_cancellazione_riga'
    ));


}

function esegui_cancellazione_riga($params)
{
    return \dateXFondoPlugin\cancella_riga($params);
}

add_action('rest_api_init', 'create_endpoint_datefondo_disattiva_riga');

function create_endpoint_datefondo_ereditarieta_nota_valore()
{

    register_rest_route('datexfondoplugin/v1', 'table/heredity', array(
        'methods' => 'POST',
        'callback' => 'esegui_eredita_nota_valore'
    ));


}

function esegui_eredita_nota_valore($params)
{
    return \dateXFondoPlugin\eredita_nome_valore($params);
}

add_action('rest_api_init', 'create_endpoint_datefondo_ereditarieta_nota_valore');

function create_endpoint_datefondo_creazione_formula()
{

    register_rest_route('datexfondoplugin/v1', 'table/newformula', array(
        'methods' => 'POST',
        'callback' => 'esegui_creazione_formula'
    ));


}

function esegui_creazione_formula($params)
{
    $insert_id = \dateXFondoPlugin\create_formula($params);
    $data = ['id' => $insert_id, 'message' => 'Formula creata correttamente'];
    $response = new WP_REST_Response($data);
    $response->set_status(201);
    return $response;
}

add_action('rest_api_init', 'create_endpoint_datefondo_creazione_formula');

function create_endpoint_datefondo_edit_fondo_anno()
{

    register_rest_route('datexfondoplugin/v1', 'table/editfondoanno', array(
        'methods' => 'POST',
        'callback' => 'esegui_modifica_fondo_anno'
    ));


}

function esegui_modifica_fondo_anno($params)
{
    $insert_id = \dateXFondoPlugin\edit_fondo_anno($params);
    $data = ['id' => $insert_id, 'message' => 'Modifica fondo e anno effettuata correttamente'];
    $response = new WP_REST_Response($data);
    $response->set_status(201);
    return $response;
}

add_action('rest_api_init', 'create_endpoint_datefondo_edit_fondo_anno');