<?php

$conn = new mysqli("localhost", "root", "", "rickandmortygame");

if($conn->connect_error){
    die($conn->connect_error);
}

?>