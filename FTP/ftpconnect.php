<?php
//CONSTANTES
define("SERVER",""); //IP del servidor
define("PORT",""); //Puerto
define("USER",""); //Usuario
define("PASSWORD",""); //Contraseña

//Conexion al servidor
$id_ftp=ftp_connect(SERVER,PORT) or die("No se pudo conextar");
ftp_login($id_ftp,USER,PASSWORD);
ftp_pasv($id_ftp,true);

//Cambiar directorio
ftp_chdir($id_ftp, "/IN");

//Directorio actual
$Dir=ftp_pwd($id_ftp);

//Directorios
$listDir = ftp_nlist($id_ftp, '-la');
print_r($listDir);

ftp_quit($id_ftp); //Cerrar sesion
