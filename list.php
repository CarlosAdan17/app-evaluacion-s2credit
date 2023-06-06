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

$sql = "SELECT * FROM posts";
$result = $conn->query($sql);

$canSeeTheBtnToRegister = $result->num_rows == 0;

$listPost = (new PostController())->getListPost($conn);

?>

<!DOCTYPE html>
<html lang="en">
<head>
	<meta charset="UTF-8">
	<meta name="viewport" content="width=device-width, initial-scale=1.0">
	<title>Listado de registros</title>

	<link rel="stylesheet" href="css/styles.css">
</head>
<body>
	
	<div class="header">
		<h1>
			Bienvenido <?php echo $_SESSION['username']; ?> 
		</h1>

		<div>
			<form action="logout.php" method="POST">
				<button type="submit" class="btn-logout">Cerrar sesión</button>
			</form>
		</div>
	</div>

	<?php if($canSeeTheBtnToRegister) { ?> 
	<form action="Controllers/controller.php" method="POST">
		<button type="submit" name="postsList" class="btn-pretty">Agregar registros a la db</button>
	</form>

	<?php  } else { ?> 


	<!-- Tabla de registros -->
	<div class="container-tb ">
		<div class="register">
			<a href="/register.php" class="btn-pretty">Registrar Post</a>
		</div>
		<table>
			<thead>
				<tr>
					<th>ID</th>
					<th>Título</th>
					<th>Contenido</th>
					<th>Opciones</th>
				</tr>
			</thead>
			<tbody>
				<?php 

				// Configuración de paginación
				$perPage = 15;
				$totalRegisters = count($listPost);
				$totalPages = ceil($totalRegisters / $perPage);

				// Obtener el número de página actual
				$currentPage = isset($_GET['page']) ? $_GET['page'] : 1;

				// Calcular el índice de inicio y fin del array para la página actual
				$start = ($currentPage - 1) * $perPage;
				$end = $start + $perPage - 1;
				if ($end >= $totalRegisters) {
				    $end = $totalRegisters - 1;
				}

				// Obtener los posts para la página actual
				$postsPage = array_slice($listPost, $start, $perPage);


				foreach ($postsPage as $post) { ?>
				<tr>
					<td data-label="ID"><?php echo $post['id'] ?></td>
					<td data-label="Título"><?php echo $post['title'] ?></td>
					<td data-label="Contenido"><?php echo $post['body'] ?></td>
					<td data-label="Opciones">
						<a href="/update.php?id=<?php echo $post['id'] ?>">Actualizar</a>
						<a href="/delete.php?id=<?php echo $post['id'] ?>">Eliminar</a>
					</td>
				</tr>
				<?php } ?> 
			</tbody>
	  	</table>

	  	<div class="pagination">
	  		<?php 

			echo "<br>";
			
			for ($i = 1; $i <= $totalPages; $i++) {
			    echo "<a href='?page=$i'>$i</a> ";
			}

			?>
	  	</div>		
	</div>
	<?php } ?> 

</body>
</html>