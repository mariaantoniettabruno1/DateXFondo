<?php

use dateXFondoPlugin\Connection;

class DocumentTable
{
    public static function saveDocument($title, $document, $fondo, $ente, $anno)
    {
        $conn = new Connection();
        $mysqli = $conn->connect();
        $sql = "INSERT INTO DATE_documento (titolo,testo, fondo, ente, anno) VALUES (?,?,?,?,?) ";
        $stmt = $mysqli->prepare($sql);
        $stmt->bind_param("ssssi", $title, $document, $fondo, $ente, $anno);
        $res = $stmt->execute();
        mysqli_close($mysqli);
    }

    public static function updateDocument($title, $document, $fondo, $ente, $anno)
    {
        $conn = new Connection();
        $mysqli = $conn->connect();
        $sql = "UPDATE DATE_documento SET titolo=?, testo=?, fondo=?, ente=?, anno=? ";
        $stmt = $mysqli->prepare($sql);
        $stmt->bind_param("ssssi", $title, $document, $fondo, $ente, $anno);
        $res = $stmt->execute();
        mysqli_close($mysqli);
    }

    public static function getEditedDocument($id)
    {
        $conn = new Connection();
        $mysqli = $conn->connect();
        $sql = "SELECT testo FROM DATE_documento WHERE id=?";
        $stmt = $mysqli->prepare($sql);
        $stmt->bind_param("i", $id);
        $res = $stmt->execute();
        $res = $stmt->get_result();
        $entries = $res->fetch_assoc();
        mysqli_close($mysqli);
        return $entries;
    }
}