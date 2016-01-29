<?php

class Module {

    private $db;

    public function getDatabase() {
        if($this->db == null)
            $this->initializeDatabase();

        return $this->db;
    }

    private function initializeDatabase() {
        $options = array(PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_OBJ,
            PDO::ATTR_ERRMODE => PDO::ERRMODE_WARNING);

        $this->db = new PDO('mysql' . ':host=' . dbHost . ';dbname=' . dbName, dbUser, dbPass, $options);
    }

    public function isVerified() {
        session_start();
        if(isset($_SESSION['time']) && (time() - $_SESSION['time']) <= 120)
            return true;

        session_unset();
        session_destroy();
        return false;
    }

}