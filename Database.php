<?php

namespace NRZero\JAF;

use Attribute;
use PDO;
use PDOException;

class Database
{
    public \PDO $pdo;
    private string $tableName;
    private string $sortColumn;
    private string $sortOrder;

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
            // print_r("connected");
        } catch (PDOException $error) {
            echo "Connection error: " . $error->getMessage();
        }
    }

    public function setTableName(string $tableName)
    {
        $this->tableName = $tableName;
        return $this;
    }

    public function insert(array $attributes, mixed $postData)
    {
        $preparedParams = array_map(fn($attr) => ":$attr", $attributes);
        $tickedAttributes = array_map(fn($attr) => "`$attr`", $attributes);
        $statement = $this->pdo->prepare(
            "INSERT INTO $this->tableName (" . implode(",", $tickedAttributes) . ")
            VALUES (" . implode(",", $preparedParams) . ")"
        );

        for ($index = 0; $index < sizeof($attributes); $index++) {
            if (is_array($postData)) {
                $statement->bindValue($preparedParams[$index], $postData[$attributes[$index]]);
            } else {
                $statement->bindValue($preparedParams[$index], $postData);
            }
        }
        return $statement->execute();
    }

    public function update(array $attributes, mixed $postData, string $where, mixed $value)
    {
        $preparedParams = array_map(fn($attr) => ":$attr", $attributes);
        $tickedAttributes = array_map(fn($attr) => "`$attr`", $attributes);
        $query = "UPDATE $this->tableName SET ";

        for ($index = 0; $index < sizeof($attributes); $index++) {
            if ($index == sizeof($attributes) - 1) {
                $query .= $tickedAttributes[$index] . "=" . "$preparedParams[$index] ";
            } else {
                $query .= $tickedAttributes[$index] . "=" . "$preparedParams[$index], ";
            }
        }

        $query .= "WHERE $where = :$where";

        $statement = $this->pdo->prepare($query);

        for ($index = 0; $index < sizeof($attributes); $index++) {
            if (is_array($postData)) {
                $statement->bindValue($preparedParams[$index], $postData[$attributes[$index]]);
            } else {
                $statement->bindValue($preparedParams[$index], $postData);
            }
        }
        $statement->bindValue(":$where", $value);
        return $statement->execute();
    }

    public function delete(string $where, mixed $value)
    {
        $statement = $this->pdo->prepare(
            "DELETE FROM $this->tableName
            WHERE $where = :$where"
        );
        $statement->bindValue(":$where", $value);
        return $statement->execute();
    }

    public function fetchOne(array $attributes, string $where, mixed $value)
    {
        $tickedAttributes = array_map(fn($attr) => "`$attr`", $attributes);
        $statement = $this->pdo->prepare(
            "SELECT " . implode(",", $tickedAttributes) .
            " FROM $this->tableName WHERE $where = :$where"
        );
        $statement->bindValue(":$where", $value);
        $statement->execute();
        return $statement->fetchAll();
    }

    public function fetchAll(array $attributes = [])
    {
        if (empty($attributes)) {
            $query = "SELECT * FROM $this->tableName";
        } else {
            $tickedAttributes = array_map(fn($attr) => "`$attr`", $attributes);
            $query = "SELECT " . implode(",", $tickedAttributes) . " FROM $this->tableName";
        }

        if (!empty($this->sortColumn)) {
            $query .= " ORDER BY '$this->sortColumn' $this->sortOrder";
        }
        $statement = $this->pdo->prepare($query);
        $statement->execute();
        return $statement->fetchAll();
    }

    public function sortBy(string $columnName, string $mode = 'ASC')
    {
        $this->sortColumn = $columnName;
        $this->sortOrder = $mode;
        return $this;
    }
}
