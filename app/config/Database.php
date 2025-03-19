<?php
    class Database{
        private static $instance = null;
        private $conn = null;
        private $host = 'localhost';
        private $user = 'root';
        private $pass = 'root';
        private $name = 'nba-stats';

        private function __construct(){
            try{
                $this->conn = new PDO(
                    "mysql:host=$this->host;dbname=$this->name",
                    $this->user,
                    $this->pass,
                    [PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION]
                );
            }catch(PDOException $err){
                die("Conn failed". $err->getMessage());


            }
        }

        public static function getInstance(){
            if(!self::$instance){
                self::$instance = new Database();

            }
            return self::$instance;
        }

        public function getConnection(){
            return $this ->conn;

        }
    }
?>