<?php
/***
 * Plugin Name: dateXFondo Plugin
 * Plugin URI:
 * Description:
 * Version: 0.1
 * Author: MG3
 * Author URI:
 */
require_once(plugin_dir_path(__FILE__) . 'common.php');
require_once(plugin_dir_path(__FILE__) . 'repositories/CustomTable.php');
require_once(plugin_dir_path(__FILE__) . 'repositories/Connection.php');
require_once(plugin_dir_path(__FILE__) . 'repositories/CreateFondo.php');
require_once(plugin_dir_path(__FILE__) . 'repositories/TemplateHistory.php');
require_once(plugin_dir_path(__FILE__) . 'repositories/MasterTemplateRepository.php');
require_once(plugin_dir_path(__FILE__) . 'repositories/MasterTemplateRowRepository.php');
require_once(plugin_dir_path(__FILE__) . 'repositories/DisabledTemplateRow.php');
require_once(plugin_dir_path(__FILE__) . 'repositories/DocumentTable.php');
require_once(plugin_dir_path(__FILE__) . 'repositories/FormulaRepository.php');
require_once(plugin_dir_path(__FILE__) . 'repositories/SlaveFormulaTable.php');
require_once(plugin_dir_path(__FILE__) . 'views/table/ShortCodeCustomTable.php');
require_once(plugin_dir_path(__FILE__) . 'views/table/ShortCodeTable.php');
require_once(plugin_dir_path(__FILE__) . 'views/table/live_edit.php');
require_once(plugin_dir_path(__FILE__) . 'views/fondo/ShortCodeCreateFondo.php');
require_once(plugin_dir_path(__FILE__) . 'views/template/MasterTemplate.php');
require_once(plugin_dir_path(__FILE__) . 'views/template/components/MasterTemplateHeader.php');
require_once(plugin_dir_path(__FILE__) . 'views/template/components/MasterTemplateTable.php');
require_once(plugin_dir_path(__FILE__) . 'views/template/components/MasterTemplateNewRow.php');
require_once(plugin_dir_path(__FILE__) . 'views/template/components/MasterTemplateNewDecurtationRow.php');
require_once(plugin_dir_path(__FILE__) . 'views/template/components/MasterTemplateNewSpecialRow.php');
require_once(plugin_dir_path(__FILE__) . 'views/template/ShortCodeTemplateHistory.php');
require_once(plugin_dir_path(__FILE__) . 'views/template/ShortCodeDisabledTemplateRow.php');
require_once(plugin_dir_path(__FILE__) . 'views/formula/Formula.php');
require_once(plugin_dir_path(__FILE__) . 'views/formula/SlaveShortCodeFormulaTable.php');
require_once(plugin_dir_path(__FILE__) . 'views/document/ShortCodeDocumentTable.php');
require_once(plugin_dir_path(__FILE__) . 'views/formula/components/FormulaCard.php');
require_once(plugin_dir_path(__FILE__) . 'views/formula/components/FormulaSidebar.php');
require_once(plugin_dir_path(__FILE__) . 'api/formula.php');
require_once(plugin_dir_path(__FILE__) . 'api/template.php');
require_once(plugin_dir_path(__FILE__) . 'api/newrow.php');


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
   // add_shortcode('post_duplicate_old_template', 'duplicate_old_template');
    add_shortcode('post_visualize_master_template', 'visualize_master_template');
    add_shortcode('post_visualize_history_template', 'visualize_history_template');
    add_shortcode('post_visualize_disabled_template_row', 'visualize_disabled_template_row');
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

function visualize_master_template()
{
    \dateXFondoPlugin\MasterTemplate::render();

}
function visualize_history_template()
{
    \dateXFondoPlugin\ShortCodeTemplateHistory::visualize_history_template();

}
function visualize_disabled_template_row()
{
    \dateXFondoPlugin\ShortCodeDisabledTemplateRow::visualize_disabled_template_row();

}

function visualize_formula_template()
{
    \dateXFondoPlugin\Formula::render();
}

function visualize_slave_formula_template()
{
    \dateXFondoPlugin\SlaveShortCodeFormulaTable::visualize_slave_formula_template();

}

//function duplicate_old_template()
//{
//    \dateXFondoPlugin\MasterTemplate::duplicate_old_template();
//
//}

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
function create_endpoint_datefondo_attiva_riga()
{

    register_rest_route('datexfondoplugin/v1', 'table/enablerow', array(
        'methods' => 'POST',
        'callback' => 'esegui_abilitazione_riga'
    ));


}

function esegui_abilitazione_riga($params)
{
    return \dateXFondoPlugin\abilita_riga($params);
}

add_action('rest_api_init', 'create_endpoint_datefondo_attiva_riga');

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



function create_endpoint_datefondo_creazione_riga_decurtazione_speciale()
{

    register_rest_route('datexfondoplugin/v1', 'table/newrowspdec', array(
        'methods' => 'POST',
        'callback' => 'esegui_creazione_riga_decurtazione_speciale'
    ));


}

function esegui_creazione_riga_decurtazione_speciale($params)
{
    $insert_id = \dateXFondoPlugin\create_special_decurtation_row($params);
    $data = ['id' => $insert_id, 'message' => 'Creazione riga decurtazione speciale effettuata correttamente'];
    $response = new WP_REST_Response($data);
    $response->set_status(201);
    return $response;
}

add_action('rest_api_init', 'create_endpoint_datefondo_creazione_riga_decurtazione_speciale');