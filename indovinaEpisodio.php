<?php
require_once "conn.php";

if(!isset($_SESSION)){
    session_start();
}

//controllo se lutente è logagto
if(!isset($_SESSION["user_id"])){
    header("Location:login.php?messaggio=Effettua il login per accedere!");
    exit();
}

//genero un episoodio a caso
$episodio_api_url = "https://rickandmortyapi.com/api/episode/" . rand(1, 50); //ci sono circa 50 episodi
$episodio_json = file_get_contents($episodio_api_url);
$episodio = json_decode($episodio_json, true);    //l'API mi restituisce un JSOn per cui faccio la decode

//prendo solo 3 personaggi casuali dall'episodio (troppo easy sennò)
$personaggi = [];
$personaggiRandom = $episodio["characters"];
shuffle($personaggiRandom);     //shuffle randiomizza per me l'array
$personaggi_limit = array_slice($personaggiRandom, 0, min(3, count($personaggiRandom)));

//la count conta il numero di elementi nei presonaggi
//se l’array ha 10 elementi, min(3, 10) → 3.
//se ne ha solo 2, min(3, 2) → 2.
//la slice prende un pezzo di array

foreach($personaggi_limit as $personaggio_url) {
    $personaggio_json = file_get_contents($personaggio_url);        //prendo l'img per ogni personaggio che ho salvato (max 3)
    $personaggio = json_decode($personaggio_json, true);
    $personaggi[] = $personaggio;       //e metto in array personaggi
}

//salvo lepisodio per verificare dopo la rispsota  --> dopo lo stampo nell'HTML
$_SESSION["episodio_corrente"] = $episodio["id"];
$_SESSION["episodio_nome"] = $episodio["name"];
?>

<!DOCTYPE html>
<html lang="it">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Indovina l'Episodio</title>
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
        
        .episode-card {
            background-color: rgba(0, 0, 0, 0.5);
            border-radius: 10px;
            padding: 20px;
            margin-top: 20px;
            border: 2px solid #97ce4c;
        }
        
        .episode-info {
            margin-bottom: 20px;
        }
        
        .info-label {
            font-weight: bold;
            color: #97ce4c;
        }
        
        .characters {
            display: flex;
            gap: 15px;
            flex-wrap: wrap;
            justify-content: center;
        }
        
        .character {
            text-align: center;
            flex: 1;
            min-width: 150px;
        }
        
        .character img {
            width: 100%;
            border-radius: 10px;
            border: 3px solid #f0e14a;
        }
        
        form {
            margin-top: 30px;
            background-color: rgba(0, 0, 0, 0.5);
            padding: 20px;
            border-radius: 10px;
            border: 2px solid #97ce4c;
        }
        
        input[type="text"] {
            width: 100%;
            padding: 10px;
            border-radius: 5px;
            border: 2px solid #97ce4c;
            background-color: rgba(0, 0, 0, 0.7);
            color: white;
            font-size: 1rem;
            margin-bottom: 10px;
        }
        
        button {
            background-color: #97ce4c;
            color: #44281d;
            border: none;
            padding: 10px 20px;
            border-radius: 5px;
            font-weight: bold;
            cursor: pointer;
            transition: background-color 0.3s;
        }
        
        button:hover {
            background-color: #f0e14a;
        }
        
        .message {
            background-color: #f0e14a;
            color: #44281d;
            padding: 10px;
            border-radius: 5px;
            margin-bottom: 20px;
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
    </style>
</head>
<body>
    <header>
        <h1>Indovina l'Episodio</h1>
    </header>
    
    <div class="container">
        <a href="paginaUtente.php" class="back-btn">← Torna alla Home</a>
        
        <?php
        if(isset($_GET["messaggio"]) && !empty($_GET["messaggio"])){
            echo "<div class='message'>" . $_GET["messaggio"] . "</div>";
        }
        ?>
        
        <div class="episode-card">
            <div class="episode-info">
                <div>
                    <span class="info-label">Codice:</span> <?php echo $episodio["episode"]; ?>
                </div>
                
                <div>
                    <span class="info-label">Data di uscita:</span> <?php echo $episodio["air_date"]; ?>
                </div>
            </div>
            
            <h3>Personaggi presenti in questo episodio:</h3>
            <div class="characters">
                <?php foreach($personaggi as $personaggio): ?>
                <div class="character">
                    <img src="<?php echo $personaggio["image"]; ?>" alt="<?php echo $personaggio["name"]; ?>">
                    <div><?php echo $personaggio["name"]; ?></div>
                </div>
                <?php endforeach; ?>
            </div>
        </div>
        
        <form action="verificaEpisodio.php" method="POST">
            <h3>Qual è il titolo di questo episodio?</h3>
            <input type="text" name="risposta" placeholder="Inserisci il titolo dell'episodio" required>
            <button type="submit">Conferma Risposta</button>
        </form>
    </div>
</body>
</html>