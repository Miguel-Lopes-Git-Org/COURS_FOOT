<?php

include_once('env.php');

function verifUtilisateur($nom, $mdp) {

    $conn = getPgConnection();

    $sql = "SELECT * FROM utilisateur WHERE identifiant = '$nom' AND password = '$mdp';";
    $result = pg_query($conn, $sql);

    if (!$result) {
        die("Erreur dans la requête SQL : " . pg_last_error());
    }

    $row = pg_fetch_row($result);

    if ($row) {
        $access = true;
    } else {
        $access = false;
    }

    pg_free_result($result);

    pg_close($conn);

    return $access;
}


?>
