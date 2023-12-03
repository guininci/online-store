<?php
require_once(__DIR__.'/../config/mysql.config.php');

class DatabaseSingleton
{
    private static $instance = null;
    private $connection;

    private function __construct($servername = MYSQL_HOST, $username = MYSQL_USER, $password = MYSQL_PASSWORD, $database = MYSQL_DATABASE)
    {
        // Create connection
        $this->connection = new mysqli($servername, $username, $password, $database);

        // Check connection
        if ($this->connection->connect_error) {
            die("Connection failed: " . $this->connection->connect_error);
        }

        // Set charset to UTF-8
        $this->connection->set_charset("utf8");
    }

    public static function getInstance()
    {
        if (self::$instance === null) {
            self::$instance = new self();
        }

        return self::$instance;
    }

    public function getConnection()
    {
        return $this->connection;
    }

    // Prevent cloning of the instance
    private function __clone()
    {
    }

    // Prevent unserialization of the instance
    private function __wakeup()
    {
    }
}

// Get the database connection instance
$conn = DatabaseSingleton::getInstance()->getConnection();
?>