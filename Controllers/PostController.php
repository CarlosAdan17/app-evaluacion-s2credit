<?php

class PostController
{
	/**
	 * Función para guardar la información en la base de datos
	 *  
	 */
	function addValuesToDb($conn) {
		$url = "https://jsonplaceholder.typicode.com/posts";
		$data = file_get_contents($url);
		$posts = json_decode($data, true);

		// Verificar si la conexión fue exitosa
		if ($conn->connect_error) {
		    die("Error en la conexión: " . $conn->connect_error);
		}

		// Iterar sobre los posts y guardar la información en la base de datos
		foreach ($posts as $post) {
		    $title = $post['title'];
		    $body = $post['body'];

		    // Escapar caracteres especiales para prevenir inyección de SQL
		    $title = $conn->real_escape_string($title);
		    $body = $conn->real_escape_string($body);

		    // Crear la consulta SQL para insertar los datos en la tabla
		    $sql = "INSERT INTO posts (title, body) VALUES ('$title', '$body')";

		    $conn->query($sql);

		    $sql = "SELECT * FROM posts where title = '$title' and body = '$body'";
			$result = $conn->query($sql);

			$post = $result->fetch_assoc()['id'];

			$sql = "UPDATE posts SET father_id = '$post', type = 1, main_status = 1 where id = '$post'";
			$conn->query($sql);

		}

		$_SESSION['statusSave'] = "true";
		header("Location: ../list.php");
		exit();
	}

	/**
	 * Función para obtener los registros de los posts
	 * 
	 */
	function getListPost($conn) {
		// Consulta para obtener todos los registros de la tabla "posts"
	    $sql = "SELECT * FROM posts where main_status = 1";
	    $result = $conn->query($sql);

	    // Verificar si hay resultados
	    if ($result->num_rows > 0) {
	        // Array para almacenar los resultados
	        $posts = array();

	        // Obtener cada fila de resultados como un arreglo asociativo
	        while ($row = $result->fetch_assoc()) {
	            $posts[] = $row;
	        }

	        // Cerrar la conexión a la base de datos
	        $conn->close();

	        // Retornar los registros obtenidos
	        return $posts;
	    } else {
	        // Cerrar la conexión a la base de datos
	        $conn->close();

	        // Si no hay registros, retornar un array vacío
	        return array();
	    }
	} 
}