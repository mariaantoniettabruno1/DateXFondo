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
            $mysqli->close();
        } else if (isset($input['nota'])) {
            $sql = "UPDATE DATE_entry_chivasso SET nota=?  WHERE id=?";
            $stmt = $mysqli->prepare($sql);
            $stmt->bind_param("si", $input['nota'], $input['id']);
            $res = $stmt->execute();
            $mysqli->close();
        }


        $mysqli->close();

    }
}

function modifica_campi_nuovo_template($request)
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
            $mysqli->close();
        } else if (isset($input['nota'])) {
            $sql = "UPDATE DATE_entry_chivasso SET nota=?  WHERE id=?";
            $stmt = $mysqli->prepare($sql);
            $stmt->bind_param("si", $input['nota'], $input['id']);
            $res = $stmt->execute();
            $mysqli->close();
        }


        $mysqli->close();

    }
    function caricamento_campi($request)
    {

        $conn = new Connection();
        $mysqli = $conn->connect();
        $titolo_fondo = $_POST["JSONIn"]["fondo"];
        $ente = $_POST["JSONIn"]["ente"];
        $anno = $_POST["JSONIn"]["anno"];
        $sql = "INSERT INTO DATE_entry_chivasso (fondo,ente,anno) VALUES(?,?,?)";
        $stmt = $mysqli->prepare($sql);
        $stmt->bind_param("sss", $titolo_fondo, $ente,$anno);
        $res = $stmt->execute();
        $mysqli->close();

    }
}



