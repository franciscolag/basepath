<?php

require_once '../src/config/Db.php';
class Propietarios
{
    public function listarPropietarios()
    {
        $sql = "select propietarios.id as prop_id, 
        CONCAT(propietarios.nombres,' ',propietarios.apellidos) as nombre_compl,
        propietarios.nombres as nombre,propietarios.apellidos as apellido,
        propietarios.telefono as telefono, propietarios.num_id as num_id, 
        (select fecha from expiraciones_propietarios where id = prop_id) as fech_exp_id,
        sindicatos.nombre_corto as id_sindicato, propietarios.archivo_ine
        from propietarios  inner join sindicatos on sindicatos.id = propietarios.id_sindicato order by nombres;";
        try {
            $db = new db();
            $db = $db->conectionDb();
            $resultado = $db->query($sql);
            $i = 0;
            while ($fila = $resultado->fetch()) {
                $propietarios[$i]['id'] = $fila['prop_id'];
                $propietarios[$i]['nombre_compl'] = $fila['nombre_compl'];
                $propietarios[$i]['nombre'] = $fila['nombre'];
                $propietarios[$i]['apellido'] = $fila['apellido'];
                $propietarios[$i]['telefono'] = $fila['telefono'];
                $propietarios[$i]['num_id'] = $fila['num_id'];
                $propietarios[$i]['fech_exp_id'] = $fila['fech_exp_id'];
                $propietarios[$i]['id_sindicato'] = $fila['id_sindicato'];
                if ($fila['archivo_ine'] != null) {
                    $propietarios[$i]['archivo_ine'] = '✓';
                } else {
                    $propietarios[$i]['archivo_ine'] = 'N/A';
                }
                $i++;
            }
            return json_encode($propietarios);
        } catch (PDOException $err) {
            // Imprime error de conexión
            return json_encode("400");
        }
    }

    public function insertaPropietario($nomProp, $appProp, $telProp, $numIdProp, $sinProp, $imgFileIdProp, $fecExpId)
    {
        try {
            $sql = "INSERT INTO `propietarios`(`nombres`, `apellidos`, `telefono`, `num_id`,`id_sindicato`
                    , `archivo_ine`) VALUES (:nombres, :apellidos, :telefono, :num_id, :id_sindicato, :file_id_prop);";
            $db = new db();
            $db = $db->conectionDb();
            $sentencia = $db->prepare($sql);
            $sentencia->bindParam(':nombres', $nomProp, PDO::PARAM_STR);
            $sentencia->bindParam(':apellidos', $appProp, PDO::PARAM_STR);
            $sentencia->bindParam(':telefono', $telProp, PDO::PARAM_STR);
            $sentencia->bindParam(':num_id', $numIdProp, PDO::PARAM_STR);
            $sentencia->bindParam(':id_sindicato', $sinProp, PDO::PARAM_INT);
            $sentencia->bindParam(':file_id_prop', $imgFileIdProp, PDO::PARAM_LOB);
            $sentencia->execute();
            $id = $this->idUltimoInsert();
            if ($this->insertaExpiracion($id, 15, $fecExpId) == false || $id == false) {
                return 501;
            }
            return  201;
        } catch (PDOException $err) {
            return 502;
        }
    }

    public function insertaExpiracion($id, $tipo, $fecha){
        try {
            $sql = "INSERT INTO expiraciones_propietarios(id_propietario, tipo, fecha) VALUES
                    (:id_propietario, :tipo, :fecha);";
            $db = new db();
            $db = $db->conectionDb();
            $sentencia = $db->prepare($sql);
            $sentencia->bindParam(':id_propietario', $id, PDO::PARAM_INT);
            $sentencia->bindParam(':tipo', $tipo, PDO::PARAM_INT);
            $sentencia->bindParam(':fecha', $fecha, PDO::PARAM_STR);
            $sentencia->execute();
            return true;
        } catch (PDOException $err) {
            return false;
        }
    }

    public function idUltimoInsert(){
        try {
            $sql = "SELECT id from propietarios order by id DESC LIMIT 1;";
            $db = new db();
            $db = $db->conectionDb();
            $resultado  = $db->query($sql);
            while ($fila = $resultado->fetch()) {
                return $fila['id'];
            }
            return true;
        } catch (PDOException $err) {
            // Imprime error de conexión
            return false;
        }
    }
}
