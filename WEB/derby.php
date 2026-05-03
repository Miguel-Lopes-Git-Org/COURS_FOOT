<?php
include_once('env.php');

$conn = getPgConnection();

if (!isset($_GET['id_match'])) {
    die("ID du match manquant.");
}

$id_match = $_GET['id_match'];
$home = $_GET['home'] ?? 'Équipe A';
$away = $_GET['away'] ?? 'Équipe B';

$sql = "SELECT e.time_event, p.name_player, t.name_team, te.lib 
        FROM event e
        JOIN player p ON e.player = p.id_player
        JOIN team t ON e.team = t.id_team
        JOIN type_de_event te ON e.type = te.type
        WHERE e.match_event = $1
        ORDER BY e.time_event ASC;";
$result = pg_query_params($conn, $sql, [$id_match]);

if (!$result) {
    die("Erreur dans la requête SQL : " . pg_last_error());
}

$eventsHtml = '';
$homeScore = 0;
$awayScore = 0;

while ($row = pg_fetch_assoc($result)) {
    $time = $row['time_event'];
    $player = trim($row['name_player'], "'");
    $team = trim($row['name_team'], "'");
    $event = $row['lib'];

    $icon = match ($event) {
        'but' => '⚽',
        'but sur penalty' => '⚽ (P)',
        'but contre son camp' => '⚽ (CSC)',
        'carton jaune' => '🟨',
        'deuxième carton jaune' => '🟨 🟨',
        'carton rouge' => '🟥',
        default => '❓',
    };

    if (in_array($event, ['but', 'but sur penalty', 'but contre son camp'])) {
        if ($team === $home) {
            $homeScore++;
        } elseif ($team === $away) {
            $awayScore++;
        }
    }

    $alignment = $team === $home ? 'left' : 'right';
    $eventsHtml .= "<div class='event $alignment'>
                        <span>$time'  $icon</span> <span>$player</span>
                    </div>";
}

pg_close($conn);

$html = '<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Derby</title>
    <link rel="stylesheet" href="css/style.css">
    <link rel="stylesheet" href="css/derby.css">
</head>
<body>
    <nav>
        <ul>
            <li><a href="index.php">Accueil</a></li>
            <li><a href="match.php">Match</a></li>
            <li><a href="joueurs.php">Joueurs</a></li>
        </ul>
    </nav>
    <section>
        <div class="scoreboard">
            <div class="team">' . htmlspecialchars($home) . '</div>
            <div class="score">' . $homeScore . ' - ' . $awayScore . '</div>
            <div class="team">' . htmlspecialchars($away) . '</div>
        </div>
        <div class="events">' . $eventsHtml . '</div>
    </section>
    <footer>
        <p>&copy; 2025 Foot Sphere - Tous droits réservés.</p> 
        <a href="">Mention Légale</a>
    </footer>
</body>
</html>';

echo $html;
?>
