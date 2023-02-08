<?php

namespace NRZero\JAF\Core;

use PDO;
use PDOException;

class Database
{
    public \PDO $pdo;
    private string $query;
    private string $tableName;
    private string|array $columns;
    private array $data;
    private string $mode;
    private array $joins;
    public function __construct(string $db_host, string $db_name, string $db_username, string $db_pass)
    {
        try {
            $this->pdo = new PDO(
                'mysql:host=' . $db_host . ";dbname=" . $db_name,
                $db_username,
                $db_pass
            );
            $this->pdo->setAttribute(PDO::ATTR_EMULATE_PREPARES, false);
            $this->pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
            print_r("connected");
        } catch (PDOException $error) {
            echo "Connection error: " . $error->getMessage();
        }
    }

    public function prepareInsert(array $data)
    {
        $this->data = $data;
        $this->mode = "INSERT";
        return $this;
    }

    public function selectTable(string $tableName)
    {
        $this->tableName = $tableName;
        return $this;
    }

    public function selectColumns(array $columns)
    {
        $this->columns = $columns;
        return $this;
    }

    public function prepare()
    {
        switch ($this->mode) {
            case "INSERT":
                if (empty($this->columns)) {
                    $this->query = "INSERT INTO {$this->tableName} VALUES (:";
                }
        }
    }

    public function run()
    {
        // run pdo query
    }
}
