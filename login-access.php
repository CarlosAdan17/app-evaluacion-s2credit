<?php
// Iniciar la sesión
session_start();

// Verificar si se ha enviado el formulario
if ($_SERVER["REQUEST_METHOD"] == "POST") {
  // Obtener los datos del formulario
  $username = $_POST["username"];
  $password = $_POST["password"];

  // Verificar que las credenciales coincidan
  if ($username === "s2credit" && md5($password) === "bd738dc205552b803e57ee1b9f528259") {

  	// Guardamos el nombre en la sesión 
  	$_SESSION["username"] = $username;

    // Redirigir al usuario a la página de inicio
    header("Location: list.php");
    exit();
  } else {
    // Credenciales inválidas, mostrar un mensaje de error en la página de inicio
    header("Location: index.php?errorMessage=true");
    exit();
  }
}

