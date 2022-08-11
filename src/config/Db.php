<?php
class db
{
    private $dbHost = '172.28.150.7:3306';
    private $dbUser = 'externo';
    private $dbPass = 'S0p0rt310a.';
    private $dbName = 'topografia';

        public function conectionDb(){
            $mysqlConnect = "mysql:host=$this->dbHost;dbname=$this->dbName";
            $dbConnection = new PDO($mysqlConnect, $this->dbUser, $this->dbPass);
            $dbConnection->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
            return $dbConnection;
        }

    }