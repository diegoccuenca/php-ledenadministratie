<?php
require_once 'config/Database.php';

class Familie
{
    private $conn;
    private $table = "families";

    public function __construct()
    {
        $this->conn = (new Database())->connect();
    }

    public function getFamilies()
    {
        $stmt = $this->conn->prepare("SELECT * FROM " . $this->table);
        $stmt->execute();
        return $stmt;
    }

    public function getFamiliesMetContributie()
    {
        $stmt = $this->conn->prepare("SELECT * FROM families");
        $stmt->execute();
        $families = $stmt->fetchAll(PDO::FETCH_ASSOC);

        foreach ($families as &$familie) {
            $familie['contributie'] = $this->berekenContributie($familie['id']);
        }

        return $families;
    }

    private function berekenContributie($familie_id)
    {
        $stmt = $this->conn->prepare("SELECT geboortedatum FROM familieleden WHERE familie_id = :id");
        $stmt->execute([':id' => $familie_id]);
        $totaal = 0;

        while ($lid = $stmt->fetch(PDO::FETCH_ASSOC)) {
            $leeftijd = date_diff(date_create($lid['geboortedatum']), date_create('today'))->y;
            $korting = 0;

            if ($leeftijd < 8) $korting = 50;
            elseif ($leeftijd <= 12) $korting = 40;
            elseif ($leeftijd <= 17) $korting = 25;
            elseif ($leeftijd >= 51) $korting = 45;

            $totaal += 100 - (100 * $korting / 100);
        }

        return $totaal;
    }

    public function createFamilie($naam, $adres)
    {
        $stmt = $this->conn->prepare("INSERT INTO families (naam, adres) VALUES (:naam, :adres)");
        return $stmt->execute([':naam' => $naam, ':adres' => $adres]);
    }

    public function deleteFamilie($id)
    {
        $stmt = $this->conn->prepare("DELETE FROM families WHERE id = :id");
        return $stmt->execute([':id' => $id]);
    }

    public function getFamilieById($id)
    {
        $stmt = $this->conn->prepare("SELECT * FROM families WHERE id = :id");
        $stmt->execute([':id' => $id]);
        return $stmt;
    }

    public function updateFamilie($id, $naam, $adres)
    {
        $stmt = $this->conn->prepare("UPDATE families SET naam = :naam, adres = :adres WHERE id = :id");
        return $stmt->execute([':id' => $id, ':naam' => $naam, ':adres' => $adres]);
    }
}
