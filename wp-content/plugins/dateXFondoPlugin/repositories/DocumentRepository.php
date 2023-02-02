<?php

use dateXFondoPlugin\Connection;

class DocumentRepository
{
    public static function getDataDocument($table_name)
    {
        $conn = new Connection();
        $mysqli = $conn->connect();
        $sql = 'SELECT DISTINCT document_name, editor_name, anno, version FROM ' . $table_name;
        $stmt = $mysqli->prepare($sql);
        $res = $stmt->execute();
        if ($res = $stmt->get_result()) {
            $rows = $res->fetch_all(MYSQLI_ASSOC);
        } else
            $rows = [];
        mysqli_close($mysqli);
        return $rows;

    }

    public static function getDataOdtDocument($table_name)
    {
        $conn = new Connection();
        $mysqli = $conn->connect();
        $sql = 'SELECT DISTINCT document_name, editor_name, anno,page,version FROM ' . $table_name;
        $stmt = $mysqli->prepare($sql);
        $res = $stmt->execute();
        if ($res = $stmt->get_result()) {
            $rows = $res->fetch_all(MYSQLI_ASSOC);
        } else
            $rows = [];
        mysqli_close($mysqli);
        return $rows;

    }

    public static function getArticoli($editor_name)
    {
        $conn = new Connection();
        $mysqli = $conn->connect();
        $sql = "SELECT * FROM DATE_documento_modello_fondo WHERE  attivo=1 and editor_name=? ORDER BY ordinamento ASC";
        $stmt = $mysqli->prepare($sql);
        $stmt->bind_param("s", $editor_name);
        $res = $stmt->execute();
        if ($res = $stmt->get_result()) {
            $rows = $res->fetch_all(MYSQLI_ASSOC);
        } else
            $rows = [];
        mysqli_close($mysqli);
        return $rows;
    }

    public static function getHistoryArticoli($editor_name, $version)
    {
        $conn = new Connection();
        $mysqli = $conn->connect();
        $sql = "SELECT * FROM DATE_documento_modello_fondo_storico WHERE attivo=1 and editor_name=? AND version=? ORDER BY ordinamento ASC";
        $stmt = $mysqli->prepare($sql);
        $stmt->bind_param("si", $editor_name, $version);
        $res = $stmt->execute();
        if ($res = $stmt->get_result()) {
            $rows = $res->fetch_all(MYSQLI_ASSOC);
        } else
            $rows = [];
        mysqli_close($mysqli);
        return $rows;
    }

    public static function getArticoliUtilizzo($editor_name)
    {
        $conn = new Connection();
        $mysqli = $conn->connect();
        $sql = "SELECT * FROM DATE_documento_modello_fondo_utilizzo WHERE  attivo=1 and editor_name=? ORDER BY ordinamento ASC";
        $stmt = $mysqli->prepare($sql);
        $stmt->bind_param("s", $editor_name);
        $res = $stmt->execute();
        if ($res = $stmt->get_result()) {
            $rows = $res->fetch_all(MYSQLI_ASSOC);
        } else
            $rows = [];
        mysqli_close($mysqli);
        return $rows;
    }

    public static function getHistoryArticoliUtilizzo($editor_name, $version)
    {
        $conn = new Connection();
        $mysqli = $conn->connect();
        $sql = "SELECT * FROM DATE_documento_modello_fondo_utilizzo_storico WHERE  attivo=1 and editor_name=? AND version=? ORDER BY ordinamento ASC";
        $stmt = $mysqli->prepare($sql);
        $stmt->bind_param("si", $editor_name, $version);
        $res = $stmt->execute();
        if ($res = $stmt->get_result()) {
            $rows = $res->fetch_all(MYSQLI_ASSOC);
        } else
            $rows = [];
        mysqli_close($mysqli);
        return $rows;
    }

    public static function getArticoliDatiUtili($editor_name)
    {
        $conn = new Connection();
        $mysqli = $conn->connect();
        $sql = "SELECT * FROM DATE_documento_modello_fondo_dati_utili WHERE  attivo=1 and editor_name=? ORDER BY ordinamento ASC";
        $stmt = $mysqli->prepare($sql);
        $stmt->bind_param("s", $editor_name);
        $res = $stmt->execute();
        if ($res = $stmt->get_result()) {
            $rows = $res->fetch_all(MYSQLI_ASSOC);
        } else
            $rows = [];
        mysqli_close($mysqli);
        return $rows;
    }

    public static function getHistoryArticoliDatiUtili($editor_name, $version)
    {
        $conn = new Connection();
        $mysqli = $conn->connect();
        $sql = "SELECT * FROM DATE_documento_modello_fondo_dati_utili_storico WHERE  attivo=1 and editor_name=? AND version=? ORDER BY ordinamento ASC";
        $stmt = $mysqli->prepare($sql);
        $stmt->bind_param("si", $editor_name, $version);
        $res = $stmt->execute();
        if ($res = $stmt->get_result()) {
            $rows = $res->fetch_all(MYSQLI_ASSOC);
        } else
            $rows = [];
        mysqli_close($mysqli);
        return $rows;
    }

    public static function getIdsArticoli($editor_name)
    {
        $conn = new Connection();
        $mysqli = $conn->connect();
        $sql = "SELECT id_articolo FROM DATE_template_fondo WHERE id_articolo IS NOT NULL and attivo=1 and template_name=?";
        $stmt = $mysqli->prepare($sql);
        $stmt->bind_param("s", $editor_name);
        $res = $stmt->execute();
        if ($res = $stmt->get_result()) {
            $rows = $res->fetch_all();
        } else
            $rows = [];
        mysqli_close($mysqli);
        return $rows;
    }

    public static function getSezioni($editor_name, $version)
    {
        $conn = new Connection();
        $mysqli = $conn->connect();
        if (isset($version)) {
            $sql = "SELECT DISTINCT sezione FROM DATE_documento_modello_fondo_storico WHERE editor_name=? AND version=?";
            $stmt = $mysqli->prepare($sql);
            $stmt->bind_param("si", $editor_name, $version);
            $res = $stmt->execute();
        } else {
            $sql = "SELECT DISTINCT sezione FROM DATE_documento_modello_fondo WHERE editor_name=?";
            $stmt = $mysqli->prepare($sql);
            $stmt->bind_param("s", $editor_name);
            $res = $stmt->execute();
        }

        $res = $stmt->get_result();
        $rows = $res->fetch_all(MYSQLI_ASSOC);
        mysqli_close($mysqli);
        return $rows;
    }

    public static function getSezioniUtilizzo($template_name, $version)
    {
        $conn = new Connection();
        $mysqli = $conn->connect();
        if (isset($version)) {
            $sql = "SELECT DISTINCT sezione FROM DATE_documento_modello_fondo_utilizzo_storico WHERE editor_name=? AND version=?";
            $stmt = $mysqli->prepare($sql);
            $stmt->bind_param("si", $template_name, $version);
            $res = $stmt->execute();
        } else {
            $sql = "SELECT DISTINCT sezione FROM DATE_documento_modello_fondo_utilizzo WHERE editor_name=?";
            $stmt = $mysqli->prepare($sql);
            $stmt->bind_param("s", $template_name);
            $res = $stmt->execute();
        }

        $res = $stmt->get_result();
        $rows = $res->fetch_all(MYSQLI_ASSOC);
        mysqli_close($mysqli);
        return $rows;
    }

    public static function getSezioniDatiUtili($template_name, $version)
    {
        $conn = new Connection();
        $mysqli = $conn->connect();
        if (isset($version)) {
            $sql = "SELECT DISTINCT sezione FROM DATE_documento_modello_fondo_dati_utili_storico WHERE editor_name=? AND version=?";
            $stmt = $mysqli->prepare($sql);
            $stmt->bind_param("si", $template_name, $version);
            $res = $stmt->execute();
        } else {
            $sql = "SELECT DISTINCT sezione FROM DATE_documento_modello_fondo_dati_utili WHERE editor_name=?";
            $stmt = $mysqli->prepare($sql);
            $stmt->bind_param("s", $template_name);
            $res = $stmt->execute();
        }

        $res = $stmt->get_result();
        $rows = $res->fetch_all(MYSQLI_ASSOC);
        mysqli_close($mysqli);
        return $rows;
    }

    public static function getFormulas($document_name)
    {
        $conn = new Connection();
        $mysqli = $conn->connect();
        $sql = "SELECT nome FROM DATE_formula WHERE formula_template_name=?";
        $stmt = $mysqli->prepare($sql);
        $stmt->bind_param("s", $document_name);
        $res = $stmt->execute();
        $res = $stmt->get_result();
        $rows = $res->fetch_all();
        mysqli_close($mysqli);
        return $rows;
    }

    public static function edit_document_row($request)
    {

        $conn = new Connection();
        $mysqli = $conn->connect();

        $sql = "UPDATE DATE_documento_modello_fondo SET 
                               nome_articolo=?,
                               ordinamento=?,
                               preventivo=?
                               
WHERE id=?";
        $stmt = $mysqli->prepare($sql);
        $stmt->bind_param("sisi",
            $request['nome_articolo'],
            $request['ordinamento'],
            $request['preventivo'],
            $request['id']);
        $res = $stmt->execute();
        $mysqli->close();
        return $res;

    }

    public static function edit_utilizzo_document_row($request)
    {

        $conn = new Connection();
        $mysqli = $conn->connect();

        $sql = "UPDATE DATE_documento_modello_fondo_utilizzo SET 
                               nome_articolo=?,
                               ordinamento=?,
                               preventivo=?,
                               consuntivo=?
                               
WHERE id=?";
        $stmt = $mysqli->prepare($sql);
        $stmt->bind_param("sissi",
            $request['nome_articolo'],
            $request['ordinamento'],
            $request['preventivo'],
            $request['consuntivo'],
            $request['id_utilizzo']);
        $res = $stmt->execute();
        $mysqli->close();
        return $res;

    }

    public static function edit_dati_utili_document_row($request)
    {

        $conn = new Connection();
        $mysqli = $conn->connect();

        $sql = "UPDATE DATE_documento_modello_fondo_dati_utili SET 
                               nome_articolo=?,
                               ordinamento=?,
                               formula=?,
                               nota=?
                               
WHERE id=?";
        $stmt = $mysqli->prepare($sql);
        $stmt->bind_param("sissi",
            $request['nome_articolo'],
            $request['ordinamento'],
            $request['formula'],
            $request['nota'],
            $request['id_dati_utili']);
        $res = $stmt->execute();
        $mysqli->close();
        return $res;

    }

    public static function delete_document_row($request)
    {
        $conn = new Connection();
        $mysqli = $conn->connect();
        $sql = "UPDATE DATE_documento_modello_fondo SET attivo=0  WHERE id=?";
        $stmt = $mysqli->prepare($sql);
        $stmt->bind_param("i", $request['id']);
        $res = $stmt->execute();
        $mysqli->close();
    }

    public static function delete_utilizzo_row($request)
    {
        $conn = new Connection();
        $mysqli = $conn->connect();
        $sql = "UPDATE DATE_documento_modello_fondo_utilizzo SET attivo=0  WHERE id=?";
        $stmt = $mysqli->prepare($sql);
        $stmt->bind_param("i", $request['id_utilizzo']);
        $res = $stmt->execute();
        $mysqli->close();
    }

    public static function delete_dati_utili_row($request)
    {
        $conn = new Connection();
        $mysqli = $conn->connect();
        $sql = "UPDATE DATE_documento_modello_fondo_dati_utili SET attivo=0  WHERE id=?";
        $stmt = $mysqli->prepare($sql);
        $stmt->bind_param("i", $request['id_dati_utili']);
        $res = $stmt->execute();
        $mysqli->close();
    }

    public static function set_modello_document_not_editable($request)
    {
        $conn = new Connection();
        $mysqli = $conn->connect();
        $sql = "UPDATE DATE_documento_modello_fondo SET editable=0";
        $stmt = $mysqli->prepare($sql);
        $res = $stmt->execute();
        $sql = "INSERT INTO DATE_documento_modello_fondo_storico 
                    (ordinamento,sezione,sottosezione,nome_articolo,preventivo,document_name,editor_name,anno,
                     attivo,editable,version)
                        SELECT  ordinamento,sezione,sottosezione,nome_articolo,preventivo,document_name,editor_name,anno,
                     attivo,editable,version
FROM DATE_documento_modello_fondo WHERE editor_name=?";
        $stmt = $mysqli->prepare($sql);
        $stmt->bind_param("s", $request['editor_name']);
        $res = $stmt->execute();
        $sql = "DELETE FROM DATE_documento_modello_fondo WHERE editor_name=?";
        $stmt = $mysqli->prepare($sql);
        $stmt->bind_param("s", $request['editor_name']);
        $res = $stmt->execute();

        //per tabella documento fondo utilizzo
        $sql = "UPDATE DATE_documento_modello_fondo_utilizzo SET editable=0";
        $stmt = $mysqli->prepare($sql);
        $res = $stmt->execute();
        $sql = "INSERT INTO DATE_documento_modello_fondo_utilizzo_storico 
                    (ordinamento,sezione,nome_articolo,preventivo,consuntivo,attivo,document_name,editor_name,editable,anno,
                     version)
                        SELECT  ordinamento,sezione,nome_articolo,preventivo,consuntivo,attivo,document_name,editor_name,editable,anno,
                     version
FROM DATE_documento_modello_fondo_utilizzo WHERE editor_name=?";
        $stmt = $mysqli->prepare($sql);
        $stmt->bind_param("s", $request['editor_name']);
        $res = $stmt->execute();
        $sql = "DELETE FROM DATE_documento_modello_fondo_utilizzo WHERE editor_name=?";
        $stmt = $mysqli->prepare($sql);
        $stmt->bind_param("s", $request['editor_name']);
        $res = $stmt->execute();
        // per tabella documento dati utili
        $sql = "UPDATE DATE_documento_modello_fondo_dati_utili SET editable=0";
        $stmt = $mysqli->prepare($sql);
        $res = $stmt->execute();
        $sql = "INSERT INTO DATE_documento_modello_fondo_dati_utili_storico 
                    (ordinamento,sezione,sottosezione,nome_articolo,formula,nota,document_name,editor_name,anno,
                     attivo,editable,version)
                        SELECT  ordinamento,sezione,sottosezione,nome_articolo,formula,nota,document_name,editor_name,anno,
                     attivo,editable,version
FROM DATE_documento_modello_fondo_dati_utili WHERE editor_name=?";
        $stmt = $mysqli->prepare($sql);
        $stmt->bind_param("s", $request['editor_name']);
        $res = $stmt->execute();
        $sql = "DELETE FROM DATE_documento_modello_fondo_dati_utili WHERE editor_name=?";
        $stmt = $mysqli->prepare($sql);
        $stmt->bind_param("s", $request['editor_name']);
        $res = $stmt->execute();
        mysqli_close($mysqli);
        return $res;
    }

    public static function edit_modello_document_header($request)
    {
        $conn = new Connection();
        $mysqli = $conn->connect();
        $sql = "UPDATE DATE_documento_modello_fondo SET document_name=?, anno=? WHERE document_name=?";
        $stmt = $mysqli->prepare($sql);
        $stmt->bind_param("sis", $request['document_name'], $request['anno'], $request['old_document_name']);
        $stmt->execute();
        $sql = "UPDATE DATE_documento_modello_fondo_utilizzo SET document_name=?, anno=? WHERE document_name=?";
        $stmt = $mysqli->prepare($sql);
        $stmt->bind_param("sis", $request['document_name'], $request['anno'], $request['old_document_name']);
        $stmt->execute();
        $sql = "UPDATE DATE_documento_modello_fondo_dati_utili SET document_name=?, anno=? WHERE document_name=?";
        $stmt = $mysqli->prepare($sql);
        $stmt->bind_param("sis", $request['document_name'], $request['anno'], $request['old_document_name']);
        $stmt->execute();
        $mysqli->close();
    }

    public static function create_new_row_costituzione($request)
    {
        $conn = new Connection();
        $mysqli = $conn->connect();
        $sql = "INSERT INTO DATE_documento_modello_fondo 
                    (ordinamento,sezione,sottosezione,nome_articolo,preventivo,document_name,anno) VALUES(?,?,?,?,?,?,?)";
        $stmt = $mysqli->prepare($sql);
        $stmt->bind_param("isssssi", $request['ordinamento'], $request['sezione'], $request['sottosezione'], $request['nome_articolo'],
            $request['preventivo'], $request['document_name'], $request['anno']);
        $stmt->execute();
        $mysqli->close();

    }

    public static function create_new_row_utilizzo($request)
    {
        $conn = new Connection();
        $mysqli = $conn->connect();
        $sql = "INSERT INTO DATE_documento_modello_fondo_utilizzo 
                    (ordinamento,sezione,nome_articolo,preventivo,consuntivo,document_name,anno) VALUES(?,?,?,?,?,?,?)";
        $stmt = $mysqli->prepare($sql);
        $stmt->bind_param("isssssi", $request['ordinamento'], $request['sezione'], $request['nome_articolo'],
            $request['preventivo'], $request['consuntivo'], $request['document_name'], $request['anno']);
        $stmt->execute();
        $mysqli->close();

    }

    public static function create_new_row_dati_utili($request)
    {
        $conn = new Connection();
        $mysqli = $conn->connect();
        $sql = "INSERT INTO DATE_documento_modello_fondo_dati_utili
                    (ordinamento,sezione,sottosezione,nome_articolo,formula,nota,document_name,anno) VALUES(?,?,?,?,?,?,?,?)";
        $stmt = $mysqli->prepare($sql);
        $stmt->bind_param("issssssi", $request['ordinamento'], $request['sezione'], $request['sottosezione'], $request['nome_articolo'],
            $request['formula'], $request['nota'], $request['document_name'], $request['anno']);
        $stmt->execute();
        $mysqli->close();

    }

    public static function duplicate_document($request)
    {
        $conn = new Connection();
        $mysqli = $conn->connect();
        if ($request['document_name'] == 'Tabella 15') {
            $sql = 'SELECT  ordinamento,
                        document_name,
                        titolo_tabella,
                        sezione,
                        sottosezione, 
                        nome_articolo, 
                        codice, importo, 
                        nota,
                        attivo,
                        editable,
                        editor_name,
                        anno,
                        version FROM DATE_documento_regioni_autonomie_locali_storico
                            WHERE document_name=? AND editor_name=? AND anno=? AND version=?';
            $stmt = $mysqli->prepare($sql);
            $stmt->bind_param("ssii", $request['document_name'], $request['editor_name'], $request['anno'], $request['version']);
            $res = $stmt->execute();
            if ($res = $stmt->get_result()) {
                $rows = $res->fetch_all(MYSQLI_ASSOC);
            } else
                $rows = [];
            $sql = 'INSERT INTO DATE_documento_regioni_autonomie_locali  (ordinamento,
                        document_name,
                        titolo_tabella,
                        sezione,
                        sottosezione, 
                        nome_articolo, 
                        codice, importo, 
                        nota,
                        attivo,
                        editable,
                        editor_name,
                        anno,
                        version) VALUES (?,?,?,?,?,?,?,?,?,?,?,?,?,?) ';
            $stmt = $mysqli->prepare($sql);
            $version = $rows[0]['version'] + 1;
            foreach ($rows as $entry) {
                $stmt->bind_param("issssssssiisii",
                    $entry['ordinamento'],
                    $entry['document_name'],
                    $entry['titolo_tabella'],
                    $entry['sezione'],
                    $entry['sottosezione'],
                    $entry['nome_articolo'],
                    $entry['codice'],
                    $entry['importo'],
                    $entry['nota'],
                    $entry['attivo'],
                    $entry['editable'],
                    $entry['editor_name'],
                    $entry['anno'],
                    $version);
                $res = $stmt->execute();
            }

        } else if ($request['document_name'] == 'Modello fondo') {

            $sql = " SELECT  ordinamento,sezione,sottosezione,nome_articolo,preventivo,document_name,anno,
                     attivo,editable
FROM DATE_documento_modello_fondo_storico WHERE document_name=? AND editor_name=? AND anno=? AND version=?";
            $stmt = $mysqli->prepare($sql);
            $stmt->bind_param("ssii", $request['document_name'], $request['editor_name'], $request['anno'], $request['version']);
            $res = $stmt->execute();
            if ($res = $stmt->get_result()) {
                $rows = $res->fetch_all(MYSQLI_ASSOC);
            } else
                $rows = [];
            $sql = "INSERT INTO DATE_documento_modello_fondo
            (ordinamento,sezione,sottosezione,nome_articolo,preventivo,document_name,editor_name,anno,
                attivo,editable,version) VALUES (?,?,?,?,?,?,?,?,?)";
            $stmt = $mysqli->prepare($sql);
            $version = $rows[0]['version'] + 1;
            foreach ($rows as $entry) {
                $stmt->bind_param("issssssiiii", $entry['ordinamento'], $entry['sezione'], $entry['sottosezione'], $entry['nome_articolo'], $entry['preventivo'],
                    $entry['document_name'], $entry['editor_name'], $entry['anno'], $entry['attivo'], $entry['editable'], $version);
                $res = $stmt->execute();
            }

            $sql = "  SELECT  ordinamento,sezione,nome_articolo,preventivo,consuntivo,attivo,document_name,editor_name,editable,anno,
                     version
FROM DATE_documento_modello_fondo_utilizzo_storico WHERE document_name=? AND editor_name=? AND anno=? AND version=?";
            $stmt = $mysqli->prepare($sql);
            $stmt->bind_param("ssii", $request['document_name'], $request['editor_name'], $request['anno'], $request['version']);
            $res = $stmt->execute();
            if ($res = $stmt->get_result()) {
                $rows = $res->fetch_all(MYSQLI_ASSOC);
            } else
                $rows = [];
            $sql = "INSERT INTO DATE_documento_modello_fondo_utilizzo 
                    (ordinamento,sezione,nome_articolo,preventivo,consuntivo,attivo,document_name,editor_name,editable,anno,
                     version) VALUES (?,?,?,?,?,?,?,?,?,?,?)";
            $stmt = $mysqli->prepare($sql);
            $version = $rows[0]['version'] + 1;
            foreach ($rows as $entry) {
                $stmt->bind_param("issssissiii", $entry['ordinamento'], $entry['sezione'], $entry['nome_articolo'], $entry['preventivo'], $entry['consuntivo'],
                    $entry['attivo'], $entry['document_name'], $entry['editor_name'], $entry['editable'], $entry['anno'], $version);
                $res = $stmt->execute();
            }
            $sql = " SELECT  ordinamento,sezione,sottosezione,nome_articolo,formula,nota,document_name,editor_name,anno,
                     attivo,editable,version
FROM DATE_documento_modello_fondo_dati_utili_storico WHERE document_name=? AND editor_name=? AND anno=? AND version=?";
            $stmt = $mysqli->prepare($sql);
            $stmt->bind_param("ssii", $request['document_name'], $request['editor_name'], $request['anno'], $request['version']);
            $res = $stmt->execute();
            if ($res = $stmt->get_result()) {
                $rows = $res->fetch_all(MYSQLI_ASSOC);
            } else
                $rows = [];
            $sql = "INSERT INTO DATE_documento_modello_fondo_dati_utili_storico 
                    (ordinamento,sezione,sottosezione,nome_articolo,formula,nota,document_name,editor_name,anno,
                     attivo,editable,version) VALUES (?,?,?,?,?,?,?,?,?,?,?,?)";
            $stmt = $mysqli->prepare($sql);
            $version = $rows[0]['version'] + 1;
            foreach ($rows as $entry) {
                $stmt->bind_param("isssssssiiii", $entry['ordinamento'], $entry['sezione'], $entry['nome_articolo'], $entry['formula'], $entry['nota'],
                    $entry['document_name'], $entry['editor_name'], $entry['anno'], $entry['attivo'], $entry['editable'], $version);
                $res = $stmt->execute();
            }

        } else {
            $sql = "SELECT chiave, valore, document_name, editor_name, anno, active, editable, page, version FROM DATE_documenti_odt_storico WHERE  document_name=? AND editor_name=? AND anno=? AND version=?";
            $stmt = $mysqli->prepare($sql);
            $stmt->bind_param("ssii", $request['document_name'], $request['editor_name'], $request['anno'], $request['version']);
            $res = $stmt->execute();
            if ($res = $stmt->get_result()) {
                $rows = $res->fetch_all(MYSQLI_ASSOC);
            } else
                $rows = [];
            $sql = "INSERT INTO DATE_documenti_odt (chiave, valore, document_name, editor_name, anno, active, editable, page, version) VALUES (?,?,?,?,?,?,?,?,) ";
         $stmt = $mysqli->prepare($sql);
            $version = $rows[0]['version'] + 1;
            foreach ($rows as $entry) {
                $stmt->bind_param("ssssiiisi", $entry['chiave'], $entry['valore'], $entry['document_name'], $entry['editor_name'], $entry['anno'],
                    $entry['active'], $entry['editable'], $entry['page'], $version);
                $res = $stmt->execute();
            }
        }
        $mysqli->close();

    }
}
