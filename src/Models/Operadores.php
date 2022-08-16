<?php

class Operadores
{
    public function listarOperadores(){
            $sql = "select operadores.id as id_ope, concat(operadores.nombres, ' ', operadores.apellidos) as nombres
       , operadores.nss,operadores.num_id,nombres as nmb,apellidos,
       (select fecha from expiraciones_operadores where tipo = 13 and id_operador = id_ope) as exp_id, operadores.num_lic,
       (select fecha from expiraciones_operadores where tipo = 16 and id_operador = id_ope) as exp_lic, operadores.num_cert_med,
       (select fecha from expiraciones_operadores where tipo = 14 and id_operador = id_ope) as exp_cert_med, archivo_seg_soc, archivo_ine, archivo_lic_cond, archivo_cert_med
       from operadores order by operadores.id;";
        try {
            $db = new db();
            $db = $db->conectionDb();
            $resultado = $db->query($sql);
            $i = 0;
            while ($fila = $resultado->fetch()) {
                $operadores[$i]['id'] = $fila['id_ope'];
                $operadores[$i]['nombres'] = $fila['nombres'];
                $operadores[$i]['nmb'] = $fila['nmb'];
                $operadores[$i]['apellidos'] = $fila['apellidos'];
                $operadores[$i]['nss'] = $fila['nss'];
                $operadores[$i]['num_id'] = $fila['num_id'];
                $operadores[$i]['fecha_exp_id'] = $fila['exp_id'];
                $operadores[$i]['num_lic'] = $fila['num_lic'];
                $operadores[$i]['fecha_exp_lic'] = $fila['exp_lic'];
                $operadores[$i]['num_cert_med'] = $fila['num_cert_med'];
                $operadores[$i]['fecha_cert_med'] = $fila['exp_cert_med'];
                //Archivos de Documentos/////////////////////////
                if ($fila['archivo_seg_soc'] != null) {
                    $operadores[$i]['archivo_seg_soc'] = '✓';
                } else {
                    $operadores[$i]['archivo_seg_soc'] = 'N/A';
                }
                if ($fila['archivo_ine'] != null) {
                    $operadores[$i]['archivo_ine'] = '✓';
                } else {
                    $operadores[$i]['archivo_ine'] = 'N/A';
                }
                if ($fila['archivo_lic_cond'] != null) {
                    $operadores[$i]['archivo_lic_cond'] = '✓';
                } else {
                    $operadores[$i]['archivo_lic_cond'] = 'N/A';
                }
                if ($fila['archivo_cert_med'] != null) {
                    $operadores[$i]['archivo_cert_med'] = '✓';
                } else {
                    $operadores[$i]['archivo_cert_med'] = 'N/A';
                }
                $i++;
            }
            return json_encode($operadores);
        } catch (PDOException $err) {
            // Imprime error de conexión
            return json_encode("400");
        }
    }

    public function idUltimoInsert(){
        try {
            $sql = "SELECT id from operadores order by id DESC LIMIT 1;";
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

    public function insertaExpiracion($id, $tipo, $fecha){
        try {
            $sql = "INSERT INTO expiraciones_operadores(id_operador, tipo, fecha) VALUES
                    (:id_operador, :tipo, :fecha);";
            $db = new db();
            $db = $db->conectionDb();
            $sentencia = $db->prepare($sql);
            $sentencia->bindParam(':id_operador', $id, PDO::PARAM_INT);
            $sentencia->bindParam(':tipo', $tipo, PDO::PARAM_INT);
            $sentencia->bindParam(':fecha', $fecha, PDO::PARAM_STR);
            $sentencia->execute();
            return true;
        } catch (PDOException $err) {
            return false;
        }
    }

    public function insertaOperadores($nombre, $ape, $nss, $identificacion, $numLic, $numCertMed, $img){
        try {
            $sql = "INSERT INTO `operadores`(`nombres`, `apellidos`, `nss`, `num_id`, `num_lic`, `num_cert_med`
                                            , `archivo_seg_soc`, `archivo_ine`, `archivo_lic_cond`, `archivo_cert_med`) VALUES 
                    (:nombres, :apellidos, :nss, :num_id,  :num_lic, :num_cert_med,:file_nss, :file_ine,
                     :file_lic_cond, :file_cert_med);";
            $db = new db();
            $db = $db->conectionDb();
            $sentencia = $db->prepare($sql);
            $sentencia->bindParam(':nombres', $nombre, PDO::PARAM_STR);
            $sentencia->bindParam(':apellidos', $ape, PDO::PARAM_STR);
            $sentencia->bindParam(':nss', $nss, PDO::PARAM_STR);
            $sentencia->bindParam(':num_id', $identificacion, PDO::PARAM_STR);
            $sentencia->bindParam(':num_lic', $numLic, PDO::PARAM_STR);
            $sentencia->bindParam(':num_cert_med', $numCertMed, PDO::PARAM_STR);
            $sentencia->bindParam(':file_nss', $imgFileNSS, PDO::PARAM_LOB);
            $sentencia->bindParam(':file_ine', $imgFileIdOpe, PDO::PARAM_LOB);
            $sentencia->bindParam(':file_lic_cond', $imgFileLicOpe, PDO::PARAM_LOB);
            $sentencia->bindParam(':file_cert_med', $imgFileCert, PDO::PARAM_LOB);
            $sentencia->execute();
            $id = $this->idUltimoInsert();
            if ($this->insertaExpiracion($id, 13, $fecExpId) == false ||
                $this->insertaExpiracion($id, 14, $fecExpId) == false||
                $this->insertaExpiracion($id, 16, $fecExpId) == false||$id == false) {
                return 501;
            }
            return  201;
        } catch (PDOException $err) {
            return 502;
        }
    }
}
