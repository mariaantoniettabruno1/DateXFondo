<?php

namespace dateXFondoPlugin;
print_r("Sono qui dentro");
use mysqli;
$input = filter_input_array(INPUT_POST);
echo "<pre>";
print_r($input);
echo "</pre>";
$conn = new Connection();
print_r("Sono dopo la connection");
$mysqli = $conn->connect();

if ($input['action'] == 'edit') {
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
