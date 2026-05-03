<?php
session_start();

if(isset($_POST['logout'])) {
    session_destroy();
    session_unset();
    $access = false;
}

if(isset($_SESSION['identifiant'])) {
    $access = true;
} else {
    $access = false;
}

$html = '

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Foot Sphere</title>
    <link rel="stylesheet" href="css/style.css">
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

    <section class="acceuil">
        <h1>Foot Sphere</h1>
        <h2><span>PASSION</span><span>SANS</span><span>LIMITE</span></h2>

        <div class="redirection-card">
            <a href="match.php" class="card">
                <div >
                    <h3>MATCH</h3>
                    <img src="https://www.lequipe.fr/_medias/img-photo-jpg/kylian-mbappe-au-duel-avec-le-bosnien-branimir-cipetic-le-1er-septembre-a-strasbourg-1-1-f-faugere-l-equipe/1500000001552484/0:0,1998:1332-828-552-75/6c4c1" alt="Image 1">
                </div>
            </a>
            <a href="joueurs.php" class="card">
                <div>
                    <img src="https://static.cnews.fr/sites/default/files/football_equipe_de_france_programme_matchs_67e14dc60d156.jpg" alt="Image 2">
                    <h3>JOUEURS</h3>
                </div>
            </a>
            <a href="match.php" class="card">
                <div>
                    <img src="https://upload.wikimedia.org/wikipedia/commons/4/4b/20191002_Fu%C3%9Fball%2C_M%C3%A4nner%2C_UEFA_Champions_League%2C_RB_Leipzig_-_Olympique_Lyonnais_by_Stepro_StP_0097.jpg" alt="Image 3">
                    <h3>DERBY</h3>
                </div>
            </a>
        </div>
    </section>


    <footer>
        <p>&copy; 2025 Foot Sphere - Tous droits réservés.</p> 
        <a href="mention_legale.php">Mention Légale</a>
    </footer>
</body>
</html>

';

echo $html;

?>