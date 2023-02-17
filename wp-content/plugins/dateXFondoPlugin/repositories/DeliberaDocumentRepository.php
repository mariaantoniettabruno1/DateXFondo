<?php

namespace dateXFondoPlugin;

class DeliberaDocumentRepository
{
    public static function getAllValues($document_name, $editor_name)
    {
        $conn = new Connection();
        $mysqli = $conn->connect();
        $sql = "SELECT chiave, valore,document_name, editor_name, anno, editable FROM DATE_documenti_odt WHERE document_name=? AND editor_name=?";
        $stmt = $mysqli->prepare($sql);
        $stmt->bind_param("ss", $document_name, $editor_name);
        $res = $stmt->execute();
        $res = $stmt->get_result();
        $rows = $res->fetch_all(MYSQLI_ASSOC);
        mysqli_close($mysqli);
        return $rows;
    }

    public static function getAllHistoryValues($document_name, $editor_name, $version)
    {
        $conn = new Connection();
        $mysqli = $conn->connect();
        $sql = "SELECT chiave, valore,document_name, editor_name, anno, editable FROM DATE_documenti_odt_storico WHERE document_name=? AND editor_name=? AND version=?";
        $stmt = $mysqli->prepare($sql);
        $stmt->bind_param("ssi", $document_name, $editor_name, $version);
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
        $page = 'delibera-indirizzi';
      $sql = "UPDATE DATE_documenti_odt SET valore=? WHERE chiave=? AND document_name=? AND editor_name=? AND anno=? AND page=?";
      $stmt = $mysqli->prepare($sql);
      foreach ($request['editedInputs'] as $key => $value) {
          $stmt->bind_param("ssssis", $value, $key, $request['document_name'], $request['editor_name'], $request['anno'],$page);
          $stmt->execute();
      }


//        $sql = "INSERT INTO DATE_documenti_odt (valore,chiave,document_name,editor_name,anno,page) VALUES(?,?,?,?,?,?)";
//        $stmt = $mysqli->prepare($sql);
//        foreach ($request['editedInputs'] as $key => $value) {
//            $stmt->bind_param("ssssis", $value, $key, $request['document_name'], $request['editor_name'], $request['anno'], $page);
//            $stmt->execute();
//
//        }
        mysqli_close($mysqli);

        return $stmt->affected_rows;
    }
    public static function edit_determina_document($request)
    {
        $conn = new Connection();
        $mysqli = $conn->connect();
        $page = 'determina-costituzione-fondo';
      $sql = "UPDATE DATE_documenti_odt SET valore=? WHERE chiave=? AND document_name=? AND editor_name=? AND anno=? AND page=?";
      $stmt = $mysqli->prepare($sql);
      foreach ($request['editedInputs'] as $key => $value) {
          $stmt->bind_param("ssssis", $value, $key, $request['document_name'], $request['editor_name'], $request['anno'],$page);
          $stmt->execute();
      }


//       $sql = "INSERT INTO DATE_documenti_odt (valore,chiave,document_name,editor_name,anno,page) VALUES(?,?,?,?,?,?)";
//       $stmt = $mysqli->prepare($sql);
//       foreach ($request['editedInputs'] as $key => $value) {
//           $stmt->bind_param("ssssis", $value, $key, $request['document_name'], $request['editor_name'], $request['anno'], $page);
//           $stmt->execute();
//
//       }
        mysqli_close($mysqli);

        return $stmt->affected_rows;
    }
 public static function edit_relazione_document($request)
    {
        $conn = new Connection();
        $mysqli = $conn->connect();
        $page = 'schema-di-relazione-illustrativa';
      $sql = "UPDATE DATE_documenti_odt SET valore=? WHERE chiave=? AND document_name=? AND editor_name=? AND anno=? AND page=?";
      $stmt = $mysqli->prepare($sql);
      foreach ($request['editedInputs'] as $key => $value) {
          $stmt->bind_param("ssssis", $value, $key, $request['document_name'], $request['editor_name'], $request['anno'],$page);
          $stmt->execute();
      }


//       $sql = "INSERT INTO DATE_documenti_odt (valore,chiave,document_name,editor_name,anno,page) VALUES(?,?,?,?,?,?)";
//       $stmt = $mysqli->prepare($sql);
//       foreach ($request['editedInputs'] as $key => $value) {
//           $stmt->bind_param("ssssis", $value, $key, $request['document_name'], $request['editor_name'], $request['anno'], $page);
//           $stmt->execute();
//
//       }
        mysqli_close($mysqli);

        return $stmt->affected_rows;
    }



    public static function edit_delibera_header($request)
    {
        $conn = new Connection();
        $mysqli = $conn->connect();
        $sql = "UPDATE DATE_documenti_odt SET document_name=?, editor_name=?, anno=? WHERE document_name=? AND editor_name=?";
        $stmt = $mysqli->prepare($sql);
        $stmt->bind_param("ss", $request['old_document_name'], $request['old_editor_name']);
        $stmt->execute();
        $mysqli->close();
    }
}