<?php

namespace dateXFondoPlugin;

class DeliberaDocumentRepository
{
    public static function getAllValues($document_name, $editor_name)
    {
        $values = array();
        $conn = new Connection();
        $mysqli = $conn->connect();
        $sql = "SELECT chiave, valore,document_name, editor_name, year, editable FROM DATE_documenti_odt WHERE document_name=? AND editor_name=?";
        $stmt = $mysqli->prepare($sql);
        $stmt->bind_param("ss", $document_name, $editor_name);
        $res = $stmt->execute();
        $res = $stmt->get_result();
        $rows = $res->fetch_all(MYSQLI_ASSOC);
        mysqli_close($mysqli);

        return $rows;
    }

    public static function edit_delibera_document($request)
    {
        $conn = new Connection();
        $mysqli = $conn->connect();
        $sql = "UPDATE DATE_documenti_odt SET valore=? WHERE chiave=? AND document_name=? AND editor_name=? AND year=?";
        $stmt = $mysqli->prepare($sql);
        foreach ($request['editedInputs'] as $key => $value) {
            $stmt->bind_param("ssssi", $value, $key, $request['document_name'], $request['editor_name'], $request['year']);
            $stmt->execute();
        }
        $mysqli->close();
    }

    public static function edit_delibera_header($request)
    {
        $conn = new Connection();
        $mysqli = $conn->connect();
        $sql = "UPDATE DATE_documenti_odt SET document_name=?, editor_name=?, year=? WHERE document_name=? AND editor_name=?";
        $stmt = $mysqli->prepare($sql);
        $stmt->bind_param("ss", $request['old_document_name'], $request['old_editor_name']);
        $stmt->execute();
        $mysqli->close();
    }
}