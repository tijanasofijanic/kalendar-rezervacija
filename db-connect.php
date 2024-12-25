<?php 
$host = 'localhost';
$username = 'root';
$password = '';
$dbname = 'apartman';

$conn = new mysqli($host, $username, $password, $dbname);

if(!$conn){
    die("Ne može se povezati sa bazom". $conn->error);
}
?>