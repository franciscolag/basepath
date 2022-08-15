<?php
class Sindicatos{
    public function listarSindicatos(){
        $sql = "Select * from sindicatos;";
        try {
            $db = new db();
            $db = $db->conectionDb();
            $resultado = $db->query($sql);
            $i = 0;
            while ($fila = $resultado->fetch()) {
                $sindicatos[$i]['id'] = $fila['id'];
                $sindicatos[$i]['razon_social'] = $fila['razon_social'];
                $sindicatos[$i]['nombre_corto'] = $fila['nombre_corto'];
                $sindicatos[$i]['direccion'] = utf8_encode($fila['direccion'] . " " . $fila['colonia']);
                $sindicatos[$i]['ciudad'] = $fila['ciudad'];
                $sindicatos[$i]['estado'] = $fila['estado'];
                $sindicatos[$i]['rfc'] = $fila['rfc'];
                $i++;
            }
            return json_encode($sindicatos);
        } catch (PDOException $err) {
            // Imprime error de conexión
            return json_encode("400");
        }
    }

    public function insertaSindicatos($razSocial,$nomCorto,$direc,$colonia,$cp,$municipio,$estado,$tipoRfc,$rfc,$tel,$email){
        try {
            $sql = "INSERT into sindicatos (razon_social, nombre_corto, direccion,colonia, codigo_postal,
                        ciudad, estado, tipo_sindicato, rfc, telefono, email) VALUES 
                        (:razon_social, :nombre_corto, :direccion, :colonia, :codigo_postal, :ciudad, :estado,
                        :tipo_sindicato, :rfc, :telefono, :email);";
            $db = new db();
            $db = $db->conectionDb();
            $sentencia = $db->prepare($sql);
            $sentencia->bindParam(':razon_social', $razSocial, PDO::PARAM_STR);
            $sentencia->bindParam(':nombre_corto', $nomCorto, PDO::PARAM_STR);
            $sentencia->bindParam(':direccion', $direc, PDO::PARAM_STR);
            $sentencia->bindParam(':colonia', $colonia, PDO::PARAM_STR);
            $sentencia->bindParam(':codigo_postal', $cp, PDO::PARAM_STR);
            $sentencia->bindParam(':ciudad', $municipio, PDO::PARAM_STR);
            $sentencia->bindParam(':estado', $estado, PDO::PARAM_STR);
            $sentencia->bindParam(':tipo_sindicato', $tipoRfc, PDO::PARAM_INT);
            $sentencia->bindParam(':rfc', $rfc, PDO::PARAM_STR);
            $sentencia->bindParam(':telefono', $tel, PDO::PARAM_STR);
            $sentencia->bindParam(':email', $email, PDO::PARAM_STR);
            $sentencia->execute();
                return  201;
        } catch (PDOException $err) {
            return 500;

        }
    }

}