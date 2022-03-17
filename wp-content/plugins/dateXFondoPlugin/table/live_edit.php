<?php

namespace dateXFondoPlugin;


function modifica_campi($request)
{


    $input = (array)$request->get_body_params();

    $conn = new Connection();
    $mysqli = $conn->connect();


    if (isset($input['action']) && $input['action'] == 'edit') {
        if (isset($input['fondo'])) {
            $sql = "UPDATE DATE_entry_chivasso SET fondo=?  WHERE id=?";
            $stmt = $mysqli->prepare($sql);
            $stmt->bind_param("si", $input['fondo'], $input['id']);
            $res = $stmt->execute();
            $mysqli->close();

        } else if (isset($input['ente'])) {
            $sql = "UPDATE DATE_entry_chivasso SET ente=?  WHERE id=?";
            $stmt = $mysqli->prepare($sql);
            $stmt->bind_param("si", $input['ente'], $input['id']);
            $res = $stmt->execute();
            $mysqli->close();
        } else if (isset($input['anno'])) {
            $sql = "UPDATE DATE_entry_chivasso SET anno=?  WHERE id=?";
            $stmt = $mysqli->prepare($sql);
            $stmt->bind_param("ii", $input['anno'], $input['id']);
            $res = $stmt->execute();
            $mysqli->close();
        } else if (isset($input['id_campo'])) {
            $sql = "UPDATE DATE_entry_chivasso SET id_campo=?  WHERE id=?";
            $stmt = $mysqli->prepare($sql);
            $stmt->bind_param("ii", $input['id_campo'], $input['id']);
            $res = $stmt->execute();
            $mysqli->close();
        } else if (isset($input['label_campo'])) {
            $sql = "UPDATE DATE_entry_chivasso SET label_campo=?  WHERE id=?";
            $stmt = $mysqli->prepare($sql);
            $stmt->bind_param("si", $input['label_campo'], $input['id']);
            $res = $stmt->execute();
            $mysqli->close();
        } else if (isset($input['descrizione_campo'])) {
            $sql = "UPDATE DATE_entry_chivasso SET descrizione_campo=?  WHERE id=?";
            $stmt = $mysqli->prepare($sql);
            $stmt->bind_param("si", $input['descrizione_campo'], $input['id']);
            $res = $stmt->execute();
            $mysqli->close();
        } else if (isset($input['sottotitolo_campo'])) {
            $sql = "UPDATE DATE_entry_chivasso SET sottotitolo_campo=?  WHERE id=?";
            $stmt = $mysqli->prepare($sql);
            $stmt->bind_param("si", $input['sottotitolo_campo'], $input['id']);
            $res = $stmt->execute();
            $mysqli->close();
        } else if (isset($input['valore'])) {
            $sql = "UPDATE DATE_entry_chivasso SET valore=?  WHERE id=?";
            $stmt = $mysqli->prepare($sql);
            $stmt->bind_param("si", $input['valore'], $input['id']);
            $res = $stmt->execute();
            $mysqli->close();
        } else if (isset($input['valore_anno_precedente'])) {
            $sql = "UPDATE DATE_entry_chivasso SET valore_anno_precedente=?  WHERE id=?";
            $stmt = $mysqli->prepare($sql);
            $stmt->bind_param("ii", $input['valore_anno_precedente'], $input['id']);
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

}
