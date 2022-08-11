<?php
require_once '../src/config/Db.php';
class Camiones
{

    public function listarCamiones(){
        $sql="select camiones.id as c_id, camiones.num_eco, camiones.placa, camiones.marca, camiones.modelo, camiones.year, camiones.cap_carga, camiones.peso, camiones.cap_comb,
        camiones.folio_fact, camiones.folio_cert_op, camiones.num_tarj_circ, camiones.num_poli_seg, camiones.archivo_fot_post, centros_costo.cc, camiones.archivo_factura,
        camiones.archivo_cert_ope, camiones.archivo_tarj_circ, camiones.archivo_pol_seg, camiones.archivo_fot_front, camiones.archivo_fot_post, camiones.archivo_fot_lat,
        camiones.archivo_fot_placa, camiones.archivo_fic_cub, (select fecha from expiraciones_camiones where id = c_id and tipo = 5 ) as fec_tarj,
        (select fecha from expiraciones_camiones where id = c_id and tipo = 6 ) as fec_pol 
        from camiones inner join centros_costo on camiones.cc = centros_costo.id order by num_eco;";
        try {
            $db = new db();
            $db = $db->conectionDb();
            $resultado  = $db->query($sql);
            $i = 0;
            while ($fila = $resultado->fetch()) {
                $camiones[$i]['id'] = $fila['c_id'];
                $camiones[$i]['num_eco'] = $fila['num_eco'];
                $camiones[$i]['placa'] = $fila['placa'];
                $camiones[$i]['marca'] = $fila['marca'];
                $camiones[$i]['modelo'] = $fila['modelo'];
                $camiones[$i]['year'] = $fila['year'];
                $camiones[$i]['cc'] = $fila['cc'];
                $camiones[$i]['cap_carga'] = $fila['cap_carga'];
                $camiones[$i]['peso'] = $fila['peso'];
                $camiones[$i]['cap_comb'] = $fila['cap_comb'];
                $camiones[$i]['folio_fact'] = $fila['folio_fact'];
                $camiones[$i]['folio_cert_op'] = $fila['folio_cert_op'];
                $camiones[$i]['num_tarj_circ'] = $fila['num_tarj_circ'];
                $camiones[$i]['num_poli_seg'] = $fila['num_poli_seg'];
                $camiones[$i]['fec_tarj'] = $fila['fec_tarj'];
                $camiones[$i]['fec_pol'] = $fila['fec_pol'];
                //Archivos de Documentos/////////////////////////
                if ($fila['archivo_factura'] != null)
                    $camiones[$i]['archivo_factura'] = '✓';
                else
                    $camiones[$i]['archivo_factura'] = 'N/A';
                if ($fila['archivo_cert_ope'] != null)
                    $camiones[$i]['archivo_cert_ope'] = '✓';
                else
                    $camiones[$i]['archivo_cert_ope'] = 'N/A';
                if ($fila['archivo_tarj_circ'] != null)
                    $camiones[$i]['archivo_tarj_circ'] = '✓';
                else
                    $camiones[$i]['archivo_tarj_circ'] = 'N/A';
                if ($fila['archivo_pol_seg'] != null)
                    $camiones[$i]['archivo_pol_seg'] = '✓';
                else
                    $camiones[$i]['archivo_pol_seg'] = 'N/A';
                ///////Archivos de fotos///////////////////////
                if ($fila['archivo_fot_front'] != null)
                    $camiones[$i]['fot_front'] = '✓';
                else
                    $camiones[$i]['fot_front'] = 'N/A';
                if ($fila['archivo_fot_post'] != null)
                    $camiones[$i]['fot_post'] = '✓';
                else
                    $camiones[$i]['fot_post'] = 'N/A';
                if ($fila['archivo_fot_lat'] != null)
                    $camiones[$i]['fot_lat'] = '✓';
                else
                    $camiones[$i]['fot_lat'] = 'N/A';
                if ($fila['archivo_fot_placa'] != null)
                    $camiones[$i]['fot_placa'] = '✓';
                else
                    $camiones[$i]['fot_placa'] = 'N/A';
                if ($fila['archivo_fic_cub'] != null)
                    $camiones[$i]['fic_cub'] = '✓';
                else
                    $camiones[$i]['fic_cub'] = 'N/A';
                $i++;
            }
            return json_encode($camiones);
        } catch (PDOException $err) {
            // Imprime error de conexión
            return json_encode("400");
        }
    }
}