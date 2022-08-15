<?php
class CentrosCosto{
    public function listarCC(){
        $sql = "Select id,cc,nombre from centros_costo where ver_topo =1 order by cc";
        try {
            $db = new db();
            $db = $db->conectionDb();
            $resultado = $db->query($sql);
            $i = 0;
            while ($fila = $resultado->fetch()) {
                $centroscostos[$i]['id'] = $fila['id'];
                $centroscostos[$i]['cc'] = $fila['cc'];
                $centroscostos[$i]['nombre'] = utf8_encode($fila['nombre']);
                $i++;
            }
            return json_encode($centroscostos);
        } catch (PDOException $err) {
            // Imprime error de conexión
            return json_encode("400");
        }
    }

}