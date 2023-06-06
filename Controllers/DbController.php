<?php

class DbController	
{
	function connect() {
		$servername = "127.0.0.1";
	    $username = "root";
	    $password = "";
	    $database = "s2credit";

	    // Crear una conexión
	    $conn = new mysqli($servername, $username, $password, $database);

	    // Verificar si la conexión fue exitosa
	    if ($conn->connect_error) {
	        die("Error en la conexión: " . $conn->connect_error);
	    }

	    return $conn;
	}
}