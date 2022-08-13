<?php
require_once '../src/config/Db.php';
//require_once '../config/Db.php';

class Usuarios{
    public function datos($nombre){
        try {
            $sql = "Select nombre from usuarios where uid_ldap = ?";
            $db = new db();
            $db = $db->conectionDb();
            $sentencia = $db->prepare($sql);
                while ($fila = $sentencia->fetch()) {
                    if(isset($fila['nombre']))
                        return strval($fila['nombre']);
                    else
                        return 500;
                }
        } catch (PDOException $err) {
            // Imprime error de conexión
            return 500;
            //return 'error catch';
            //echo "ERROR: No se pudo conectar a la base de datos: " . $err->getMessage();
        }
    }
}