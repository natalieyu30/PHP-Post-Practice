<?php

// Connect to database
$conn = mysqli_connect('localhost', 'Natalie', '1234', 'php_dev');

// Chcek connection
if (!$conn) {
    echo 'Connection error: ' . mysqli_connect_error();
}


?>