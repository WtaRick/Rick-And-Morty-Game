<?php
require_once "conn.php";

if(!isset($_SESSION)){
    session_start();
}

//verifica se l'utente è loggato
if(!isset($_SESSION["user_id"])){
    header("Location:login.php?messaggio=Effettua il login per accedere!");
    exit();
}

//PER DATI UTENTE

//prendo le informazioni utente aggiornate (per sicurezza)
$idUtente = $_SESSION["user_id"];
$query = "SELECT * FROM utenti WHERE ID = $idUtente";
$result = $conn->query($query);
$utente = $result->fetch_assoc();

//e ora aggiorno i dati della sessione (sempre per sicurezza) 
$_SESSION["XP"] = $utente["XP"];
$_SESSION["Monete"] = $utente["Monete"];
$_SESSION["prestigioAttuale"] = $utente["prestigioAttuale"];


//PER DATI PRESTIGIO

//prendo il prestigio
$query_prestigio = "SELECT nome FROM prestigio WHERE ID = " . $utente["prestigioAttuale"];
$result_prestigio = $conn->query($query_prestigio);
$prestigio = $result_prestigio->fetch_assoc();
$nome_prestigio = $prestigio["nome"] ?? "Nessuno";  //se $prestigio è null o se $prestigio["nome"] non è settato mette Nessuno in automatico

//percentuale della barra di progresso del prestigio
$query_limite = "SELECT xpMinimi, xpMassimi FROM prestigio WHERE ID = " . $utente["prestigioAttuale"];
$result_limite = $conn->query($query_limite);
$limite = $result_limite->fetch_assoc();

$xp_corrente = $utente["XP"];

if ($limite) {
    $xp_minimo = $limite["xpMinimi"];
    $xp_massimo = $limite["xpMassimi"];
} else {
    $xp_minimo = 0;
    $xp_massimo = 100;
}

$percentuale = min(100, max(0, (($xp_corrente - $xp_minimo) / ($xp_massimo - $xp_minimo)) * 100));  //calcolo % chiesto a chat


?>

<!DOCTYPE html>
<html lang="it">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Rick and Morty Game - Home</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            background-color: #2b2b2b;
            color: white;
            margin: 0;
            padding: 0;
        }
        
        .container {
            max-width: 1200px;
            margin: 0 auto;
            padding: 20px;
        }
        
        header {
            background-color: #97ce4c;
            padding: 20px;
            text-align: center;
            color: #44281d;
            border-bottom: 5px solid #f0e14a;
        }
        
        .user-info {
            background-color: rgba(0, 0, 0, 0.5);
            padding: 15px;
            border-radius: 10px;
            margin-bottom: 20px;
            display: flex;
            justify-content: space-between;
            align-items: center;
        }
        
        .stats {
            display: flex;
            gap: 20px;
        }
        
        .stat {
            text-align: center;
        }
        
        .stat-value {
            font-size: 1.5rem;
            font-weight: bold;
            color: #f0e14a;
        }
        
        .progress-bar {
            background-color: #44281d;
            border-radius: 10px;
            height: 20px;
            width: 100%;
            margin-top: 10px;
        }
        
        .progress {
            background-color: #97ce4c;
            height: 100%;
            border-radius: 10px;
            width: <?php echo $percentuale; ?>%;
        }
        
        .games {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(300px, 1fr));
            gap: 20px;
            margin-top: 30px;
        }
        
        .game-card {
            background-color: rgba(0, 0, 0, 0.5);
            border-radius: 10px;
            padding: 20px;
            text-align: center;
            transition: transform 0.3s;
            cursor: pointer;
            border: 2px solid #97ce4c;
        }
        
        .game-card:hover {
            transform: translateY(-5px);
            border-color: #f0e14a;
        }
        
        .game-title {
            color: #97ce4c;
            font-size: 1.5rem;
            margin-bottom: 10px;
        }
        
        .game-description {
            margin-bottom: 20px;
        }
        
        .game-button {
            background-color: #97ce4c;
            color: #44281d;
            border: none;
            padding: 10px 20px;
            border-radius: 5px;
            font-weight: bold;
            cursor: pointer;
            transition: background-color 0.3s;
        }
        
        .game-button:hover {
            background-color: #f0e14a;
        }
        
        .logout {
            background-color: #ff3c7b;
            color: white;
            border: none;
            padding: 5px 10px;
            border-radius: 5px;
            cursor: pointer;
        }
        
        .classifica-btn {
            background-color: #f0e14a;
            color: #44281d;
            border: none;
            padding: 10px 20px;
            border-radius: 5px;
            font-weight: bold;
            cursor: pointer;
            margin-top: 20px;
            display: block;
            width: 200px;
            margin: 20px auto;
            text-align: center;
            text-decoration: none;
        }

        .badge {
            background-color: #44281d;
            color: #f0e14a;
            padding: 5px 10px;
            border-radius: 15px;
            font-size: 0.8rem;
            font-weight: bold;
        }
    </style>
</head>
<body>
    <header>
        <h1>Rick and Morty Game</h1>
    </header>
    
    <div class="container">
        <?php
        if(isset($_GET["messaggio"]) && !empty($_GET["messaggio"])){
            echo "<div class='message'>" . $_GET["messaggio"] . "</div>";
        }
        ?>
        
        <div class="user-info">
            <div>
                <h2>Benvenuto <?php echo $utente["username"]; ?></h2>
                <span class="badge">Prestigio: <?php echo $nome_prestigio; ?></span>
            </div>
            
            <div class="stats">
                <div class="stat">
                    <div>XP</div>
                    <div class="stat-value"><?php echo $utente["XP"]; ?></div>
                </div>
                
                <div class="stat">
                    <div>Monete</div>
                    <div class="stat-value"><?php echo $utente["Monete"]; ?></div>
                </div>
                
                <a href="logout.php" class="logout">Logout</a>
            </div>
        </div>
        
        <div>
            <h3>Progresso verso
                <?php 

                $next_prestigio_id = $utente["prestigioAttuale"] + 1;

                $query_next = "SELECT nome FROM prestigio WHERE ID = $next_prestigio_id";       //il prox prestigio

                $result_next = $conn->query($query_next);
                if($result_next->num_rows > 0) {
                    $next = $result_next->fetch_assoc();
                    echo $next["nome"];
                } else {
                    echo "Massimo prestigio";     //se ha raggiunto il prestigio massimo
                }
            ?></h3>
            <div class="progress-bar">
                <div class="progress"></div>
            </div>
            <div>XP: <?php echo $xp_corrente; ?>/<?php echo $xp_massimo; ?></div>
        </div>
        
        <div class="games">
            <div class="game-card">
                <h3 class="game-title">Indovina il Personaggio</h3>
                <p class="game-description">Data l'immagine e altre informazioni, indovina il nome del personaggio.</p>
                <a href="indovinaPersonaggio.php" class="game-button">Gioca</a>
            </div>
            
            <div class="game-card">
                <h3 class="game-title">Indovina l'Episodio</h3>
                <p class="game-description">Data la lista dei personaggi e altre informazioni, indovina il nome dell'episodio.</p>
                <a href="indovinaEpisodio.php" class="game-button">Gioca</a>
            </div>
        </div>
        
        <a href="classifica.php" class="classifica-btn">Visualizza Classifica</a>
    </div>
</body>
</html>