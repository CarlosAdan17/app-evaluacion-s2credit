<?php

require "Controllers/DbController.php";
require "Controllers/PostController.php";

// Iniciar la sesión
session_start();

// Verificar si la sesión está activa
if (session_status() === PHP_SESSION_ACTIVE) {

	if (isset($_SESSION["username"])) {
		$username = $_SESSION["username"];
	} else {
		header("Location: index.php");
 		exit();	
	}
} else {
  	// La sesión no está activa, redirigir al usuario a la página de index
  	header("Location: index.php");
  	exit();
}

$id = null; 
$conn = (new DbController())->connect();

if (isset($_GET['id'])) {
	$id = $_GET['id'];

	$sql = "DELETE FROM posts where father_id = '$id'";

	if ($conn->query($sql) == true) {
		header("Location: list.php");
  		exit();
	}
}

?>