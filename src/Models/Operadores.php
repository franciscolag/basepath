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
}
