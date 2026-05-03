<?php

session_start();

if(isset($_SESSION['identifiant'])) {
    $access = true;
} else {
    $access = false;
}

include_once('env.php');

$conn = getPgConnection();

$sql = "SELECT * FROM LesJoueurs;";
$result = pg_query($conn, $sql);

if (!$result) {
    die("Erreur dans la requête SQL : " . pg_last_error());
}

$matchesHtml = '';

if (pg_num_rows($result) == 0) {
    $matchesHtml .= '<p>Aucun match trouvé.</p>';
} else {
    while ($row = pg_fetch_row($result)) {
        $namePlayer = trim($row[1], "'");
        $nameTeam = trim($row[3], "'");
        $photoTeam = trim($row[4], "'");

        if (empty($namePlayer) || empty($nameTeam)) {
            continue;
        }

        $matchesHtml .= '<div class="card-joueur">
                            <div class="card-joueur-equipe">
                                <p>' . $nameTeam . '</p>
                                <img class="card-joueur-equipe-image" src="' . $photoTeam . '" alt="">
                            </div>    
                            <img class="card-joueur-image" src="https://www.pngplay.com/wp-content/uploads/15/Kylian-Mbappe-Transparent-Free-PNG.png" alt="">
                            <h3 class="card-joueur-pseudo">' . $namePlayer . '</h3>
                            <div class="glow"></div>
                        </div>';
    }
}

pg_close($conn);

$html = '<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Foot Sphere</title>
    <link rel="stylesheet" href="css/style.css">
    <link rel="stylesheet" href="css/joueur.css">
</head>
<body>
    <nav>
        <ul>
            <li><a href="index.php">Accueil</a></li>
            <li><a href="match.php">Match</a></li>
            <li><a href="joueurs.php">Joueurs</a></li>
        </ul>';
        
        if ($access) {
            $html .= '<form action="index.php" method="post" > <button name="logout">Se déconnecter</button> </form>';
        } else {
            $html .= '<a href="connexion.php">Se connecter</a>';
        }
        
        $html .= '
    </nav>
    <div class="joueur">
        <h1>Les joueurs</h1>
        <div class="container-joueur">
            '. $matchesHtml . '
        </div>
    </div>

    <footer>
        <p>&copy; 2025 Foot Sphere - Tous droits réservés.</p> 
        <a href="mention_legale.php">Mention Légale</a>
    </footer>

    <script src="js/main.js"></script>
</body>
</html>';

echo $html;
?>
