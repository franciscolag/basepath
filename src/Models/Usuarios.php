<?php
require_once '../src/config/Db.php';

class Usuarios
{
    public function datos($nombre)
    {
        try {
            $sql = "Select nombre from usuarios where uid_ldap = ?";
            $db = new db();
            $db = $db->conectionDb();
            $resultado = $db->query($sql);
            if ($resultado->execute(array($nombre))) {
                while ($fila = $resultado->fetch()) {
                    return json_encode(202);
                }
            } else {
                return json_encode(401);
            }
        } catch (PDOException $err) {
            // Imprime error de conexión
            echo "ERROR: No se pudo conectar a la base de datos: " . $err->getMessage();
        }
    }
}