<?php
require_once '../';
class Auth{

    public function autenticar($user, $pass){
        $config['version'] = '1.0';
        $config['urlLdap'] = 'ldap://172.28.150.1';
        $config['baseSearch'] = 'OU=Utilizadores,OU=OU_MEXICO,DC=mexico,DC=mota-engil,DC=pt';
        // conexión al servidor LDAP
        $ldapconn = ldap_connect($config['urlLdap']) or die("Could not connect to LDAP server.");
        $config['usernameConsultaLdap'] = 'CN=' . $user . ',OU=Utilizadores,OU=OU_MEXICO,DC=mexico,DC=mota-engil,DC=pt';
        $config['passwordConsultaLdap'] = $pass;

        if ($ldapconn) {
            // realizando la autenticación
            $ldapbind = ldap_bind($ldapconn, $config['usernameConsultaLdap'], $config['passwordConsultaLdap']) or die(header("Location: ../error.php"));

            // verificación del enlace
            if ($ldapbind) {
                $serch = ldap_search($ldapconn, $config['baseSearch'], "sAMAccountName=*") or die("Error in search query: " . ldap_error($ldapconn));
                // verificación del enlace
                return json_last_error(1);
            } else {
                return json_last_error(2);
            }
        }
    }
}