<?php

function loadEnv(string $path): void
{
    static $loaded = false;

    if ($loaded || !file_exists($path)) {
        return;
    }

    $lines = file($path, FILE_IGNORE_NEW_LINES | FILE_SKIP_EMPTY_LINES);
    if ($lines === false) {
        return;
    }

    foreach ($lines as $line) {
        $line = trim($line);
        if ($line === '' || str_starts_with($line, '#')) {
            continue;
        }

        $parts = explode('=', $line, 2);
        if (count($parts) !== 2) {
            continue;
        }

        $key = trim($parts[0]);
        $value = trim($parts[1]);
        $value = trim($value, "\"'");

        putenv($key . '=' . $value);
        $_ENV[$key] = $value;
        $_SERVER[$key] = $value;
    }

    $loaded = true;
}

function envVar(string $key, ?string $default = null): ?string
{
    $value = getenv($key);
    if ($value === false || $value === '') {
        return $default;
    }

    return $value;
}

function getPgConnection()
{
    loadEnv(__DIR__ . '/.env');

    $host = envVar('DB_HOST', '127.0.0.1');
    $name = envVar('DB_NAME', 'foot');
    $user = envVar('DB_USER');
    $pass = envVar('DB_PASS');

    if ($user === null || $pass === null) {
        die("Variables .env manquantes : DB_USER et DB_PASS sont obligatoires.");
    }

    $conn = pg_connect("host={$host} dbname={$name} user={$user} password={$pass}");
    if (!$conn) {
        die("Erreur de connexion : " . pg_last_error());
    }

    return $conn;
}

?>
