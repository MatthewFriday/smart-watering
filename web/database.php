<?php

class Database {
    private $conn = null;
    private $user;
    private $pass;
    private $host;
    private $db;
    private $ex = null;

    function __construct($user, $pass, $host, $db) {
        $this->user = $user;
        $this->pass = $pass;
        $this->host = $host;
        $this->db = $db;
    }

    function connect() {
        try {
            $this->conn = new PDO("mysql:host=$this->host;dbname=$this->db", $this->user, $this->pass);
            $this->conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
        } catch(PDOException $e) {
            echo "Connection failed: " . $e->getMessage();
        }
    }

    function get_last_error() { return $this->ex; }

    function check_user($username, $password) {
        if (!isset($this->conn))
            $this->connect();

        $stmt = $this->conn->prepare("SELECT ID, password FROM `users` WHERE `username` = ?;");
        $stmt->bindParam(1, $username, PDO::PARAM_STR);
        $stmt->execute();

        $results = $stmt->fetchAll(PDO::FETCH_ASSOC);

        if (count($results) == 0)
            return false;
        
        if (password_verify($password, $results[0]["password"]))
            return $results[0]["ID"];
        else
            return false;
    }

    function get_user($uid) {
        if (!isset($this->conn))
            $this->connect();

        $stmt = $this->conn->prepare("SELECT * FROM `users` WHERE `ID` = ?;");
        $stmt->bindParam(1, $uid, PDO::PARAM_INT);
        $stmt->execute();

        $results = $stmt->fetchAll(PDO::FETCH_ASSOC);

        if (count($results) == 0)
            return false;
        
        return $results[0];
    }

    function get_latest_measurements() {
        if (!isset($this->conn))
            $this->connect();

        $stmt = $this->conn->prepare("SELECT * FROM `latest_measurements`;");
        $stmt->execute();

        $results = $stmt->fetchAll(PDO::FETCH_ASSOC);

        if (count($results) == 0)
            return false;
        
        return $results[0];
    }

    function get_all_measurements($start, $end) {
        if (!isset($this->conn))
            $this->connect();

        $stmt = $this->conn->prepare("SELECT * FROM `measurements` WHERE `timestamp` BETWEEN ? AND ?;");
        $stmt->bindParam(1, $start, PDO::PARAM_STR);
        $stmt->bindParam(2, $end, PDO::PARAM_STR);
        $stmt->execute();

        $results = $stmt->fetchAll(PDO::FETCH_ASSOC);

        return $results;
    }

    function get_measurement_stats() {
        if (!isset($this->conn))
            $this->connect();

        $stmt = $this->conn->prepare("SELECT count(timestamp) AS 'count', min(timestamp) AS 'min_timestamp', max(timestamp) AS 'max_timestamp' FROM measurements;");
        $stmt->execute();

        $results = $stmt->fetchAll(PDO::FETCH_ASSOC);

        if (count($results) == 0)
            return false;
        
        return $results[0];
    }

    function get_rules() {
        if (!isset($this->conn))
            $this->connect();

        $stmt = $this->conn->prepare("SELECT * FROM `conditions`;");
        $stmt->execute();

        $results = $stmt->fetchAll(PDO::FETCH_ASSOC);

        return $results;
    }

    function add_rule($data) {
        if (!isset($this->conn))
            $this->connect();

        if (!isset($data["ruleID"], $data["value"], $data["conditionexpr"])) {
            $this->ex = "Missing parameters";
            return false;
        }

        if (!in_array($data["value"], array(
            'moisture','light','temperature','humidity','time'
        ))) { $this->ex = "Invalid value"; return false; }

        $stmt = $this->conn->prepare("INSERT INTO `conditions` (`ID`, `ruleID`, `value`, `conditionexpr`) VALUES (NULL, ?, ?, ?);");
        $stmt->bindParam(1, $data["ruleID"], PDO::PARAM_INT);
        $stmt->bindParam(2, $data["value"], PDO::PARAM_STR);
        $stmt->bindParam(3, $data["conditionexpr"], PDO::PARAM_STR);

        try {
            $stmt->execute();
            return $stmt->rowCount() > 0;
        } catch (PDOException $e) {
            $this->ex = $e->getMessage();
            return false;
        }
    }

    function edit_rule($data) {
        if (!isset($this->conn))
            $this->connect();

        if (!isset($data["condID"], $data["ruleID"], $data["value"], $data["conditionexpr"])) {
            $this->ex = "Missing parameters";
            return false;
        }

        if (!in_array($data["value"], array(
            'moisture','light','temperature','humidity','time'
        ))) { $this->ex = "Invalid value"; return false; }

        $stmt = $this->conn->prepare("UPDATE `conditions` SET `ruleID` = ?, `value` = ?, `conditionexpr` = ? WHERE `conditions`.`ID` = ?;");
        $stmt->bindParam(1, $data["ruleID"], PDO::PARAM_INT);
        $stmt->bindParam(2, $data["value"], PDO::PARAM_STR);
        $stmt->bindParam(3, $data["conditionexpr"], PDO::PARAM_STR);
        $stmt->bindParam(4, $data["condID"], PDO::PARAM_INT);

        try {
            return $stmt->execute();
        } catch (PDOException $e) {
            $this->ex = $e-getMessage();
            return false;
        }
    }

    function del_rule($id) {
        if (!isset($this->conn))
            $this->connect();

        $stmt = $this->conn->prepare("DELETE FROM `conditions` WHERE `conditions`.`ID` = ?;");
        $stmt->bindParam(1, $id, PDO::PARAM_INT);
        $stmt->execute();

        return $stmt->rowCount() != 0;
    }

    function get_config() {
        if (!isset($this->conn))
            $this->connect();

        $stmt = $this->conn->prepare("SELECT * FROM `config`;");
        $stmt->execute();

        $results = $stmt->fetchAll(PDO::FETCH_ASSOC);

        $output = array();

        foreach ($results as $item) {
            $output[$item["name"]] = $item["value"];
        }

        return $output;
    }

    function set_notify_config($config) {
        if (!isset($this->conn))
            $this->connect();

        if (!isset($config["poverEnable"], $config["poverToken"], $config["poverUserKey"])) {
            $this->ex = "Missing parameters";
            return false;
        }

        $result = true;

        $stmt = $this->conn->prepare("UPDATE `config` SET `value` = ? WHERE `config`.`name` = 'poverEnable';");
        $stmt->bindParam(1, $config["poverEnable"], PDO::PARAM_STR);

        try {
            $temp = $stmt->execute();
            if (!$temp) $result = false;
        } catch (PDOException $e) {
            $this->ex = $e-getMessage();
            return false;
        }

        $stmt = $this->conn->prepare("UPDATE `config` SET `value` = ? WHERE `config`.`name` = 'poverToken';");
        $stmt->bindParam(1, $config["poverToken"], PDO::PARAM_STR);

        try {
            $temp = $stmt->execute();
            if (!$temp) $result = false;
        } catch (PDOException $e) {
            $this->ex = $e-getMessage();
            return false;
        }

        $stmt = $this->conn->prepare("UPDATE `config` SET `value` = ? WHERE `config`.`name` = 'poverUserKey';");
        $stmt->bindParam(1, $config["poverUserKey"], PDO::PARAM_STR);

        try {
            $temp = $stmt->execute();
            if (!$temp) $result = false;
        } catch (PDOException $e) {
            $this->ex = $e-getMessage();
            return false;
        }

        return $result;
    }
}