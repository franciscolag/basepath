<?php
require_once '../src/Models/Usuarios.php';
//require_once '../Models/Usuarios.php';

class Auth{
    public function autenticar($user, $pass){
        $objUsuario = new Usuarios();
        $nombre = $objUsuario->datos($user);
        if ($nombre == '401' || $nombre == '500') {
            return '401';
        } else {
            $config['version'] = '1.0';
            $config['urlLdap'] = 'ldap://172.28.150.1';
            $config['baseSearch'] = 'OU=Utilizadores,OU=OU_MEXICO,DC=mexico,DC=mota-engil,DC=pt';
            // conexión al servidor LDAP
            $ldapconn = ldap_connect($config['urlLdap']) or die("Could not connect to LDAP server.");
            $config['usernameConsultaLdap'] = 'CN='.$nombre.',OU=Utilizadores,OU=OU_MEXICO,DC=mexico,DC=mota-engil,DC=pt';
            $config['passwordConsultaLdap'] = $pass;

            if ($ldapconn) {
                // realizando la autenticación
                try {
                    $ldapbind = @ldap_bind($ldapconn, $config['usernameConsultaLdap'], $config['passwordConsultaLdap']);

                    // verificación del enlace
                    if ($ldapbind) {
                        $serch = ldap_search($ldapconn, $config['baseSearch'], "sAMAccountName=*");
                        // verificación del enlace
                        return "200";
                    } else {
                        return "401";
                    }
                }catch(Exception $e){
                    return "400";
                }
            }
        }
    }
}