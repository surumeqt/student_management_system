<?php 
$host = 'localhost';
$username = 'root';
$password = '';
$database = 'student_management';

$conn = new mysqli($host, $username, $password, $database);

if ($conn->connect_error) {
    die("Connection failed: " . $connection->connect_error);
}