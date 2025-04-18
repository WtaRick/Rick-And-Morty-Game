<?php
require_once "conn.php";

if(!isset($_SESSION)){
    session_start();
}

if(!isset($_POST["username"]) || !isset($_POST["password"])){
    header("Location:index.php?messaggio=credenziali errate!");
    exit();
}

if(empty($_POST["username"]) || empty($_POST["password"])){
    header("Location:index.php?messaggio=credenziali vuote!");
    exit();
}

$user = $conn->real_escape_string($_POST["username"]);
$pass = $_POST["password"];

//guardo se l'username esiste già
$q = "SELECT username FROM utenti WHERE username = ?";
$check = $conn->prepare($q);
$check->bind_param("s", $user); //s --> STring 
$check->execute();
$risultatoCheck = $check->get_result();

if($risultatoCheck->num_rows > 0) {
    header("Location:index.php?messaggio=Username già in uso!");
    exit();
}

$checkPrestigio = $conn->query("SELECT ID FROM prestigio LIMIT 1");
if ($checkPrestigio->num_rows > 0) {
    $prestigioRow = $checkPrestigio->fetch_assoc();
    $defaultPrestigio = $prestigioRow['ID'];
    
    $query = "INSERT INTO utenti (username, password, XP, Monete, prestigioAttuale) VALUES (?, ?, 0, 0, ?)";
    $stmt = $conn->prepare($query);
    $hashed_password = md5($pass);
    $stmt->bind_param("ssi", $user, $hashed_password, $defaultPrestigio);
}
$result = $stmt->execute();

if($result){
    header("Location:index.php?messaggio=Registrazione eseguita con successo!");
    exit();
} else {
    header("Location:index.php?messaggio=Errore durante la registrazione!");
    exit();
}
?>