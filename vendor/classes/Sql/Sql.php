<?php

    namespace Sql;

    class Sql{

        const HOSTNAME = "127.0.0.1";
        const USERNAME = "root";
        const PASSWORD = "pedrinho10";
        const DBNAME = "db_ecommerce";

    


        private $conn;

        public function __construct(){
            $this->conn = new \PDO(
               "mysql:dbname=" . Sql::DBNAME. "; host=" . Sql::HOSTNAME,
               Sql::USERNAME,
               Sql::PASSWORD
            );
        }

        public function setParams($statment, $parameters = array()){
            foreach($parameters as $key => $value){
                $this->setParam($statment, $key, $value);
            }
        }

        public function setParam($statment, $key, $value){
            $statment->bindParam($key, $value);
        }

        public function myQuery($rawQuery, $params = array()){
           $stmt = $this->conn->prepare($rawQuery);

           $this->setParams($stmt, $params);

           $stmt->execute();

           return $stmt;
        }


       
        public function select($rawQuery, $params = array()):array{
            
           $stmt =  $this->myQuery($rawQuery, $params);
            
           return  $stmt->fetchAll(\PDO::FETCH_ASSOC);   
        }
    }
?>