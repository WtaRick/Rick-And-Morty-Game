<?php

if(isset($_SESSION)){
    session_destroy();
    header("Location:index.php?messaggio:Logout eseguito con successo!");
    exit;
}
else{
    header("Location:index.php?messaggio:Logout eseguito con successo!");
    exit;
}

?>