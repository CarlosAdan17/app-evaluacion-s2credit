<?php

require "DbController.php";
require "PostController.php";

// Validar si se presiono el botón para obtener los datos del servicio REST
if (isset($_POST['postsList'])) {
	$conn = (new DbController())->connect();
	$postController = (new PostController())->addValuesToDb($conn);
}