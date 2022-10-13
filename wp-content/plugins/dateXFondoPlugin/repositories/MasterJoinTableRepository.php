<?php

namespace dateXFondoPlugin;

class MasterJoinTableRepository
{
    public static function getJoinedArticoli()
    {
        // manca campo orientamento perchè ha lo stesso nome in entrambe le tabelle
        $conn = new Connection();
        $mysqli = $conn->connect();
        $sql = "SELECT fondo,anno,descrizione_fondo,sezione,sottosezione,id_articolo,nome_articolo,sottotitolo_articolo,nota,link,editable,version,row_type,heredity
                FROM DATE_template_fondo LEFT JOIN DATE_template_formula ON DATE_template_fondo.id = DATE_template_formula.external_id";
        $result = $mysqli->query($sql);
        $rows = $result->fetch_all(MYSQLI_ASSOC);
        mysqli_close($mysqli);
        return $rows;
    }
    public static function getJoinedFormulas()
    {
        $conn = new Connection();
        $mysqli = $conn->connect();
        $sql = "SELECT sezione,sottosezione,nome,descrizione,condizione,formula 
                FROM DATE_formula LEFT JOIN DATE_template_formula ON DATE_formula.id = DATE_template_formula.external_id";
        $result = $mysqli->query($sql);
        $rows = $result->fetch_all(MYSQLI_ASSOC);
        mysqli_close($mysqli);
        return $rows;
    }
    public static function getJoinedRecords()
    {
        $conn = new Connection();
        $mysqli = $conn->connect();
        $sql = "SELECT external_id,type,ordinamento  FROM DATE_template_formula";
        $result = $mysqli->query($sql);
        $rows = $result->fetch_all(MYSQLI_ASSOC);
        mysqli_close($mysqli);
        return $rows;
    }
}