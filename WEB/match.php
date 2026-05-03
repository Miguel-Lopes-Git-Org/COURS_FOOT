<?php

session_start();

if(isset($_SESSION['identifiant'])) {
    $access = true;
} else {
    $access = false;
}

include_once('env.php');

$conn = getPgConnection();

$searchClub = isset($_GET['search_club']) ? $_GET['search_club'] : '';
$searchDate = isset($_GET['search_date']) && !empty($_GET['search_date']) ? $_GET['search_date'] : null;

$sql = "SELECT * FROM LesRencontres WHERE 
        (LOWER(home_team) LIKE LOWER($1) OR LOWER(away_team) LIKE LOWER($1))";
$params = ['%' . $searchClub . '%'];

if ($searchDate) {
    $sql .= " AND date_match = $2";
    $params[] = $searchDate;
}

$result = pg_query_params($conn, $sql, $params);

$matchesHtml = '';

if (pg_num_rows($result) == 0) {
    $matchesHtml .= '<p>Aucun match trouvé.</p>';
} else {
    while ($row = pg_fetch_row($result)) {
        $dateMatch = $row[0];
        $homeTeam = trim($row[1], "'");
        $awayTeam = trim($row[2], "'");
        $idMatch = $row[3];

        if (empty($homeTeam) || empty($awayTeam) || empty($dateMatch)) {
            continue;
        }

        $matchesHtml .= '<div class="card-match">
                            <a href="derby.php?id_match=' . $row[3] . '&home=' . urlencode($homeTeam) . '&away=' . urlencode($awayTeam) . '&id_match=' . urlencode($idMatch) . '">
                                <div class="equipes">
                                    <p class="equipe">' . $homeTeam . '</p>
                                    <p style="font-size: 24px; letter-spacing: 2px;">VS</p>
                                    <p class="equipe">' . $awayTeam . '</p>
                                </div>
                            </a>

                            <p class="date">' . $dateMatch . '</p>';
                            if ($access) {
                                $matchesHtml .= '
                                    <div class="edit-delete">
                                        <a href="edit_match.php?id_match=' . $idMatch . '"><img src="./img/edit.svg"></a>
                                        <a href="delete_match.php?id_match=' . $idMatch . '"><img src="./img/delete.svg"></a>
                                    </div>';
                            }
                        $matchesHtml .= '
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
    <link rel="stylesheet" href="css/match.css">
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
    <div class="match">
        <h1>Les matchs</h1>

        <div class="search-bar">
        <form method="GET" action="match.php">
            <input type="text" name="search_club" placeholder="Rechercher par club" value="' . htmlspecialchars($searchClub) . '">
            <input type="date" name="search_date" value="' . htmlspecialchars($searchDate) . '">
            <button type="submit">Rechercher</button>
        </form>
    </div>
        <div class="container-match">' . $matchesHtml . '</div>
    </div>

    <footer>
        <p>&copy; 2025 Foot Sphere - Tous droits réservés.</p> 
        <a href="mention_legale.php">Mention Légale</a>
    </footer>
</body>
</html>';

echo $html;
?>
