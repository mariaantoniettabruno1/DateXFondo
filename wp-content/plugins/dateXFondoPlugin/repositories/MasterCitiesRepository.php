<?php

namespace dateXFondoPlugin;

use mysqli;

class MasterCitiesRepository
{

    public function getAllCities()
    {
        $conn = new SlaveConnection();
        $mysqli = $conn->connect();

        $sql = "SELECT id,nome,descrizione,data_creazione,data_scadenza,id_consulente,attivo FROM DATE_ente";
        $result = $mysqli->query($sql);
        $row = $result->fetch_all(MYSQLI_ASSOC);
        $mysqli->close();

        $conn = new Connection();
        $mysqli = $conn->connect();

        $sql = "SELECT DISTINCT id,user_login FROM wp_users WHERE id=?";
        $stmt = $mysqli->prepare($sql);
        foreach ($row as $entry) {
            $stmt->bind_param("i", $entry['id_consulente']);
            $res = $stmt->execute();
        }

        if ($res = $stmt->get_result()) {
            $rows = $res->fetch_all(MYSQLI_ASSOC);
        } else
            $rows = [];
        $mysqli->close();

        return array($row, $rows);
    }


    public function edit_cities_user($params)
    {
        $conn = new Connection();
        $mysqli = $conn->connect();
        $sql = "SELECT id FROM wp_users WHERE user_login=?";
        $stmt = $mysqli->prepare($sql);
        $stmt->bind_param("s", $params['nome_utente']);
        $res = $stmt->execute();

        if ($res = $stmt->get_result()) {
            $rows = $res->fetch_all(MYSQLI_ASSOC);
        } else
            $rows = [];

        $sql = "UPDATE DATE_users SET attivo=0 WHERE id_user=?";
        $stmt = $mysqli->prepare($sql);
        $stmt->bind_param("s", $rows[0]['id']);
        $res = $stmt->execute();

        $sql = "INSERT INTO DATE_users (id_user,id_ente,db)  VALUES (?,?,?)";
        $stmt = $mysqli->prepare($sql);
        foreach ($params['citiesArray'] as $ente) {
            $stmt->bind_param("sss", $rows[0]['id'], $ente['id'], $ente['db']);
            $res = $stmt->execute();
        }
        if ($params['tuttiButton'])
            $params['tuttiButton'] = 1;
        else
            $params['tuttiButton'] = 0;
        $sql = "UPDATE DATE_users SET attivo=1,tutti=? WHERE id_user=? AND id_ente=?";
        $stmt = $mysqli->prepare($sql);
        foreach ($params['citiesArray'] as $ente) {
            $stmt->bind_param("iss", $params['tuttiButton'], $rows[0]['id'], $ente['id']);
            $res = $stmt->execute();
        }
        $rows = $this->getCheckedCities($params['nome_utente']);
        $mysqli->close();
        return $rows;
    }

    public function getConsultants()
    {
        $conn = new Connection();
        $mysqli = $conn->connect();

        $sql = "SELECT DISTINCT id,user_login FROM wp_users ";
        $result = $mysqli->query($sql);
        $row = $result->fetch_all(MYSQLI_ASSOC);
        $mysqli->close();
        return $row;
    }

    public function getCities()
    {
        $conn = new SlaveConnection();
        $mysqli = $conn->connect();
        $sql = "SELECT DISTINCT id,nome FROM DATE_ente WHERE attivo=1";
        $result = $mysqli->query($sql);
        $row = $result->fetch_all(MYSQLI_ASSOC);
        $mysqli->close();
        return $row;
    }

    public function getCheckedCities($user)
    {
        $conn = new Connection();
        $mysqli = $conn->connect();

        if ($user['bool'] === 1 || $user['bool'] === '1') {

            $sql = "SELECT id FROM wp_users WHERE user_login=?";
            $stmt = $mysqli->prepare($sql);
            $stmt->bind_param("s", $user['selectedValue']);
            $res = $stmt->execute();

            if ($res = $stmt->get_result()) {
                $rows = $res->fetch_all(MYSQLI_ASSOC);
            } else
                $rows = [];

        } else {
            $sql = "SELECT id FROM wp_users WHERE user_login=?";
            $stmt = $mysqli->prepare($sql);
            $stmt->bind_param("s", $user);
            $res = $stmt->execute();

            if ($res = $stmt->get_result()) {
                $rows = $res->fetch_all(MYSQLI_ASSOC);
            } else
                $rows = [];
        }


        $sql = "SELECT id_ente FROM DATE_users WHERE id_user=? AND attivo=1";
        $stmt = $mysqli->prepare($sql);
        $stmt->bind_param("s", $rows[0]['id']);
        $res = $stmt->execute();

        if ($res = $stmt->get_result()) {
            $rows = $res->fetch_all(MYSQLI_ASSOC);
        } else
            $rows = [];

        return $rows;
    }

}