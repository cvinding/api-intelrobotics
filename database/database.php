<?php
namespace DATABASE;

/**
 * Class Database
 * @package DATABASE
 * @author Christian Vinding Rasmussen
 * //TODO: description needed
 */
class Database implements \DATABASE\_IMPLEMENTS\Database {

    /**
     * This is the PDO object
     * @var $pdo
     */
    private $pdo;

    /**
     * Database constructor.
     */
    public function __construct(){
        // Auto connect to the database
        $this->connect(require "../config/database.php");
    }

    /**
     * connect() opens a database connection
     * @param array $config
     * @return bool
     */
    private function connect(array $config) : bool {
        try {
            $this->pdo = new \PDO(
                "mysql:host={$config['HOSTNAME']};dbname={$config['DATABASE']}:charset={$config['CHARSET']}",
                $config['USERNAME'],
                $config['PASSWORD'],
                $config['OPTIONS']
            );

        } catch (\PDOException $exception) {
            exit($exception);
        }
        return true;
    }

    /**
     * getPDO() returns the PDO object
     * @return \PDO
     */
    protected function getPDO() : \PDO {
        return $this->pdo;
    }

    /**
     * Database destructor.
     * Always close the connection after the object is out of scope
     */
    public function __destruct(){
        $this->pdo = NULL;
    }

}