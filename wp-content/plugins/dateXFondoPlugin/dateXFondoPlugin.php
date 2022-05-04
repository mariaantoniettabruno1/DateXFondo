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

function duplicate_old_template()
{
    \dateXFondoPlugin\ShortCodeDuplicateOldTemplate::duplicate_old_template();

}

//route ed endpoint per far funzionare la modifica campi della table contenente i dati dell'anno corrente
function create_endpoint_datefondo()
{

    register_rest_route('datexfondoplugin/v1', 'table/edit', array(
        'methods' => 'POST',
        'callback' => 'esegui_modifica_campi'
    ));


}

function esegui_modifica_campi($params)
{
    return \dateXFondoPlugin\modifica_campi($params);
}

add_action('rest_api_init', 'create_endpoint_datefondo');


////route ed endpoint per far funzionare la modifica campi del table template che viene duplicato in fase di creazione di un nuovo fondo
//function create_endpoint_datefondo_nuovo()
//{
//
//    register_rest_route('datexfondoplugin/v1', 'table/editnewfondo', array(
//        'methods' => 'POST',
//        'callback' => 'esegui_modifica_campi_nuovo_template'
//    ));
//
//
//}
//
//function esegui_modifica_campi_nuovo_template($params)
//{
//    return \dateXFondoPlugin\modifica_campi_nuovo_template($params);
//}

//add_action('rest_api_init', 'create_endpoint_datefondo_nuovo');
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

