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

//genero un personaggio a caso
$carattere_api_url = "https://rickandmortyapi.com/api/character/" . rand(1, 650); //ci sono circa 650 personaggi
$carattere_json = file_get_contents($carattere_api_url);
$carattere = json_decode($carattere_json, true);    //l'API mi restituisce un JSOn per cui faccio la decode

//e poi salvo nella sessione
$_SESSION["personaggio_corrente"] = $carattere["id"];
$_SESSION["personaggio_nome"] = $carattere["name"];
?>

<!DOCTYPE html>
<html lang="it">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Indovina il Personaggio</title>
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
        
        .character-card {
            background-color: rgba(0, 0, 0, 0.5);
            border-radius: 10px;
            padding: 20px;
            margin-top: 20px;
            display: flex;
            gap: 20px;
            align-items: center;
            border: 2px solid #97ce4c;
        }
        
        .character-image {
            flex: 1;
        }
        
        .character-image img {
            width: 100%;
            border-radius: 10px;
            border: 3px solid #f0e14a;
        }
        
        .character-info {
            flex: 2;
        }
        
        .info-item {
            margin-bottom: 10px;
        }
        
        .info-label {
            font-weight: bold;
            color: #97ce4c;
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
        <h1>Indovina il Personaggio</h1>
    </header>
    
    <div class="container">
        <a href="paginaUtente.php" class="back-btn">← Torna alla Home</a>
        
        <?php
        if(isset($_GET["messaggio"]) && !empty($_GET["messaggio"])){
            echo "<div class='message'>" . $_GET["messaggio"] . "</div>";
        }
        ?>
        
        <div class="character-card">
            <div class="character-image">
                <img src="<?php echo $carattere["image"]; ?>" alt="Personaggio misterioso">
            </div>
            
            <div class="character-info">
                <div class="info-item">
                    <span class="info-label">Status:</span> <?php echo $carattere["status"]; ?>
                </div>
                
                <div class="info-item">
                    <span class="info-label">Specie:</span> <?php echo $carattere["species"]; ?>
                </div>
                
                <div class="info-item">
                    <span class="info-label">Origine:</span> <?php echo $carattere["origin"]["name"]; ?>
                </div>
                
                <div class="info-item">
                    <span class="info-label">Attuale Locazione:</span> <?php echo $carattere["location"]["name"]; ?>
                </div>
            </div>
        </div>
        
        <form action="verificaPersonaggio.php" method="POST">
            <h3>Chi è questo personaggio?</h3>
            <input type="text" name="risposta" placeholder="Inserisci il nome del personaggio" required>
            <button type="submit">Conferma Risposta</button>
        </form>
    </div>
</body>
</html>