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

$listPost = [];

if (isset($_GET['id'])) {
	$id = $_GET['id'];

	$sql = "SELECT * FROM posts where id = '$id'";
	$result = $conn->query($sql);
	
	$post = $result->fetch_assoc();

	if ($post['father_id'] == null) {
		$sql = "SELECT * FROM posts where father_id = '$id'";
	} else {
		$postId = $post['father_id'];
		$sql = "SELECT * FROM posts where father_id = '$postId'";
	}
	$result = $conn->query($sql);

	// Verificar si hay resultados
    if ($result->num_rows > 0) {
        // Array para almacenar los resultados
        $listPost = array();

        // Obtener cada fila de resultados como un arreglo asociativo
        while ($row = $result->fetch_assoc()) {
            $listPost[] = $row;
        }
    }

	if ($post == null) {
		header("Location: list.php");
  		exit();
	}

}

if (isset($_POST['updatePost'])) {
	$title = $_POST['title'];
	$body = $_POST['body'];

	if ($post['father_id'] == $post['id']) {

		if ($post['title'] != $title && $post['body'] != $body) {
			$sql = "SELECT * FROM posts where id = '$id'";
			$result = $conn->query($sql);
			
			$postOld = $result->fetch_assoc();

			$postTitle= $postOld['title'];
			$postBody= $postOld['body'];
			$postId = $postOld['id'];

			$sql = "INSERT INTO posts (title, body, father_id) values ('$postTitle', '$postBody', '$postId')";

			$conn->query($sql);

			$sql = "UPDATE posts SET title = '$title', body = '$body' where id = '$id'";
		}

		if ($conn->query($sql) == true) {
			header("Location: list.php");
	  		exit();
		}
	} else {
		$fatherId = $post['father_id'];
		$sql = "SELECT * FROM posts where id = '$fatherId'";
		$result = $conn->query($sql);
		
		$postOld = $result->fetch_assoc();

		$postTitle= $post['title'];
		$postBody= $post['body'];
		$postId = $post['id'];

		$sql = "UPDATE posts SET title = '$postTitle', body = '$postBody' where id = " . $postOld['id'];
		$conn->query($sql);

		$sql = "DELETE FROM posts where id = '$id'";

		if ($conn->query($sql) == true) {
			header("Location: list.php");
	  		exit();
		}
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
	      	
	      	<button type="submit" class="btn-green" name="updatePost">
	      		
	      		<?php 

	      			if ($post['father_id'] != $post['id']) {
	      				echo "Retomar este registro";
	      			} else {
	      				echo "Actualizar";
	      			}

	      		?>

	      	</button>
	    </form>


	   <div>
	   		<h3 class="textHistory">Historial de cambios</h3>

   			<?php foreach ($listPost as $listPostU) { ?>
   				<div class="title-post"><p><?php echo $listPostU['title']; ?></p></div>
   				<div class="body-post"><p><?php echo $listPostU['body']; ?></p></div>

   				<?php if($listPostU['id'] != $id) { ?>
   				<div>
   					<a href="/update.php?id=<?php echo $listPostU['id'] ?>">Retormar registro</a>
   				</div>
   				<?php } ?>

   				<hr>
   			<?php } ?>
	   </div>
	</main>

</body>
</html>