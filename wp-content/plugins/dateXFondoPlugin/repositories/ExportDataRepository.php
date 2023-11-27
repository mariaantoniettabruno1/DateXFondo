<?php

namespace dateXFondoPlugin;

use mysqli;

class ExportDataRepository
{
    public static function getAllTemplate()
    {
        $conn = new Connection();
        $mysqli = $conn->connect();
        $sql = "SELECT DISTINCT fondo,anno,descrizione_fondo,template_name,version FROM DATE_storico_template_fondo  ORDER BY ordinamento ASC";
        $result = $mysqli->query($sql);
        $row = $result->fetch_all(MYSQLI_ASSOC);
        mysqli_close($mysqli);
        return $row;
    }

    public function export_data($request)
    {

        $conn = new Connection();
        $mysqli = $conn->connect();
        $sql = "SELECT * FROM DATE_storico_template_fondo WHERE fondo=? AND anno=? AND template_name=? AND version=?";
        $stmt = $mysqli->prepare($sql);
        $stmt->bind_param("sisi", $request['fondo'], $request['anno'], $request['template_name'], $request['version']);
        $res = $stmt->execute();
        $res = $stmt->get_result();
        $template_data = $res->fetch_all(MYSQLI_ASSOC);

        $sql = "SELECT * FROM DATE_formula WHERE formula_template_name=?";
        $stmt = $mysqli->prepare($sql);
        $stmt->bind_param("s",  $request['template_name']);
        $res = $stmt->execute();
        $res = $stmt->get_result();
        $formulas = $res->fetch_all(MYSQLI_ASSOC);

        $sql = "SELECT * FROM DATE_template_formula";
        $result = $mysqli->query($sql);
        $template_formula = $result->fetch_all(MYSQLI_ASSOC);

        $sql = "SELECT * FROM DATE_documenti_odt_storico WHERE editor_name=? AND version=?";
        $stmt = $mysqli->prepare($sql);
        $stmt->bind_param("si",  $request['template_name'], $request['version']);
        $res = $stmt->execute();
        $res = $stmt->get_result();
        $odt_documents = $res->fetch_all(MYSQLI_ASSOC);

        $sql = "SELECT * FROM DATE_documento_modello_fondo_storico WHERE editor_name=? AND version=?";
        $stmt = $mysqli->prepare($sql);
        $stmt->bind_param("si",  $request['template_name'], $request['version']);
        $res = $stmt->execute();
        $res = $stmt->get_result();
        $modello_fondo_document = $res->fetch_all(MYSQLI_ASSOC);

        $sql = "SELECT * FROM DATE_documento_modello_fondo_dati_utili_storico WHERE editor_name=? AND version=?";
        $stmt = $mysqli->prepare($sql);
        $stmt->bind_param("si",  $request['template_name'], $request['version']);
        $res = $stmt->execute();
        $res = $stmt->get_result();
        $modello_dati_utili_document = $res->fetch_all(MYSQLI_ASSOC);

        $sql = "SELECT * FROM DATE_documento_modello_fondo_utilizzo_storico WHERE editor_name=? AND version=?";
        $stmt = $mysqli->prepare($sql);
        $stmt->bind_param("si",  $request['template_name'], $request['version']);
        $res = $stmt->execute();
        $res = $stmt->get_result();
        $modello_utilizzo_document = $res->fetch_all(MYSQLI_ASSOC);


        $sql = "SELECT * FROM DATE_documento_regioni_autonomie_locali_storico WHERE editor_name=? AND version=?";
        $stmt = $mysqli->prepare($sql);
        $stmt->bind_param("si",  $request['template_name'], $request['version']);
        $res = $stmt->execute();
        $res = $stmt->get_result();
        $modello_regioni_document = $res->fetch_all(MYSQLI_ASSOC);

        mysqli_close($mysqli);
        for($i = 0; $i<sizeof($request['cities']);$i++) {

            $url = DB_HOST . ":" . DB_PORT . "/";
            $username = DB_USER;
            $password = DB_PASSWORD;
            $dbname = 'c1date_'.$request['cities'][$i];
            $mysqli = new mysqli($url, $username, $password, $dbname);

            $anno = $request['anno'] - 1;
            $sql = "SELECT id_articolo,valore,nota FROM DATE_storico_template_fondo WHERE fondo=? AND anno=? AND template_name=? AND version=?";
            $stmt = $mysqli->prepare($sql);
            $stmt->bind_param("sisi", $request['fondo'], $anno, $request['template_name'], $request['version']);
            $stmt->execute();
            $res = $stmt->get_result();
            $heredity = $res->fetch_all(MYSQLI_ASSOC);
            if (sizeof($heredity) == 0) {
                $version = $request['version'] - 1;
                $sql = "SELECT id_articolo,valore,nota FROM DATE_storico_template_fondo WHERE fondo=? AND anno=? AND template_name=? AND version=?";
                $stmt = $mysqli->prepare($sql);
                $stmt->bind_param("sisi", $request['fondo'], $request['anno'], $request['template_name'], $version);
                $stmt->execute();
                $res = $stmt->get_result();
                $heredity = $res->fetch_all(MYSQLI_ASSOC);
            }
            for ($i = 0; $i < sizeof($template_data); $i++) {
                if ($template_data[$i]['heredity'] == '1') {
                    for ($j = 0; $j < sizeof($heredity); $j++) {
                        if ($template_data[$i]['id_articolo'] == $heredity[$j]['id_articolo']) {
                            $template_data[$i]['valore_anno_precedente'] = $heredity[$j]['valore'];
                        }
                    }
                } else if ($template_data[$i]['heredity'] == '2') {
                    for ($j = 0; $j < sizeof($heredity); $j++) {
                        if ($template_data[$i]['id_articolo'] == $heredity[$j]['id_articolo']) {
                            $template_data[$i]['valore_anno_precedente'] = $heredity[$j]['valore'];
                            $template_data[$i]['nota'] = $heredity[$j]['nota'];
                        }
                    }
                }
                $template_data[$i]['editable'] = 1;
            }

            if ($request['version'] != 0) {
                $version = $request['version'] - 1;
            } else {
                $version = $request['version'];
            }
            $stmt = $mysqli->prepare($sql);
            $stmt->bind_param("si", $request['template_name'], $version);

            $sql = "INSERT INTO DATE_storico_template_fondo 
                    (fondo,anno,descrizione_fondo,ordinamento,id_articolo,sezione,sottosezione,
                     nome_articolo,descrizione_articolo,sottotitolo_articolo,valore,valore_anno_precedente,nota,link,attivo,version,row_type,editable,heredity,template_name)
                        SELECT  fondo,anno,descrizione_fondo,ordinamento,id_articolo,sezione,sottosezione,
                     nome_articolo,descrizione_articolo,sottotitolo_articolo,valore,valore_anno_precedente,nota,link,attivo,version,row_type,editable,heredity,template_name 
FROM DATE_template_fondo WHERE template_name=? AND version=?";
            $res = $stmt->execute();
            $sql = "DELETE FROM DATE_template_fondo WHERE template_name=?";
            $stmt = $mysqli->prepare($sql);
            $stmt->bind_param("s", $template_name);
            $res = $stmt->execute();

            $sql = "INSERT INTO DATE_template_fondo 
                    (fondo,anno,descrizione_fondo,ordinamento,id_articolo,sezione,sottosezione,
                     nome_articolo,descrizione_articolo,sottotitolo_articolo,valore,valore_anno_precedente,nota,link,attivo,version,row_type,heredity,template_name) 
                     VALUES (?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?)";
            $stmt = $mysqli->prepare($sql);
            foreach ($template_data as $entry) {
                $stmt->bind_param("sisissssssiissiisis", $entry['fondo'], $entry['anno'], $entry['descrizione_fondo'], $entry['ordinamento'], $entry['id_articolo'],
                    $entry['sezione'], $entry['sottosezione'], $entry['nome_articolo'], $entry['descrizione_articolo'], $entry['sottotitolo_articolo'], $entry['valore'],
                    $entry['valore_anno_precedente'], $entry['nota'], $entry['link'], $entry['attivo'], $entry['version'], $entry['row_type'], $entry['heredity'], $entry['template_name']);
                $res = $stmt->execute();
            }
            $sql="UPDATE DATE_template_fondo set attivo=0 WHERE row_type='special'";
            $stmt = $mysqli->prepare($sql);
            $res = $stmt->execute();

            $sql = "INSERT INTO DATE_storico_formula 
                    (sezione,sottosezione,nome,descrizione,condizione,formula,text_type,formula_template_name,visibile,attivo)
                        SELECT sezione,sottosezione,nome,descrizione,condizione,formula,text_type,formula_template_name,visibile,attivo
FROM DATE_formula WHERE formula_template_name=?";
            $stmt = $mysqli->prepare($sql);
            $stmt->bind_param("s", $request['template_name']);
            $res = $stmt->execute();

            $sql = "INSERT INTO DATE_formula 
                    (sezione,sottosezione,nome,descrizione,condizione,formula,text_type,formula_template_name,visibile,attivo) 
                     VALUES (?,?,?,?,?,?,?,?,?,?)";
            $stmt = $mysqli->prepare($sql);
            foreach ($formulas as $entry) {
                $stmt->bind_param("ssssssisii", $entry['sezione'], $entry['sottosezione'], $entry['nome'],  $entry['descrizione'],$entry['condizione'], $entry['formula'],
                    $entry['text_type'], $entry['formula_template_name'], $entry['visibile'], $entry['attivo']);
                $res = $stmt->execute();
            }
            $sql = "DELETE FROM DATE_template_formula";
            $result = $mysqli->prepare($sql);
            $result->execute();
            $sql = "INSERT INTO DATE_template_formula 
                    (external_id,type,ordinamento) 
                     VALUES (?,?,?)";
            $stmt = $mysqli->prepare($sql);
            foreach ($template_formula as $entry) {
                $stmt->bind_param("iii", $entry['external_id'], $entry['type'], $entry['ordinamento']);
                $res = $stmt->execute();
            }


            if ($request['version'] != 0) {
                $version = $request['version'] - 1;
            } else {
                $version = $request['version'];
            }

            $sql = "INSERT INTO DATE_documenti_odt_storico
                    (chiave,valore,document_name,editor_name,anno,active,editable,page,version)
                        SELECT chiave,valore,document_name,editor_name,anno,active,editable,page,version
FROM DATE_documenti_odt WHERE editor_name=? AND version=?";
            $stmt = $mysqli->prepare($sql);
            $stmt->bind_param("si", $request['template_name'], $version);
            $res = $stmt->execute();

          $sql = "INSERT INTO DATE_documento_modello_fondo_storico
                    (ordinamento,sezione,sottosezione,nome_articolo,preventivo,document_name,editor_name,anno,attivo,editable,version)
                        SELECT ordinamento,sezione,sottosezione,nome_articolo,preventivo,document_name,editor_name,anno,attivo,editable,version
FROM DATE_documento_modello_fondo WHERE editor_name=? AND version=?";
            $stmt = $mysqli->prepare($sql);
            $stmt->bind_param("si", $request['template_name'], $version);
            $res = $stmt->execute();

            $sql = "INSERT INTO DATE_documento_modello_fondo_dati_utili_storico
                    (ordinamento,sezione,sottosezione,nome_articolo,formula,nota,document_name,editor_name,anno,attivo,editable,version)
                        SELECT ordinamento,sezione,sottosezione,nome_articolo,formula,nota,document_name,editor_name,anno,attivo,editable,version
FROM DATE_documento_modello_fondo_dati_utili WHERE editor_name=? AND version=?";
            $stmt = $mysqli->prepare($sql);
            $stmt->bind_param("si", $request['template_name'], $version);
            $res = $stmt->execute();

            $sql = "INSERT INTO DATE_documento_modello_fondo_utilizzo_storico
                    (ordinamento,sezione,nome_articolo,preventivo,consuntivo,attivo,document_name,editor_name,editable,anno,version)
                        SELECT ordinamento,sezione,nome_articolo,preventivo,consuntivo,attivo,document_name,editor_name,editable,anno,version
FROM DATE_documento_modello_fondo_utilizzo WHERE editor_name=? AND version=?";
            $stmt = $mysqli->prepare($sql);
            $stmt->bind_param("si", $request['template_name'], $version);
            $res = $stmt->execute();

            $sql = "INSERT INTO DATE_documento_regioni_autonomie_locali_storico
                    (ordinamento,document_name,titolo_tabella,sezione,sottosezione,nome_articolo,codice,importo,nota,attivo,editable,editor_name,anno,version)
                        SELECT ordinamento,document_name,titolo_tabella,sezione,sottosezione,nome_articolo,codice,importo,nota,attivo,editable,editor_name,anno,version
FROM DATE_documento_regioni_autonomie_locali WHERE editor_name=? AND version=?";
            $stmt = $mysqli->prepare($sql);
            $stmt->bind_param("si", $request['template_name'], $version);
            $res = $stmt->execute();

           $editable = 1;
           $sql = "INSERT INTO DATE_documenti_odt
                   (chiave,valore,document_name,editor_name,anno,active,editable,page,version)
                    VALUES (?,?,?,?,?,?,?,?,?)";
           $stmt = $mysqli->prepare($sql);
           foreach ($odt_documents as $entry) {
               $stmt->bind_param("sissiiisi", $entry['chiave'], $entry['valore'], $entry['document_name'], $entry['editor_name'],
                   $entry['anno'], $entry['active'], $editable, $entry['page'], $entry['page']);
               $res = $stmt->execute();
           }

            $sql = "INSERT INTO DATE_documento_modello_fondo
                    (ordinamento,sezione,sottosezione,nome_articolo,preventivo,document_name,editor_name,anno,attivo,editable,version)
                     VALUES (?,?,?,?,?,?,?,?,?,?,?)";
            $stmt = $mysqli->prepare($sql);
            foreach ($modello_fondo_document as $entry) {
                $stmt->bind_param("issssssiiii", $entry['ordinamento'], $entry['sezione'], $entry['sottosezione'], $entry['nome_articolo'],
                    $entry['preventivo'], $entry['document_name'], $entry['editor_name'], $entry['anno'], $entry['attivo'], $editable, $entry['version']);
                $res = $stmt->execute();
            }

            $sql = "INSERT INTO DATE_documento_modello_fondo_dati_utili
                    (ordinamento,sezione,sottosezione,nome_articolo,formula,nota,document_name,editor_name,anno,attivo,editable,version)
                     VALUES (?,?,?,?,?,?,?,?,?,?,?,?)";
            $stmt = $mysqli->prepare($sql);
            foreach ($modello_dati_utili_document as $entry) {
                $stmt->bind_param("isssssssiiii", $entry['ordinamento'], $entry['sezione'], $entry['sottosezione'], $entry['nome_articolo'],
                    $entry['formula'], $entry['nota'], $entry['document_name'], $entry['editor_name'], $entry['anno'], $entry['attivo'], $editable, $entry['version']);
                $res = $stmt->execute();
            }

            $sql = "INSERT INTO DATE_documento_modello_fondo_utilizzo
                    (ordinamento,sezione,nome_articolo,preventivo,consuntivo,attivo,document_name,editor_name,editable,anno,version)
                     VALUES (?,?,?,?,?,?,?,?,?,?,?)";
            $stmt = $mysqli->prepare($sql);
            foreach ($modello_utilizzo_document as $entry) {
                $stmt->bind_param("issssissiii", $entry['ordinamento'], $entry['sezione'], $entry['nome_articolo'],
                    $entry['preventivo'], $entry['consuntivo'], $entry['attivo'], $entry['document_name'], $entry['editor_name'], $editable, $entry['anno'], $entry['version']);
                $res = $stmt->execute();
            }

            $sql = "INSERT INTO DATE_documento_regioni_autonomie_locali
                    (ordinamento,document_name,titolo_tabella,sezione,sottosezione,nome_articolo,codice,importo,nota,attivo,editable,editor_name,anno,version)
                     VALUES (?,?,?,?,?,?,?,?,?,?,?,?,?,?)";
            $stmt = $mysqli->prepare($sql);
            foreach ($modello_regioni_document as $entry) {
                $stmt->bind_param("issssssssiisii", $entry['ordinamento'], $entry['document_name'], $entry['titolo_tabella'],
                    $entry['sezione'], $entry['sottosezione'], $entry['nome_articolo'], $entry['codice'], $entry['importo'], $entry['nota'], $entry['attivo'], $editable, $entry['editor_name'], $entry['anno'], $entry['version']);
                $res = $stmt->execute();
            }

            mysqli_close($mysqli);
        }
        return $formulas;
    }

    //un giorno capirò perchè se inserisco questa funzione al posto delle query non funziona e la utilizzerò
//    public function exportDataFondoCompleto($request){
//        $conn = new Connection();
//        $mysqli = $conn->connect();
//        $sql = "SELECT * FROM DATE_formula WHERE formula_template_name=?";
//        $stmt = $mysqli->prepare($sql);
//        $stmt->bind_param("s",  $request['template_name']);
//        $res = $stmt->execute();
//        $res = $stmt->get_result();
//        $formulas = $res->fetch_all(MYSQLI_ASSOC);
//        $sql = "SELECT * FROM DATE_template_formula";
//        $result = $mysqli->query($sql);
//        $template_formula = $result->fetch_all(MYSQLI_ASSOC);
//        mysqli_close($mysqli);
//        $url = DB_HOST . ":" . DB_PORT . "/";
//        $username = DB_USER;
//        $password = DB_PASSWORD;
//        $dbname = 'c1date_slave';
//        $mysqli = new mysqli($url, $username, $password, $dbname);
//
//        $sql = "INSERT INTO DATE_storico_formula
//                    (sezione,sottosezione,nome,descrizione,condizione,formula,text_type,formula_template_name,visibile,attivo)
//                        SELECT sezione,sottosezione,nome,descrizione,condizione,formula,text_type,formula_template_name,visibile,attivo
//FROM DATE_formula WHERE formula_template_name=?";
//        $stmt = $mysqli->prepare($sql);
//        $stmt->bind_param("s", $request['template_name']);
//        $res = $stmt->execute();
//
//        $sql = "INSERT INTO DATE_formula
//                    (sezione,sottosezione,nome,descrizione,condizione,formula,text_type,formula_template_name,visibile,attivo)
//                     VALUES (?,?,?,?,?,?,?,?,?,?)";
//        $stmt = $mysqli->prepare($sql);
//        foreach ($formulas as $entry) {
//            $stmt->bind_param("ssssssisii", $entry['sezione'], $entry['sottosezione'], $entry['nome'], $entry['condizione'], $entry['formula'],
//                $entry['text_type'], $entry['formula_template_name'], $entry['visibile'], $entry['attivo']);
//            $res = $stmt->execute();
//        }
//        $sql = "DELETE FROM DATE_template_formula";
//        $result = $mysqli->prepare($sql);
//        $result->execute();
//        $sql = "INSERT INTO DATE_template_formula
//                    (external_id,type,ordinamento)
//                     VALUES (?,?,?)";
//        $stmt = $mysqli->prepare($sql);
//        foreach ($template_formula as $entry) {
//            $stmt->bind_param("iii", $entry['external_id'], $entry['type'], $entry['ordinamento']);
//            $res = $stmt->execute();
//        }
//        mysqli_close($mysqli);
//    }
//    public function exportDataDocument($request){
//        $conn = new Connection();
//        $mysqli = $conn->connect();
//        $sql = "SELECT * FROM DATE_documenti_odt_storico WHERE editor_name=? AND version=?";
//        $stmt = $mysqli->prepare($sql);
//        $stmt->bind_param("si",  $request['template_name'], $request['version']);
//        $res = $stmt->execute();
//        $res = $stmt->get_result();
//        $odt_documents = $res->fetch_all(MYSQLI_ASSOC);
//
//        $sql = "SELECT * FROM DATE_documento_modello_fondo_storico WHERE editor_name=? AND version=?";
//        $stmt = $mysqli->prepare($sql);
//        $stmt->bind_param("si",  $request['template_name'], $request['version']);
//        $res = $stmt->execute();
//        $res = $stmt->get_result();
//        $modello_fondo_document = $res->fetch_all(MYSQLI_ASSOC);
//
//        $sql = "SELECT * FROM DATE_documento_modello_fondo_dati_utili_storico WHERE editor_name=? AND version=?";
//        $stmt = $mysqli->prepare($sql);
//        $stmt->bind_param("si",  $request['template_name'], $request['version']);
//        $res = $stmt->execute();
//        $res = $stmt->get_result();
//        $modello_dati_utili_document = $res->fetch_all(MYSQLI_ASSOC);
//
//        $sql = "SELECT * FROM DATE_documento_modello_fondo_utilizzo_storico WHERE editor_name=? AND version=?";
//        $stmt = $mysqli->prepare($sql);
//        $stmt->bind_param("si",  $request['template_name'], $request['version']);
//        $res = $stmt->execute();
//        $res = $stmt->get_result();
//        $modello_utilizzo_document = $res->fetch_all(MYSQLI_ASSOC);
//
//
//        $sql = "SELECT * FROM DATE_documento_regioni_autonomie_locali_storico WHERE editor_name=? AND version=?";
//        $stmt = $mysqli->prepare($sql);
//        $stmt->bind_param("si",  $request['template_name'], $request['version']);
//        $res = $stmt->execute();
//        $res = $stmt->get_result();
//        $modello_regioni_document = $res->fetch_all(MYSQLI_ASSOC);
//
//        mysqli_close($mysqli);
//        $url = DB_HOST . ":" . DB_PORT . "/";
//        $username = DB_USER;
//        $password = DB_PASSWORD;
//        $dbname = 'c1date_slave';
//        $mysqli = new mysqli($url, $username, $password, $dbname);
//
//        if($request['version']!= 0){
//            $version = $request['version'] - 1;
//        }
//        else{
//            $version = $request['version'];
//        }
//
//        $sql = "INSERT INTO DATE_documenti_odt_storico
//                    (chiave,valore,document_name,editor_name,anno,active,editable,page,version)
//                        SELECT chiave,valore,document_name,editor_name,anno,active,editable,page,version
//FROM DATE_documenti_odt WHERE editor_name=? AND version=?";
//        $stmt = $mysqli->prepare($sql);
//        $stmt->bind_param("si", $request['template_name'],$version);
//        $res = $stmt->execute();
//
//
//        $sql = "INSERT INTO DATE_documento_modello_fondo_storico
//                    (ordinamento,sezione,sottosezione,nome_articolo,preventivo,document_name,editor_name,anno,attivo,editable,version)
//                        SELECT ordinamento,sezione,sottosezione,nome_articolo,preventivo,document_name,editor_name,anno,attivo,editable,version
//FROM DATE_documento_modello_fondo WHERE editor_name=? AND version=?";
//        $stmt = $mysqli->prepare($sql);
//        $stmt->bind_param("si", $request['template_name'],$version);
//        $res = $stmt->execute();
//
//        $sql = "INSERT INTO DATE_documento_modello_fondo_dati_utili_storico
//                    (ordinamento,sezione,sottosezione,nome_articolo,formula,nota,document_name,editor_name,anno,attivo,editable,version)
//                        SELECT ordinamento,sezione,sottosezione,nome_articolo,formula,nota,document_name,editor_name,anno,attivo,editable,version
//FROM DATE_documento_modello_fondo_dati_utili WHERE editor_name=? AND version=?";
//        $stmt = $mysqli->prepare($sql);
//        $stmt->bind_param("si", $request['template_name'],$version);
//        $res = $stmt->execute();
//
//        $sql = "INSERT INTO DATE_documento_modello_fondo_utilizzo_storico
//                    (ordinamento,sezione,nome_articolo,preventivo,consuntivo,attivo,document_name,editor_name,editable,anno,version)
//                        SELECT ordinamento,sezione,nome_articolo,preventivo,consuntivo,attivo,document_name,editor_name,editable,anno,version
//FROM DATE_documento_modello_fondo_utilizzo WHERE editor_name=? AND version=?";
//        $stmt = $mysqli->prepare($sql);
//        $stmt->bind_param("si", $request['template_name'],$version);
//        $res = $stmt->execute();
//
//        $sql = "INSERT INTO DATE_documento_regioni_autonomie_locali_storico
//                    (ordinamento,document_name,titolo_tabella,sezione,sottosezione,nome_articolo,codice,importo,nota,attivo,editable,editor_name,anno,version)
//                        SELECT ordinamento,document_name,titolo_tabella,sezione,sottosezione,nome_articolo,codice,importo,nota,attivo,editable,editor_name,anno,version
//FROM DATE_documento_regioni_autonomie_locali WHERE editor_name=? AND version=?";
//        $stmt = $mysqli->prepare($sql);
//        $stmt->bind_param("si", $request['template_name'],$version);
//        $res = $stmt->execute();
//
//        $editable = 1;
//        $sql = "INSERT INTO DATE_documenti_odt
//                    (chiave,valore,document_name,editor_name,anno,active,editable,page,version)
//                     VALUES (?,?,?,?,?,?,?,?,?)";
//        $stmt = $mysqli->prepare($sql);
//        foreach ($odt_documents as $entry) {
//            $stmt->bind_param("sissiiisi", $entry['chiave'], $entry['valore'], $entry['document_name'], $entry['editor_name'],
//                $entry['anno'],$entry['active'],$editable,$entry['page'],$entry['page']);
//            $res = $stmt->execute();
//        }
//
//        $sql = "INSERT INTO DATE_documento_modello_fondo
//                    (ordinamento,sezione,sottosezione,nome_articolo,preventivo,document_name,editor_name,anno,attivo,editable,version)
//                     VALUES (?,?,?,?,?,?,?,?,?,?,?)";
//        $stmt = $mysqli->prepare($sql);
//        foreach ($modello_fondo_document as $entry) {
//            $stmt->bind_param("issssssiiii", $entry['ordinamento'], $entry['sezione'], $entry['sottosezione'], $entry['nome_articolo'],
//                $entry['preventivo'],$entry['document_name'],$entry['editor_name'],$entry['anno'],$entry['attivo'],$editable,$entry['version']);
//            $res = $stmt->execute();
//        }
//
//        $sql = "INSERT INTO DATE_documento_modello_fondo_dati_utili
//                    (ordinamento,sezione,sottosezione,nome_articolo,formula,nota,document_name,editor_name,anno,attivo,editable,version)
//                     VALUES (?,?,?,?,?,?,?,?,?,?,?,?)";
//        $stmt = $mysqli->prepare($sql);
//        foreach ($modello_dati_utili_document as $entry) {
//            $stmt->bind_param("isssssssiiii", $entry['ordinamento'], $entry['sezione'], $entry['sottosezione'], $entry['nome_articolo'],
//                $entry['formula'],$entry['nota'],$entry['document_name'],$entry['editor_name'],$entry['anno'],$entry['attivo'],$editable,$entry['version']);
//            $res = $stmt->execute();
//        }
//
//        $sql = "INSERT INTO DATE_documento_modello_fondo_utilizzo
//                    (ordinamento,sezione,nome_articolo,preventivo,consuntivo,attivo,document_name,editor_name,editable,anno,version)
//                     VALUES (?,?,?,?,?,?,?,?,?,?,?)";
//        $stmt = $mysqli->prepare($sql);
//        foreach ($modello_utilizzo_document as $entry) {
//            $stmt->bind_param("issssissiii", $entry['ordinamento'], $entry['sezione'], $entry['nome_articolo'],
//                $entry['preventivo'],$entry['consuntivo'],$entry['attivo'],$entry['document_name'],$entry['editor_name'],$editable,$entry['anno'],$entry['version']);
//            $res = $stmt->execute();
//        }
//
//        $sql = "INSERT INTO DATE_documento_regioni_autonomie_locali
//                    (ordinamento,document_name,titolo_tabella,sezione,sottosezione,nome_articolo,codice,importo,nota,attivo,editable,editor_name,anno,version)
//                     VALUES (?,?,?,?,?,?,?,?,?,?,?,?,?,?)";
//        $stmt = $mysqli->prepare($sql);
//        foreach ($modello_regioni_document as $entry) {
//            $stmt->bind_param("issssssssiisii", $entry['ordinamento'], $entry['document_name'], $entry['titolo_tabella'],
//                $entry['sezione'],$entry['sottosezione'],$entry['nome_articolo'],$entry['codice'],$entry['importo'],$entry['nota'],$entry['attivo'],$editable,$entry['editor_name'],$entry['anno'],$entry['version']);
//            $res = $stmt->execute();
//        }
//        mysqli_close($mysqli);
//    }
}