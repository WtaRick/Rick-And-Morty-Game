<?php
require_once "conn.php";

if(!isset($_SESSION)){
    session_start();
}

//solita verifica
if(!isset($_SESSION["user_id"])){
    header("Location:login.php?messaggio=Effettua il login per accedere!");
    exit();
}

if(!isset($_SESSION["personaggio_nome"]) || !isset($_POST["risposta"])){
    header("Location:indovinaPersonaggio.php?messaggio=Errore, riprova!");
    exit();
}

$risposta_utente = trim($_POST["risposta"]);    //rimuovo dalla stringa gli spazi
$nome_corretto = $_SESSION["personaggio_nome"];

//verifico la risposta case insensitive senno è troppo diffcile 
if(strtolower($risposta_utente) == strtolower($nome_corretto)){     // strtolower --> trasforma tutta la stringa inserita dall’utente in minuscolo.

    //se la risposyta è corretta aggiorno
    $user_id = $_SESSION["user_id"];
    $monete_guadagnate = 20;
    $xp_guadagnati = 10;
    
    //aggiorno DB
    $q = "UPDATE utenti SET XP = XP + $xp_guadagnati, Monete = Monete + $monete_guadagnate WHERE ID = $user_id";
    $conn->query($q);

    
    //devo controllare se l'utente ha raggiunto un nuovo prestigio

    //prendo dati utente
    $q_utente = "SELECT XP FROM utenti WHERE ID = $user_id";
    $result = $conn->query($q_utente);
    $utente = $result->fetch_assoc();
    $xp_totale = $utente["XP"];
    
    //trovo il prestigio giusto
    $q_prestigio = "SELECT ID FROM prestigio WHERE xpMinimi <= $xp_totale AND xpMassimi > $xp_totale";
    $result_prestigio = $conn->query($q_prestigio);
    
    if($result_prestigio->num_rows > 0){
        $prestigio = $result_prestigio->fetch_assoc();
        $nuovo_prestigio = $prestigio["ID"];
        
        //aggirono prestigio
        $q_update = "UPDATE utenti SET prestigioAttuale = $nuovo_prestigio WHERE ID = $user_id";
        $conn->query($q_update);
        
        //controllo se l'utente ha gia questo prestigio e se ottengo 0 sono a psoto

        $q_check = "SELECT * FROM utente_prestigio WHERE utenteID = $user_id AND prestigioID = $nuovo_prestigio";
        $checkControlloPrestigio = $conn->query($q_check);
        
        if($checkControlloPrestigio->num_rows == 0){
            //aggiungo il badge del prestigio
            $q_badge = "INSERT INTO utente_prestigio (utenteID, prestigioID) VALUES ($user_id, $nuovo_prestigio)";
            $conn->query($q_badge);
        }
    }

    header("Location:indovinaPersonaggio.php?messaggio=Corretto! Hai guadagnato $monete_guadagnate monete e $xp_guadagnati XP");
    exit();
} else {

    header("Location:indovinaPersonaggio.php?messaggio=Sbagliato! Il personaggio era: $nome_corretto");
    exit();
}
?>