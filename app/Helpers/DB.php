<?php

namespace App\Helpers;

use PDO;
use PDOException;
use PDOStatement;

class DB
{
    /** @var PDO $dbh Database handle */
    private $dbh;

    /** @var PDOStatement $stmt */
    private $stmt;

    /**
     * @todo Allow connection to multiple databases - add database number choice to constructor
     * @todo Add logging of queries when debug mode is on
     *
     * @throws PDOException Remember to catch this exception. Error could reveal password!
     */
    public function __construct()
    {
        switch (getenv('DB_CONNECTION')) {
            case 'sqlite':
                $dsn = 'sqlite:' . ROOT . '/storage/database.sqlite';
                $options = [
                    PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION
                ];
                $username = null;
                $password = null;
                break;
            case 'mysql':
                $dsn = 'mysql:host=' . getenv('DB_HOST') . ';dbname=' . getenv('DB_DATABASE');
                $options = [
                    PDO::ATTR_ERRMODE            => PDO::ERRMODE_EXCEPTION,
                    PDO::MYSQL_ATTR_INIT_COMMAND => 'SET NAMES utf8'
                ];
                $username = getenv('DB_USERNAME');
                $password = getenv('DB_PASSWORD');
                break;
            default:
                throw new \Exception('Invalid DB_CONNECTION');
                break;
        }

        $this->dbh = new PDO($dsn, $username, $password, $options);
    }

    /**
     * Get all rows from query result
     *
     * @param string $query
     * @param array $params
     *
     * @return array
     */
    public function select($query, array $params = [])
    {
        $this->run($query, $params);

        return $this->stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    /**
     * Get one row from query result
     *
     * @param string $query
     * @param array $params
     *
     * @return array
     */
    public function selectOne($query, array $params = [])
    {
        $this->run($query, $params);

        return $this->stmt->fetch(PDO::FETCH_ASSOC);
    }

    /**
     * Insert row into table
     *
     * @param string $query
     * @param array $params
     *
     * @return int Number of affected rows
     */
    public function insert($query, array $params = [])
    {
        $this->run($query, $params);

        return $this->stmt->rowCount();
    }

    /**
     * Delete row from table
     *
     * @param string $query
     * @param array $params
     *
     * @return int Number of affected rows
     */
    public function delete($query, array $params = [])
    {
        $this->run($query, $params);

        return $this->stmt->rowCount();
    }

    /**
     * Update row in table
     *
     * @param string $query
     * @param array $params
     *
     * @return int Number of affected rows
     */
    public function update($query, array $params = [])
    {
        $this->run($query, $params);

        return $this->stmt->rowCount();
    }

    /**
     * Prepare & execute query
     *
     * @param string $query SQL query with optional placeholders (see below)
     * @param array $params Array of placeholders with corresponding values ([':placeholder' => $value])
     *
     * @throws PDOException If dbh cannot prepare statement. Depends on ERRMODE_EXCEPTION
     */
    private function run($query, array $params)
    {
        $this->stmt = $this->dbh->prepare($query);

        foreach ($params as $key => &$value) {
            $this->stmt->bindParam($key, $value);
        }

        $this->stmt->execute();
    }
}
