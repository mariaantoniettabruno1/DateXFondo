<?php

namespace dateXFondoPlugin;

class DeliberaDocumentRepository
{
    public static function edit_delibera_document($request){
        $conn = new Connection();
        $mysqli = $conn->connect();
        $sql = "UPDATE DATE_documento_modello_fondo SET document_name=?, anno=? WHERE document_name=?";
        $stmt = $mysqli->prepare($sql);
        $stmt->bind_param("sis", $request['document_name'], $request['anno'], $request['old_document_name']);
        $stmt->execute();
        $mysqli->close();
    }
}