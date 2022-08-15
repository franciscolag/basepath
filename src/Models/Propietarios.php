<?php
require_once '../src/config/Db.php';
class Propietarios{

    public function listarPropietarios(){
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
                if ($fila['archivo_ine'] != null)
                    $propietarios[$i]['archivo_ine'] = '✓';
                else
                    $propietarios[$i]['archivo_ine'] = 'N/A';
                $i++;
            }
            return json_encode($propietarios);
        } catch (PDOException $err) {
            // Imprime error de conexión
            return json_encode("400");
        }
    }
}