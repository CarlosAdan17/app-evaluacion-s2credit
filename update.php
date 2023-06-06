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

	$sql = "SELECT * FROM posts where id = '$id'";
	$result = $conn->query($sql);

	$post = $result->fetch_assoc();
}

if (isset($_POST['updatePost'])) {
	$title = $_POST['title'];
	$body = $_POST['body'];

	$sql = "UPDATE posts SET title = '$title', body = '$body' where id = '$id'";

	if ($conn->query($sql) == true) {
		header("Location: list.php");
  		exit();
	}
}


?>

<!DOCTYPE html>
<html lang="en">
<head>
	<meta charset="UTF-8">
	<meta name="viewport" content="width=device-width, initial-scale=1.0">
	<title>Actualizar registro</title>
	<link rel="stylesheet" href="css/styles.css">
</head>
<body>
	
	<main class="container">
		<div class="title">
			<h1>Actualizar registro</h1>
		</div>

		<form action="" method="POST">
	      	<div class="input-group">
	      		<label for="title" class="lb-input">Título del post *</label>
	      		<input type="text" id="title" placeholder="Título" value="<?php echo $post['title'] ?>" required name="title" />
	      	</div>

			<div class="input-group">
	      		<label for="body" class="lb-input">Cuerpo del post *</label>
	      		<input type="text" id="body" name="body" placeholder="Contraseña" value="<?php echo $post['body'] ?>" required />
	      	</div>
	      	
	      	<button type="submit" class="btn-green" name="updatePost">Guardar</button>
	    </form>
	</main>

</body>
</html>