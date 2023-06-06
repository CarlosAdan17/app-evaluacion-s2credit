<?php

// Iniciar la sesión
session_start();


// Verificar si la sesión está activa
if (isset($_SESSION["username"])) {
	header("Location: list.php");
	exit();
}

?>

<!DOCTYPE html>
<html lang="en">
<head>
	<meta charset="UTF-8">
	<meta name="viewport" content="width=device-width, initial-scale=1.0">
	<title>Evaluación de postulación - uso de servicio REST</title>

	<link rel="stylesheet" href="css/styles.css">
</head>
<body>
	<main>
		<section>
			<div class="container">
			    <h2>Iniciar sesión</h2>

			    <div>
			    	<?php
			    		if (isset($_GET['errorMessage'])) {
							$error = $_GET['errorMessage'];

							if ($error) {
								echo "<p class='error-message'>Lo sentimos, verifica el usuario o contraseña y vuelve a intentarlo.</p>";
							}
						}
			    	?>
			    </div>

			    <form action="login-access.php" method="POST">
			      	<div class="input-group">
			      		<label for="username" class="lb-input">Ingresa tu usuario *</label>
			      		<input type="text" id="username" placeholder="Usuario" required name="username" />
			      	</div>

					<div class="input-group">
			      		<label for="password" class="lb-input">Ingresa tu contraseña *</label>
			      		<input type="password" id="password" name="password" placeholder="Contraseña" required />
			      	</div>
			      	
			      	<button type="submit" class="btn-green">Ingresar</button>
			    </form>
			  </div>
		</section>
	</main>
</body>
</html>