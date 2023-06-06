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

$conn = (new DbController())->connect();
$showErrorMessage = false;

if (isset($_POST['registerPost'])) {
	if (isset($_POST['title']) && isset($_POST['body'])) {
		$title = $_POST['title'];
		$body = $_POST['body'];

		$sql = "INSERT INTO posts (title, body) values ('$title', '$body')";

		if ($conn->query($sql) == true) {

			$sql = "SELECT * FROM posts where title = '$title' and body = '$body'";
			$result = $conn->query($sql);

			$post = $result->fetch_assoc()['id'];

			$sql = "UPDATE posts SET father_id = '$post', type = 1, main_status = 1 where id = '$post'";
			$conn->query($sql);

			header("Location: list.php");
	  		exit();
		}
	} else {
		$showErrorMessage = true;
	}
}


?>

<!DOCTYPE html>
<html lang="en">
<head>
	<meta charset="UTF-8">
	<meta name="viewport" content="width=device-width, initial-scale=1.0">
	<title>Registrar post</title>
	<link rel="stylesheet" href="css/styles.css">
</head>
<body>
	
	<main class="container">
		<div class="title">
			<h1>Regitrar Post</h1>
		</div>

		<div>
			<?php if ($showErrorMessage) { ?>

				<p class="error-message">Revisa que los campos se encuentren llenos</p>

			<?php } ?>
		</div>

		<form action="" method="POST">
	      	<div class="input-group">
	      		<label for="title" class="lb-input">Título del post *</label>
	      		<input type="text" id="title" placeholder="Título" required name="title" />
	      	</div>

			<div class="input-group">
	      		<label for="body" class="lb-input">Cuerpo del post *</label>
	      		<input type="text" id="body" name="body" placeholder="Contraseña" required />
	      	</div>
	      	
	      	<button type="submit" class="btn-green" name="registerPost">Guardar</button>
	    </form>
	</main>

</body>
</html>