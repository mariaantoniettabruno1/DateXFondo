<?php
/***
 * Plugin Name: dateXFondo Plugin
 * Plugin URI:
 * Description:
 * Version: 0.1
 * Author: MG3
 * Author URI:
 */

use dateXFondoPlugin\DateXFondoCommon;

require_once(plugin_dir_path(__FILE__) . 'common.php');
require_once(plugin_dir_path(__FILE__) . 'repositories/CustomTable.php');
require_once(plugin_dir_path(__FILE__) . 'repositories/Connection.php');
require_once(plugin_dir_path(__FILE__) . 'repositories/MasterTemplateRepository.php');
require_once(plugin_dir_path(__FILE__) . 'repositories/MasterTemplateRowRepository.php');
require_once(plugin_dir_path(__FILE__) . 'repositories/DisabledTemplateRow.php');
require_once(plugin_dir_path(__FILE__) . 'repositories/DocumentRepository.php');
require_once(plugin_dir_path(__FILE__) . 'repositories/DeliberaDocumentRepository.php');
require_once(plugin_dir_path(__FILE__) . 'repositories/FormulaRepository.php');
require_once(plugin_dir_path(__FILE__) . 'repositories/RegioniDocumentRepository.php');
require_once(plugin_dir_path(__FILE__) . 'repositories/MasterJoinTableRepository.php');
require_once(plugin_dir_path(__FILE__) . 'repositories/ExportDataRepository.php');
require_once(plugin_dir_path(__FILE__) . 'views/exportdata/ExportData.php');
require_once(plugin_dir_path(__FILE__) . 'views/exportdata/components/ExportDataWizard.php');
require_once(plugin_dir_path(__FILE__) . 'views/table/MasterTemplateFormulaJoin.php');
require_once(plugin_dir_path(__FILE__) . 'views/table/components/MasterJoinTable.php');
require_once(plugin_dir_path(__FILE__) . 'views/template/MasterTemplate.php');
require_once(plugin_dir_path(__FILE__) . 'views/template/MasterAllTemplate.php');
require_once(plugin_dir_path(__FILE__) . 'views/template/MasterTemplateToActive.php');
require_once(plugin_dir_path(__FILE__) . 'views/template/MasterTemplateHistory.php');
require_once(plugin_dir_path(__FILE__) . 'views/template/components/MasterTemplateHeader.php');
require_once(plugin_dir_path(__FILE__) . 'views/template/components/MasterTemplateTable.php');
require_once(plugin_dir_path(__FILE__) . 'views/template/components/MasterAllTemplateTable.php');
require_once(plugin_dir_path(__FILE__) . 'views/template/components/MasterTemplateHistoryTable.php');
require_once(plugin_dir_path(__FILE__) . 'views/template/components/MasterTemplateNewRow.php');
require_once(plugin_dir_path(__FILE__) . 'views/template/components/MasterTemplateNewDecurtationRow.php');
require_once(plugin_dir_path(__FILE__) . 'views/template/components/MasterTemplateNewSpecialRow.php');
require_once(plugin_dir_path(__FILE__) . 'views/template/components/MasterTemplateStopEditingButton.php');
require_once(plugin_dir_path(__FILE__) . 'views/template/components/MasterTemplateToActiveRow.php');
require_once(plugin_dir_path(__FILE__) . 'views/template/ShortCodeDisabledTemplateRow.php');
require_once(plugin_dir_path(__FILE__) . 'views/formula/Formula.php');
require_once(plugin_dir_path(__FILE__) . 'views/document/AllDocument.php');
require_once(plugin_dir_path(__FILE__) . 'views/document/DocumentHistory.php');
require_once(plugin_dir_path(__FILE__) . 'views/document/components/AllDocumentTable.php');
require_once(plugin_dir_path(__FILE__) . 'views/document/MasterModelloRegioniDocument.php');
require_once(plugin_dir_path(__FILE__) . 'views/document/components/regioni/MasterModelloRegioniHeader.php');
require_once(plugin_dir_path(__FILE__) . 'views/document/components/regioni/MasterModelloRegioniTable.php');
require_once(plugin_dir_path(__FILE__) . 'views/document/components/regioni/MasterModelloRegioniCostituzioneTable.php');
require_once(plugin_dir_path(__FILE__) . 'views/document/components/regioni/MasterModelloRegioniDestinazioneTable.php');
require_once(plugin_dir_path(__FILE__) . 'views/document/components/regioni/MasterModelloRegioniStopEdit.php');
require_once(plugin_dir_path(__FILE__) . 'views/document/components/regioni/MasterModelloRegioniCostituzioneRow.php');
require_once(plugin_dir_path(__FILE__) . 'views/document/components/regioni/ModelloRegioniDestinazioneRow.php');
require_once(plugin_dir_path(__FILE__) . 'views/document/MasterModelloFondoDocument.php');
require_once(plugin_dir_path(__FILE__) . 'views/document/components/modellofondo/MasterModelloFondoHeader.php');
require_once(plugin_dir_path(__FILE__) . 'views/document/components/modellofondo/MasterModelloStopEditTable.php');
require_once(plugin_dir_path(__FILE__) . 'views/document/components/modellofondo/MasterModelloFondoNewCostituzioneRow.php');
require_once(plugin_dir_path(__FILE__) . 'views/document/components/modellofondo/MasterModelloFondoNewUtilizzoRow.php');
require_once(plugin_dir_path(__FILE__) . 'views/document/components/modellofondo/MasterModelloFondoDatiUtiliRow.php');
require_once(plugin_dir_path(__FILE__) . 'views/document/components/modellofondo/MasterModelloFondoDocumentTable.php');
require_once(plugin_dir_path(__FILE__) . 'views/document/components/modellofondo/MasterModelloFondoCostituzione.php');
require_once(plugin_dir_path(__FILE__) . 'views/document/components/modellofondo/MasterModelloFondoDatiUtili.php');
require_once(plugin_dir_path(__FILE__) . 'views/document/components/modellofondo/MasterModelloFondoUtilizzo.php');
require_once(plugin_dir_path(__FILE__) . 'views/document/DeliberaIndirizziDocument.php');
require_once(plugin_dir_path(__FILE__) . 'views/document/DeterminaCostituzioneDocument.php');
require_once(plugin_dir_path(__FILE__) . 'views/document/RelazioneIllustrativaDocument.php');
require_once(plugin_dir_path(__FILE__) . 'views/document/components/delibera/DeliberaDocumentHeader.php');
require_once(plugin_dir_path(__FILE__) . 'views/formula/components/FormulaCard.php');
require_once(plugin_dir_path(__FILE__) . 'views/formula/components/FormulaSidebar.php');
require_once(plugin_dir_path(__FILE__) . 'views/formula/components/PreviewArticolo.php');
require_once(plugin_dir_path(__FILE__) . 'api/formula.php');
require_once(plugin_dir_path(__FILE__) . 'api/document.php');
require_once(plugin_dir_path(__FILE__) . 'api/regionidocument.php');
require_once(plugin_dir_path(__FILE__) . 'api/deliberadocument.php');
require_once(plugin_dir_path(__FILE__) . 'api/template.php');
require_once(plugin_dir_path(__FILE__) . 'api/newrow.php');
require_once(plugin_dir_path(__FILE__) . 'api/joinTable.php');


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
    add_shortcode('post_join_table', 'visualize_join_table');
    add_shortcode('post_visualize_master_template', 'visualize_master_template');
    add_shortcode('post_visualize_master_all_template', 'visualize_master_all_template');
    add_shortcode('post_visualize_history_template', 'visualize_history_template');
    add_shortcode('post_visualize_disabled_template_row', 'visualize_disabled_template_row');
    add_shortcode('post_visualize_formula_template', 'visualize_formula_template');
    add_shortcode('post_document_template', 'document_template');
    add_shortcode('post_document_history', 'document_history');
    add_shortcode('post_document_table_template', 'document_table_template');
    add_shortcode('post_regioni_autonomie_locali_template', 'regioni_autonomie_locali_template');
    add_shortcode('post_delibera_template', 'delibera_template');
    add_shortcode('post_determina_costituzione_template', 'determina_costituzione_template');
    add_shortcode('post_relazione_illustrativa_template', 'relazione_illustrativa_template');
    add_shortcode('post_export_data_template', 'export_data_template');
}



function visualize_join_table()
{
    \dateXFondoPlugin\MasterTemplateFormulaJoin::render();
}



function visualize_master_all_template()
{
    \dateXFondoPlugin\MasterAllTemplate::render();

}

function visualize_master_template()
{
    \dateXFondoPlugin\MasterTemplate::render();

}

function visualize_history_template()
{
    \dateXFondoPlugin\MasterTemplateHistory::render();

}

function visualize_disabled_template_row()
{
    \dateXFondoPlugin\MasterTemplateToActive::render();

}

function visualize_formula_template()
{
    \dateXFondoPlugin\Formula::render();
}



function document_template()
{
    \dateXFondoPlugin\MasterModelloFondoDocument::render();

}

function regioni_autonomie_locali_template()
{
    \dateXFondoPlugin\MasterModelloRegioniDocument::render();

}

function delibera_template()
{
    $document = new \dateXFondoPlugin\DeliberaIndirizziDocument();
    $document->render();

}
function determina_costituzione_template()
{
    $document = new \dateXFondoPlugin\DeterminaCostituzioneDocument();
   $document->render();

}
function relazione_illustrativa_template()
{
    $document = new \dateXFondoPlugin\RelazioneIllustrativaDocument();
    $document->render();

}
function document_table_template()
{
    $document = new \dateXFondoPlugin\AllDocument();
    $document->render();

}
function document_history()
{
    $document = new \dateXFondoPlugin\DocumentHistory();
    $document->render();

}
function export_data_template()
{
    \dateXFondoPlugin\ExportData::render();

}


