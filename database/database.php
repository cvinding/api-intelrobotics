<?php
namespace DATABASE;

/**
 * Class Database
 * @package DATABASE
 * @author Christian Vinding Rasmussen
 * Database class for creating easy database queries.
 * //TODO: make ORM maybe?
 */
class Database {

    /**
     * This is the PDO connection object
     * @var \PDO $pdo
     */
    private $pdo = NULL;

    /**
     * This is the PDO statement object
     * @var \PDOStatement $query
     */
    private $query = NULL;

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
            // Create the database connection
            $this->pdo = new \PDO(
                "mysql:host={$config['HOSTNAME']};port={$config['PORT']};dbname={$config['DATABASE']};charset={$config['CHARSET']}",
                $config['USERNAME'],
                $config['PASSWORD'],
                $config['OPTIONS']
            );

        } catch (\PDOException $PDOException) {
            exit($PDOException);
        }

        // Return true if it came so far ;)
        return true;
    }

    /**
     * query() is used in conjunction with a fetch*() function
     * @param string $query
     * @param array $bindable
     * @return Database
     */
    public function query(string $query, array $bindable = []) : \DATABASE\Database {
        try {
            // Prepare the query
            $this->query = $this->pdo->prepare($query);

            // Check if there is any bindable variables and use them if there are
            if(isset($bindable) && !empty($bindable)){
                // Execute with parameters
                $this->query->execute($bindable);
            } else {
                // Execute without parameters
                $this->query->execute();
            }

        } catch (\PDOException $PDOException) {
            exit($PDOException);
        }

        // Return the instance of this object \DATABASE\Database
        return $this;
    }

    /**
     * fetchArray() returns the fetched data as an ASSOCIATIVE array
     * @return array $data
     */
    public function fetchArray() : array {
        // Fetch the data
        $data = $this->query->fetchAll();

        // Set the query to NULL
        $this->query = NULL;

        // Return the data
        return $data;
    }

    /**
     * affectedRows() return all rows affected by an DELETE, INSERT or UPDATE statement
     * @return int
     */
    public function affectedRows() : int {
        return $this->query->rowCount();
    }

    /**
     * Database destructor.
     * Always close the connection after the object is out of scope
     */
    public function __destruct(){
        $this->pdo = NULL;
    }

}