<?php

namespace dateXFondoPlugin;


function modifica_campi($request)
{
    $input = (array)$request->get_body_params();

    $conn = new Connection();
    $mysqli = $conn->connect();

    if (isset($input['action']) && $input['action'] == 'edit') {
        if (isset($input['valore'])) {
            $sql = "UPDATE DATE_entry_chivasso SET valore=?  WHERE id=?";
            $stmt = $mysqli->prepare($sql);
            $stmt->bind_param("si", $input['valore'], $input['id']);
            $res = $stmt->execute();

        } else if (isset($input['nota'])) {
            $sql = "UPDATE DATE_entry_chivasso SET nota=?  WHERE id=?";
            $stmt = $mysqli->prepare($sql);
            $stmt->bind_param("si", $input['nota'], $input['id']);
            $res = $stmt->execute();
            $mysqli->close();
        }


    }

    return $stmt;
}

function modifica_campi_nuovo_template($request)
{

    $input = (array)$request->get_body_params();

    $conn = new Connection();
    $mysqli = $conn->connect();

    if (isset($input['action']) && $input['action'] == 'edit') {
        if (isset($input['id_campo'])) {
            $sql = "UPDATE DATE_entry_chivasso SET id_campo=?  WHERE id=?";
            $stmt = $mysqli->prepare($sql);
            $stmt->bind_param("ii", $input['id_campo'], $input['id']);
            $res = $stmt->execute();
            $mysqli->close();
        } else if (isset($input['nota'])) {
            $sql = "UPDATE DATE_entry_chivasso SET nota=?  WHERE id=?";
            $stmt = $mysqli->prepare($sql);
            $stmt->bind_param("si", $input['nota'], $input['id']);
            $res = $stmt->execute();
            $mysqli->close();
        }


    }
    $mysqli->close();
    return 'Chiamata ok';
}

function caricamento_campi($request)
{
    $temp_data = new DuplicateOldTemplate();
    $conn = new Connection();
    $mysqli = $conn->connect();
    $titolo_fondo = $_POST["JSONIn"]["fondo"];
    $ente = $_POST["JSONIn"]["ente"];
    $anno = $_POST["JSONIn"]["anno"];
    $anno = (int)$anno;
    $anno_precedente = $anno - 1;
    $entries = $temp_data->getOldData($ente, $anno_precedente);
    $sql = "INSERT INTO DATE_entry_chivasso (fondo,ente,anno,id_campo,label_campo,descrizione_campo,sottotitolo_campo) VALUES(?,?,?,?,?,?,?)";
    $stmt = $mysqli->prepare($sql);
    foreach ($entries as $entry) {
        $stmt->bind_param("sssssss", $titolo_fondo, $ente, $anno, $entry[4], $entry[5], $entry[6], $entry[7]);
        $res = $stmt->execute();
    }
    $mysqli->close();
    return true;

}

function creazione_nuova_riga($request)
{
    $conn = new Connection();
    $mysqli = $conn->connect();
    $titolo_fondo = $_POST["JSONIn"]["fondo"];
    $ente = $_POST["JSONIn"]["ente"];
    $anno = $_POST["JSONIn"]["anno"];
    $sql = "INSERT INTO DATE_entry_chivasso (fondo,ente,anno) VALUES(?,?,?)";
    $stmt = $mysqli->prepare($sql);
    $stmt->bind_param("sss", $titolo_fondo, $ente, $anno);
    $res = $stmt->execute();
    $mysqli->close();
    return true;
}

function cancella_riga($request)
{
    $input = (array)$request->get_body_params();
    $conn = new Connection();
    $mysqli = $conn->connect();
    $sql = "UPDATE DATE_entry_chivasso SET attivo=0  WHERE id=?";
    $stmt = $mysqli->prepare($sql);
    $stmt->bind_param("i", $input['id']);
    $res = $stmt->execute();
    $mysqli->close();
    return 'id cancellato';
}




