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
        //per gli n comuni, fare un ciclo con l'array passato nel payload per ogni db
        $conn = new Connection();
        $mysqli = $conn->connect();
        $sql = "SELECT * FROM DATE_storico_template_fondo WHERE fondo=? AND anno=? AND template_name=? AND version=?";
        $stmt = $mysqli->prepare($sql);
        $stmt->bind_param("sisi", $request['fondo'], $request['anno'], $request['template_name'], $request['version']);
        $res = $stmt->execute();
        $res = $stmt->get_result();
        $template_data = $res->fetch_all(MYSQLI_ASSOC);
        mysqli_close($mysqli);
        //questo è per il test di prova, fare questo procedimento n volte aggiornando il db name con i nomi dei comuni selezionati
        $url = DB_HOST . ":" . DB_PORT . "/";
        $username = DB_USER;
        $password = DB_PASSWORD;
        $dbname = 'c1date_slave';
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
        $sql = "INSERT INTO DATE_storico_template_fondo 
                    (fondo,anno,descrizione_fondo,ordinamento,id_articolo,sezione,sottosezione,
                     nome_articolo,descrizione_articolo,sottotitolo_articolo,valore,valore_anno_precedente,nota,link,attivo,version,row_type,editable,heredity,template_name)
                        SELECT  fondo,anno,descrizione_fondo,ordinamento,id_articolo,sezione,sottosezione,
                     nome_articolo,descrizione_articolo,sottotitolo_articolo,valore,valore_anno_precedente,nota,link,attivo,version,row_type,editable,heredity,template_name 
FROM DATE_template_fondo WHERE template_name=? AND version=?";
        $stmt = $mysqli->prepare($sql);
        $stmt->bind_param("si", $request['template_name'], $request['version']);
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
        mysqli_close($mysqli);
        $this->exportDataFondoCompleto($request);
        return $template_data;
    }
    public function exportDataFondoCompleto($request){
        $conn = new Connection();
        $mysqli = $conn->connect();
        $sql = "SELECT * FROM DATE_formula WHERE formula_template_name=?";
        $stmt = $mysqli->prepare($sql);
        $stmt->bind_param("s",  $request['template_name']);
        $res = $stmt->execute();
        $res = $stmt->get_result();
        $formulas = $res->fetch_all(MYSQLI_ASSOC);
        $sql = "SELECT * FROM DATE_template_formula WHERE id_articolo IS NOT NULL AND attivo=1";
        $result = $mysqli->query($sql);
        $template_formula = $result->fetch_all(MYSQLI_ASSOC);
        mysqli_close($mysqli);
        $url = DB_HOST . ":" . DB_PORT . "/";
        $username = DB_USER;
        $password = DB_PASSWORD;
        $dbname = 'c1date_slave';
        $mysqli = new mysqli($url, $username, $password, $dbname);
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
            $stmt->bind_param("ssssssisii", $entry['sezione'], $entry['sottosezione'], $entry['nome'], $entry['condizione'], $entry['formula'],
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
        mysqli_close($mysqli);
    }
}