<?php

session_start();

if(isset($_SESSION['identifiant'])) {
    header('Location: index.php');
}

include_once('verifConnexion.php');

if (isset($_POST['identifiant']) && isset($_POST['password'])) {
    if (verifUtilisateur($_POST['identifiant'], $_POST['password'])) {
        $_SESSION['identifiant'] = $_POST['identifiant'];
        header('Location: index.php');
        exit();
    } else {
        echo "<script>alert('Identifiant ou mot de passe incorrect.');</script>";
    }
}

$html = '

<!DOCTYPE html>
<html lang="fr">
<head>
    <link rel="stylesheet" href="./css/style.css">
    <link rel="stylesheet" href="./css/connexion.css">
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Foot Sphere</title>
</head>
<body >
    <nav>
        <ul>
            <li><a href="index.php">Accueil</a></li>
            <li><a href="match.php">Match</a></li>
            <li><a href="joueurs.php">Joueurs</a></li>
        </ul>
    </nav>
    
    <div style="width: 100%; height: 100%; display: flex; justify-content: center; align-items: center;">
        <form action="connexion.php" method="post">
            <label for="">Identifiant</label>
            <input type="text" name="identifiant">
            <label for="">Mot de passe</label>
            <input type="password" name="password">
            <button type="submit">Se connecter</button>
        </form>
    </div>

    <footer>
        <p>&copy; 2025 Foot Sphere - Tous droits réservés.</p> 
        <a href="mention_legale.php">Mention Légale</a>
    </footer>
</body>
</html>

';

echo $html;

?>