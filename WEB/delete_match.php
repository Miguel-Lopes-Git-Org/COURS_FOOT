<?php
include_once('env.php');

$conn = getPgConnection();

if (isset($_GET['id'])) {
    $id = intval($_GET['id']);
    $sql = "DELETE FROM LesRencontres WHERE id = $id;";
    $result = pg_query($conn, $sql);

    if (!$result) {
        die("Erreur lors de la suppression : " . pg_last_error());
    }
}

pg_close($conn);
header("Location: match.php");
exit;
?>
