<?php

class Database
{
    private static $instance = null; 
    private $pdo;

    private function __construct()
    {
        $servername = $_SERVER['DB_HOST'];
        $username = $_SERVER['DB_USERNAME'];
        $password = $_SERVER['DB_PASSWORD'];
        $dbName = $_SERVER['DB_NAME'];

        try {
            $this->pdo = new PDO("mysql:host=$servername;dbname=$dbName;charset=utf8mb4", $username, $password);
            $this->pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
        } catch (PDOException $e) {
            echo "Connection failed: " . $e->getMessage();
            exit; 
        }
    }

    public static function getInstance()
    {
        if (self::$instance === null) {
            self::$instance = new self();
        }
        return self::$instance->pdo;
    }
}

function safeQuote($value)
{
    $pdo = Database::getInstance(); 
    if (is_null($value)) {
        return 'NULL';
    } else {
        return $pdo->quote($value);
    }
}

function exportDatabaseUsingPDO($outputFile)
{
    try {
        $pdo = Database::getInstance();

        $tables = $pdo->query("SHOW TABLES")->fetchAll(PDO::FETCH_COLUMN);

        $output = fopen($outputFile, 'w'); 

        foreach ($tables as $table) {
            $stmt = $pdo->query("SHOW CREATE TABLE `$table`");
            $createTable = $stmt->fetch(PDO::FETCH_ASSOC);
            fwrite($output, "-- Table structure for `$table`\n");
            fwrite($output, $createTable['Create Table'] . ";\n\n");

            $stmt = $pdo->query("SELECT * FROM `$table`");
            while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
                $rowValues = array_map(function($value) {
                    return safeQuote($value);
                }, $row);
                $rowValues = implode(", ", $rowValues);
                fwrite($output, "INSERT INTO `$table` VALUES ($rowValues);\n");
            }
            fwrite($output, "\n");
        } 

        fclose($output); 
    } catch (PDOException $e) {
        echo "Error: " . $e->getMessage();
    }
}

$outputFile = 'backup/db/db.sql'; 
if (defined('DATABASE_BACKUP_PERM') && DATABASE_BACKUP_PERM) {
    exportDatabaseUsingPDO($outputFile);
}
