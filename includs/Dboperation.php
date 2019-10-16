<?php

class Dboperation{
    private $con;
    function _construct(){
        require_once dirname(__FILE__) . '/Dbconnect.php';

        $db = new Dbconnect;
        $this->con = $db->connect();
    }
    public function createUser($email, $password, $name, $school){
     if(!$this->isEmailExist($email)){
        $stmt = $this->con->prepare("INSERT INTO users (email, password, name, school) VALUES(?, ?, ?, ?)");
        $stmt->bind_param("ssss", $email, $password, $name, $school);
        if($stmt->execute()){
         return USER_CREATED;
        }else{
         return USER_FAILURE;
        }
     }
     return USER_EXIST;
    }
    private function isEmailExist($email){
        $stmt = $this->con->prepare("SELECT id FROM users WHERE email = ?");
        $stmt->bind_param("s", $email);
        $stmt->execute();
        $stmt->store_result();
        return $stmt->num_rows >0;
    }
}