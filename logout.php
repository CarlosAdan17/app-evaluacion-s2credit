<?php
// Iniciar la sesión
session_start();

// Destruir todas las variables de sesión
session_destroy();

// Redirigir al usuario a la página de login
header("Location: index.php");
exit();
