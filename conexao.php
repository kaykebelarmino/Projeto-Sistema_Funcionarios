<?php
$servername = "localhost:3308";
$username = "root";  
$password = "etec2024"; 
$dbname = "empresa"; 

$conn = new mysqli($servername, $username, $password, $dbname);

if ($conn->connect_error) {
    die("Conexão falhou: " . $conn->connect_error);
}
?>
