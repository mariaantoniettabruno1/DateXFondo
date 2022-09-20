<?php

namespace dateXFondoPlugin;

function modifica_campi($request)
{
    $input = (array)$request->get_body_params();

    $conn = new Connection();
    $mysqli = $conn->connect();

    if (isset($input['action']) && $input['action'] == 'edit') {
        $sql = "UPDATE DATE_template_fondo SET id_campo=?,
                               label_campo=?,
                               descrizione_campo=?,
                               sottotitolo_campo=?,
                               valore=?,
                               valore_anno_precedente=?,
                               nota=? 
WHERE id=?";
        $stmt = $mysqli->prepare($sql);
        $stmt->bind_param("issssfs", $input['id_campo'], $input['label_campo'], $input['descrizione_campo'], $input['sottotitolo_campo'], $input['valore'], $input['valore_anno_precedente'], $input['nota'], $input['id']);
        $res = $stmt->execute();
        $mysqli->close();
    } else {
        $mysqli->close();
    }
    return $input;
}

function modifica_campi_slave($request)
{
    $input = (array)$request->get_body_params();

    $conn = new Connection();
    $mysqli = $conn->connect();
    if (isset($input['action']) && $input['action'] == 'edit') {
        $sql = "UPDATE DATE_entry_chivasso SET  valore=?, nota=?   WHERE id=?";
        $stmt = $mysqli->prepare($sql);
        $stmt->bind_param("isi",
            $input['valore'],
            $input['nota'],
            $input['id']);
        $res = $stmt->execute();
        $mysqli->close();
    } else {
        $mysqli->close();

    }

    return $input;

}


function modifica_campi_nuovo_template($request)
{
    $conn = new Connection();
    $mysqli = $conn->connect();
    $id = (int)$_POST['id_riga'];
    $sql = "UPDATE DATE_template_fondo SET id_articolo=?, nome_articolo=?, sottotitolo_articolo=?, descrizione_articolo=?, link=?
WHERE id=?";
    $stmt = $mysqli->prepare($sql);
    $stmt->bind_param("sssssi", $_POST['id_articolo'],
        $_POST['nome_articolo'],
        $_POST['sottotitolo_articolo'],
        $_POST['descrizione_articolo'],
        $_POST['link'],
        $id);
    $res = $stmt->execute();
    $mysqli->close();
    return $request;
}

function caricamento_campi($request)
{
    $temp_data = new DuplicateOldTemplate();
    $conn = new Connection();
    $mysqli = $conn->connect();
    $titolo_fondo = $_POST["JSONIn"]["fondo"];
    $ente = $_POST["JSONIn"]["ente"];
    $anno = $_POST["JSONIn"]["anno"];
    $campo_ereditato = $_POST["campo_ereditato"];
    $anno = (int)$anno;
    $anno_precedente = $anno - 1;


    $entries = $temp_data->getOldData($titolo_fondo, $anno_precedente);
    if (strcmp($campo_ereditato, "Valore") == 0) {
        $sql = "INSERT INTO DATE_template_fondo (fondo,anno,id_campo,label_campo,descrizione_campo,sottotitolo_campo,valore) VALUES(?,?,?,?,?,?,?)";
        $stmt = $mysqli->prepare($sql);
        foreach ($entries as $entry) {
            $stmt->bind_param("sssssss", $titolo_fondo, $ente, $anno, $entry[4], $entry[6], $entry[7], $entry[8], $entry[9]);
            $res = $stmt->execute();
        }
    } elseif ($campo_ereditato == "Nota e Valore") {
        $sql = "INSERT INTO DATE_template_fondo (fondo,anno,id_campo,label_campo,descrizione_campo,sottotitolo_campo,valore, nota) VALUES(?,?,?,?,?,?,?,?)";
        $stmt = $mysqli->prepare($sql);
        foreach ($entries as $entry) {
            $stmt->bind_param("ssssssss", $titolo_fondo, $anno, $entry[4], $entry[6], $entry[7], $entry[8], $entry[9], $entry[11]);
            $res = $stmt->execute();
        }
    } else {
        $sql = "INSERT INTO DATE_template_fondo (fondo,anno,id_campo,label_campo,descrizione_campo,sottotitolo_campo) VALUES(?,?,?,?,?,?)";
        $stmt = $mysqli->prepare($sql);
        foreach ($entries as $entry) {
            $stmt->bind_param("ssssss", $titolo_fondo, $ente, $anno, $entry[4], $entry[6], $entry[7], $enty[8]);
            $res = $stmt->execute();
        }
    }
    $mysqli->close();
    return true;

}

function creazione_nuova_riga($request)
{
    $conn = new Connection();
    $mysqli = $conn->connect();
    //TODO capire come passare fondo e anno da js, altrimenti usare una get che prende i dati dal db prima dell'insert
    $fondo = 'Fondo 2022';
    $anno = 2022;
    $sezione = $_POST["sezione"];
    $sottosezione = $_POST["sottosezione"];
    $id_articolo = $_POST["id_articolo"];
    $nome_articolo = $_POST["nome_articolo"];
    $descrizione_articolo = $_POST["descrizione_articolo"];
    $sottotitolo_articolo = $_POST["sottotitolo_articolo"];
    $link = $_POST["link"];
    $sql = "INSERT INTO DATE_template_fondo (fondo,anno,sezione,sottosezione,id_articolo,nome_articolo,
                                 descrizione_articolo,sottotitolo_articolo,link) VALUES(?,?,?,?,?,?,?,?,?)";
    $stmt = $mysqli->prepare($sql);
    $stmt->bind_param("sssssssss", $fondo, $anno, $sezione, $sottosezione, $id_articolo, $nome_articolo, $descrizione_articolo, $sottotitolo_articolo, $link);
    $res = $stmt->execute();
    $mysqli->close();
    return $stmt->insert_id;
}

function cancella_riga($request)
{
    $input = (array)$request->get_body_params();
    $conn = new Connection();
    $mysqli = $conn->connect();
    $sql = "UPDATE DATE_template_fondo SET attivo=0  WHERE id=?";
    $stmt = $mysqli->prepare($sql);
    $stmt->bind_param("i", $_POST['id_riga']);
    $res = $stmt->execute();
    $mysqli->close();
    return 'id cancellato';
}




