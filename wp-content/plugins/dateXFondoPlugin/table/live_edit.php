<?php

namespace dateXFondoPlugin;

function modifica_campi($request)
{
    $input = (array)$request->get_body_params();

    $conn = new Connection();
    $mysqli = $conn->connect();

    if (isset($input['action']) && $input['action'] == 'edit') {
        $sql = "UPDATE DATE_entry_chivasso SET id_campo=?,
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


function modifica_campi_nuovo_template($request)
{

    $input = (array)$request->get_body_params();

    $conn = new Connection();
    $mysqli = $conn->connect();
    if (isset($input['id_campo'])) {
        $sql = "UPDATE DATE_entry_chivasso SET id_campo=?
WHERE id=?";
        $stmt = $mysqli->prepare($sql);
        $stmt->bind_param("si", $input['id_campo'], $input['id']);
        $res = $stmt->execute();
    }
    else if(isset($input['sezione'])){
        $sql = "UPDATE DATE_entry_chivasso SET sezione=?
WHERE id=?";
        $stmt = $mysqli->prepare($sql);
        $stmt->bind_param("si", $input['sezione'], $input['id']);
        $res = $stmt->execute();
    }else if(isset($input['label_campo'])){
        $sql = "UPDATE DATE_entry_chivasso SET label_campo=?
WHERE id=?";
        $stmt = $mysqli->prepare($sql);
        $stmt->bind_param("si", $input['label_campo'], $input['id']);
        $res = $stmt->execute();
    }else if(isset($input['descrizione_campo'])){
        $sql = "UPDATE DATE_entry_chivasso SET descrizione_campo=?
WHERE id=?";
        $stmt = $mysqli->prepare($sql);
        $stmt->bind_param("si", $input['descrizione_campo'], $input['id']);
        $res = $stmt->execute();
    }else if(isset($input['sottotitolo_campo'])){
        $sql = "UPDATE DATE_entry_chivasso SET sottotitolo_campo=?
WHERE id=?";
        $stmt = $mysqli->prepare($sql);
        $stmt->bind_param("si", $input['sottotitolo_campo'], $input['id']);
        $res = $stmt->execute();
    } else if (isset($input['valore'])) {
        $sql = "UPDATE DATE_entry_chivasso SET valore=?
WHERE id=?";
        $stmt = $mysqli->prepare($sql);
        $stmt->bind_param("si", $input['valore'], $input['id']);
        $res = $stmt->execute();
    } else if (isset($input['valore_anno_precedente'])) {
        $sql = "UPDATE DATE_entry_chivasso SET valore_anno_precedente=?
WHERE id=?";
        $stmt = $mysqli->prepare($sql);
        $stmt->bind_param("si", $input['valore_anno_precedente'], $input['id']);
        $res = $stmt->execute();
    }else if (isset($input['nota'])) {
        $sql = "UPDATE DATE_entry_chivasso SET nota=?
WHERE id=?";
        $stmt = $mysqli->prepare($sql);
        $stmt->bind_param("si", $input['nota'], $input['id']);
        $res = $stmt->execute();
    }
    $mysqli->close();
    return $input;
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


    $entries = $temp_data->getOldData($ente, $anno_precedente);
    if(strcmp($campo_ereditato,"Valore")==0){
        $sql = "INSERT INTO DATE_entry_chivasso (fondo,ente,anno,id_campo,label_campo,descrizione_campo,sottotitolo_campo,valore) VALUES(?,?,?,?,?,?,?,?)";
        $stmt = $mysqli->prepare($sql);
        foreach ($entries as $entry) {
            $stmt->bind_param("ssssssss", $titolo_fondo, $ente, $anno, $entry[4], $entry[5], $entry[6], $entry[7],$entry[8]);
            $res = $stmt->execute();
        }
    }
    elseif($campo_ereditato == "Nota e Valore"){
        $sql = "INSERT INTO DATE_entry_chivasso (fondo,ente,anno,id_campo,label_campo,descrizione_campo,sottotitolo_campo,valore, nota) VALUES(?,?,?,?,?,?,?,?,?)";
        $stmt = $mysqli->prepare($sql);
        foreach ($entries as $entry) {
            $stmt->bind_param("sssssssss", $titolo_fondo, $ente, $anno, $entry[4], $entry[5], $entry[6], $entry[7],$entry[8],$entry[10]);
            $res = $stmt->execute();
        }
    }
    else{
        $sql = "INSERT INTO DATE_entry_chivasso (fondo,ente,anno,id_campo,label_campo,descrizione_campo,sottotitolo_campo) VALUES(?,?,?,?,?,?,?)";
        $stmt = $mysqli->prepare($sql);
        foreach ($entries as $entry) {
            $stmt->bind_param("sssssss", $titolo_fondo, $ente, $anno, $entry[4], $entry[5], $entry[6], $entry[7]);
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
    $titolo_fondo = $_POST["JSONIn"]["fondo"];
    $ente = $_POST["JSONIn"]["ente"];
    $anno = $_POST["JSONIn"]["anno"];
    $sql = "INSERT INTO DATE_entry_chivasso (fondo,ente,anno) VALUES(?,?,?)";
    $stmt = $mysqli->prepare($sql);
    $stmt->bind_param("sss", $titolo_fondo, $ente, $anno);
    $res = $stmt->execute();
    $mysqli->close();
    return $stmt->insert_id;
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




