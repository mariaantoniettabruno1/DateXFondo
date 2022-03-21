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
    }
    $mysqli->close();

}
