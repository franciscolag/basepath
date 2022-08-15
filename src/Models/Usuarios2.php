<?php

class Usuarios2{
    public function login($nombre,$password){
        $sql = "Select nombre, password from usuarios2 where nombre = :nombre and password = :password";
        try {
            $db = new db();
            $db = $db->conectionDb();
            $resultado=$db->prepare($sql);
            $resultado->bindParam(':nombre',$nombre,PDO::PARAM_STR);
            $resultado->bindParam(':password',$password,PDO::PARAM_STR);
            $resultado->execute();
            while ($resultado->fetch()) {
                return "202";
            }
            return "401";
        } catch (PDOException $err) {
            // Imprime error de conexión
            return "400";
        }
    }
}