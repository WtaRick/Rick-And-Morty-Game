<?php
require_once "conn.php";

if(!isset($_SESSION)){
    session_start();
}

//verifica lo in dell'utente
if(!isset($_SESSION["user_id"])){
    header("Location:login.php?messaggio=Effettua il login per accedere!");
    exit();
}

//prendo classifica (i primi 20)
$q = "SELECT u.ID, u.username, u.Monete, u.XP, p.nome as livello 
      FROM utenti u 
      JOIN prestigio p ON u.prestigioAttuale = p.ID 
      ORDER BY u.Monete DESC LIMIT 20";

//Ordino in base alle m onete  per ordine decrescente (LIMIT praticmanete limita il numero di tuple restituite in qeustio caso 20)
      
$risClassifica = $conn->query($q);

//prendo id dell'utente dalla sessione
$idUtente = $_SESSION["user_id"];

//trovo la poszione dell'utente (molto simile ma controllo l'ID + calcolo posizione)
$qUtente = "SELECT u.ID, u.username, u.Monete, u.XP, p.nome as livello, 
           (SELECT COUNT(*) + 1 FROM utenti WHERE Monete > u.Monete) as posizione 
           FROM utenti u 
           JOIN prestigio p ON u.prestigioAttuale = p.ID 
           WHERE u.ID = $idUtente";

           //spiegazione QUERY:
           //(SELECT COUNT(*) + 1 FROM utenti WHERE Monete > u.Monete) as posizione --> se 5 utenti hanno più monete, l'utente è in 6sta posizione

$risUtente = $conn->query($qUtente);
$datiUtente = null;
//controllo se ci sono dati
if ($risUtente && $risUtente->num_rows > 0) {
    $datiUtente = $risUtente->fetch_assoc();
}
?>

<!DOCTYPE html>
<html lang="it">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Classifica - Rick and Morty Game</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            background-color: #2b2b2b;
            color: white;
            margin: 0;
            padding: 0;
        }
        
        .container {
            max-width: 800px;
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
        
        .back-btn {
            display: inline-block;
            background-color: #44281d;
            color: white;
            padding: 10px 20px;
            border-radius: 5px;
            text-decoration: none;
            margin-bottom: 20px;
        }
        
        .message {
            background-color: #f0e14a;
            color: #44281d;
            padding: 10px;
            border-radius: 5px;
            margin-bottom: 20px;
        }
        
        .leaderboard {
            background-color: rgba(0, 0, 0, 0.5);
            border-radius: 10px;
            padding: 20px;
            margin-top: 20px;
            border: 2px solid #97ce4c;
        }
        
        .leaderboard-title {
            color: #97ce4c;
            text-align: center;
            margin-bottom: 20px;
        }
        
        table {
            width: 100%;
            border-collapse: collapse;
        }
        
        th, td {
            padding: 12px;
            text-align: left;
            border-bottom: 1px solid #444;
        }
        
        th {
            background-color: rgba(151, 206, 76, 0.3);
            color: #f0e14a;
        }
        
        tr:hover {
            background-color: rgba(151, 206, 76, 0.1);
        }
        
        .current-user {
            background-color: rgba(240, 225, 74, 0.2);
            font-weight: bold;
        }
        
        .position {
            text-align: center;
            font-weight: bold;
        }
        
        .position-1 {
            color: gold;
        }
        
        .position-2 {
            color: silver;
        }
        
        .position-3 {
            color: #cd7f32; /* bronze */
        }
        
        .user-info {
            background-color: rgba(0, 0, 0, 0.5);
            border-radius: 10px;
            padding: 15px;
            margin-top: 20px;
            border: 2px solid #f0e14a;
            display: flex;
            justify-content: space-between;
            align-items: center;
        }
        
        .user-stats {
            display: flex;
            gap: 20px;
        }
        
        .stat {
            text-align: center;
        }
        
        .stat-value {
            font-size: 1.5rem;
            font-weight: bold;
            color: #97ce4c;
        }
        
        .stat-label {
            color: #f0e14a;
            font-size: 0.9rem;
        }
    </style>
</head>
<body>
    <header>
        <h1>Classifica</h1>
    </header>
    
    <div class="container">
        <a href="paginaUtente.php" class="back-btn">← Torna alla Home</a>
        
        <?php
        if(isset($_GET["messaggio"]) && !empty($_GET["messaggio"])){
            echo "<div class='message'>" . htmlspecialchars($_GET["messaggio"]) . "</div>";
        }
        ?>
        
        <div class="user-info">
            <div>
            <?php
            //controllo che abbia la poszione prima di utilizzarla senno mi da errore che il dato è NULL
            //chat per la grafica ha sbrattato tutto l'ordine delle robe ma il concetto resta quello
            if ($datiUtente && isset($datiUtente["posizione"])) {
            ?>
                <h3>La tua posizione: <?php echo htmlspecialchars($datiUtente["posizione"]); ?>°</h3>     
                <p><?php echo htmlspecialchars($datiUtente["username"]); ?> - <?php echo htmlspecialchars($datiUtente["livello"]); ?></p>
            <?php
            } else {
            ?>
                <h3>Posizione non disponibile</h3>
                <p>Dati utente non trovati</p>
            <?php
            }
            ?>
            </div>
            <div class="user-stats">
                <div class="stat">
                    <div class="stat-value"><?php echo ($datiUtente && isset($datiUtente["Monete"])) ? htmlspecialchars($datiUtente["Monete"]) : "0"; ?></div>
                    <div class="stat-label">Monete</div>
                </div>
                <div class="stat">
                    <div class="stat-value"><?php echo ($datiUtente && isset($datiUtente["XP"])) ? htmlspecialchars($datiUtente["XP"]) : "0"; ?></div>
                    <div class="stat-label">XP</div>
                </div>
            </div>
        </div>
        
        <div class="leaderboard">
            <h2 class="leaderboard-title">TOP 20 Giocatori</h2>
            
            <table>
                <thead>
                    <tr>
                        <th>Pos.</th>
                        <th>Username</th>
                        <th>Livello</th>
                        <th>Monete</th>
                        <th>XP</th>
                    </tr>
                </thead>
                <tbody>
                    <?php 
                    //controllo se ci sono dati nella classifica
                    if(!$risClassifica || $risClassifica->num_rows == 0) {
                    ?>
                        <tr>
                            <td colspan="5" style="text-align: center;">Nessun utente trovato</td>
                        </tr>
                    <?php 
                    } else {
                        //se ci sono
                        $position = 1;
                        while($row = $risClassifica->fetch_assoc()) {
                            //controllo che l'utente è loggato e metto a true
                            $utenteCorrente = false;
                            if($row["ID"] == $idUtente) {
                                $utenteCorrente = true;
                            }
                            
                            //4 ECCEZIONI: 1-2-3 poszione + poszione dell'utente 

                            //CSS per le prime 3 poszioni
                            $position_class = "";
                            if($position <= 3) {
                                $position_class = "position-" . $position;
                            }
                            
                            //CSS per evidenziare la poszione dell'utente
                            $classe_riga = "";
                            if($utenteCorrente) {
                                $classe_riga = "current-user";
                            }
                    ?>
                        <tr class="<?php echo $classe_riga; ?>">
                            <td class="position <?php echo $position_class; ?>"><?php echo $position; ?></td>
                            <td><?php echo htmlspecialchars($row["username"]); ?></td>
                            <td><?php echo htmlspecialchars($row["livello"]); ?></td>
                            <td><?php echo htmlspecialchars($row["Monete"]); ?></td>
                            <td><?php echo htmlspecialchars($row["XP"]); ?></td>
                        </tr>
                    <?php 
                            $position++;
                        }
                    }
                    ?>
                </tbody>
            </table>
        </div>
    </div>
</body>
</html>