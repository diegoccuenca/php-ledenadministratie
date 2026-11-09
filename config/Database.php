<?php

class Database
{
    public $conn;

    public function connect(): PDO
    {
        // De werkelijke inloggegevens staan uitsluitend in een lokaal bestand.
        // Kopieer database.local.php.example naar database.local.php.
        $configPath = __DIR__ . '/database.local.php';
        if (!is_file($configPath)) {
            throw new RuntimeException('Databaseconfiguratie ontbreekt. Zie README.md.');
        }

        $config = require $configPath;
        if (!is_array($config) || !isset($config['host'], $config['dbname'], $config['username'], $config['password'])) {
            throw new RuntimeException('Databaseconfiguratie is onvolledig.');
        }

        try {
            $this->conn = new PDO(
                sprintf('mysql:host=%s;dbname=%s;charset=utf8mb4', $config['host'], $config['dbname']),
                $config['username'],
                $config['password'],
                [PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION]
            );
        } catch (PDOException $e) {
            // Toon geen databasegegevens in foutmeldingen.
            throw new RuntimeException('Databaseverbinding mislukt. Controleer je lokale instellingen.', 0, $e);
        }

        return $this->conn;
    }
}
