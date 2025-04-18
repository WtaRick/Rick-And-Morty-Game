<?php
require_once "conn.php";

if(!isset($_SESSION)){
    session_start();
}

if(!isset($_POST["username"]) || !isset($_POST["password"])){
    header("Location:index.php?messaggio=Inserisci tutte le credenziali!");
    exit;
}

if(empty($_POST["username"]) || empty($_POST["password"])){
    header("Location:index.php?messaggio=Inserisci delle credenziali valide!");
    exit;
}

//rende inoffensivi eventuali caratteri speciali che potrebbero essere usati per un attacco SQL injection
$user = $conn->real_escape_string($_POST["username"]);
$pass = $_POST["password"];
//? --> segnaposto che verranno sostituiti in modo sicuro con i valori effettivi
$q = "SELECT ID, username, XP, Monete, prestigioAttuale FROM `utenti` WHERE username = ? AND password = ?";

$stmt = $conn->prepare($q);
$hashed_password = md5($pass);

$stmt->bind_param("ss", $user, $hashed_password);   //ss sta per String e String, i due valori sostituiscono i ?
$stmt->execute();   //esegue la query
$result = $stmt->get_result(); //prende il risultato come al solito

if($result->num_rows == 0){
    header("Location:index.php?messaggio=Ricontrolla le credenziali!");
    exit();
}
else if($result->num_rows > 1){
    header("Location:index.php?messaggio=ERRORE!");
    exit();
} 
else {
    //salvo i dati utente nella sessione
    $utente = $result->fetch_assoc();
    $_SESSION["user_id"] = $utente["ID"];
    $_SESSION["username"] = $utente["username"];
    $_SESSION["XP"] = $utente["XP"];
    $_SESSION["Monete"] = $utente["Monete"];
    $_SESSION["prestigioAttuale"] = $utente["prestigioAttuale"];
    
    header("Location:paginaUtente.php");
    exit();
}
?>