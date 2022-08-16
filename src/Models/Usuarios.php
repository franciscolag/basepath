<?php
require_once '../src/config/Db.php';

class Usuarios{
    public function datos($nombre){
        try {
            $sql = "Select nombre from usuarios where uid_ldap = :nombre and usuarios.tipo_usuario>0;";
            $db = new db();
            $db = $db->conectionDb();
            $sentencia = $db->prepare($sql);
            $sentencia->bindParam(':nombre',$nombre,PDO::PARAM_STR);
            $sentencia->execute();
                while ($fila = $sentencia->fetch()) {
                        return $fila['nombre'];
                }
            return "500";
        } catch (PDOException $err) {
            // Imprime error de conexión
            return "500";
            //return 'error catch';
            //echo "ERROR: No se pudo conectar a la base de datos: " . $err->getMessage();
        }
    }

    public function tipo($nombre){
        try {
            $sql = "Select tipo_usuario from usuarios where uid_ldap = :nombre;";
            $db = new db();
            $db = $db->conectionDb();
            $sentencia = $db->prepare($sql);
            $sentencia->bindParam(':nombre',$nombre,PDO::PARAM_STR);
            $sentencia->execute();
            while ($fila = $sentencia->fetch()) {
                return  json_encode($fila['tipo_usuario']);
            }
            return json_encode(500);
        } catch (PDOException $err) {
            // Imprime error de conexión
            return json_encode(500);
            //return 'error catch';
            //echo "ERROR: No se pudo conectar a la base de datos: " . $err->getMessage();
        }
    }
}