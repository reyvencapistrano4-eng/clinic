<?php
class Database
{
    static $dbName = 'clinicdb';
    static $dbHost = 'localhost';
    static $dbUsername = 'root';
    static $dbPassword = '';

    private static $cont = null;

    public static function letsconnect()
    {
        try {
            if (self::$cont === null) {
                self::$cont = new PDO(
                    "mysql:host=" . self::$dbHost . ";dbname=" . self::$dbName,
                    self::$dbUsername,
                    self::$dbPassword
                );
                self::$cont->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
            }
            return self::$cont;
        } catch (PDOException $e) {
            echo json_encode(["status" => "error", "message" => "Connection failed: " . $e->getMessage()]);
            exit;
        }
    }

    public static function GetOneData($pdo, $sql, $params = [])
    {
        try {
            $q = $pdo->prepare($sql);
            $q->execute($params);
            $result = $q->fetch(PDO::FETCH_ASSOC);
            return $result;
            // returns false if no record
        } catch (PDOException $e) {
            echo json_encode(["status" => "error", "message" => "Database Error: " . $e->getMessage()]);
            exit;
        }
    }

    public static function GetAllData($pdo, $sql, $params = [])
    {
        try {
            $q = $pdo->prepare($sql);
            $q->execute($params);
            $result = $q->fetchAll(PDO::FETCH_ASSOC);
            return $result;
        } catch (PDOException $e) {
            echo json_encode(["status" => "error", "message" => "Database Error: " . $e->getMessage()]);
            exit;
        }
    }

    public static function ManageRecord($pdo, $sql, $params = [])
    {
        try {
            $q = $pdo->prepare($sql);
            $q->execute($params);
            return true;
        } catch (PDOException $e) {
            echo json_encode(["status" => "error", "message" => "Database Error: " . $e->getMessage()]);
            return false;
        }
    }
}