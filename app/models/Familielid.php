<?php
require_once 'config/Database.php';

class Familielid
{
    private $conn;
    private $table = "familieleden";

    public function __construct()
    {
        $this->conn = (new Database())->connect();
    }

    public function getFamilieleden()
    {
        $query = "SELECT familieleden.*, families.naam AS familienaam, soort_lid.omschrijving AS soortnaam
                  FROM " . $this->table . "
                  INNER JOIN families ON familieleden.familie_id = families.id
                  INNER JOIN soort_lid ON familieleden.soort_lid_id = soort_lid.id";
        $stmt = $this->conn->prepare($query);
        $stmt->execute();
        return $stmt;
    }

    public function getFamilies()
    {
        $stmt = $this->conn->prepare("SELECT * FROM families");
        $stmt->execute();
        return $stmt;
    }

    public function getSoorten()
    {
        $stmt = $this->conn->prepare("SELECT * FROM soort_lid");
        $stmt->execute();
        return $stmt;
    }

    public function createFamilielid($naam, $geboortedatum, $familie_id, $soort_lid_id)
    {
        $query = "INSERT INTO " . $this->table . "
                  (naam, geboortedatum, familie_id, soort_lid_id)
                  VALUES (:naam, :geboortedatum, :familie_id, :soort_lid_id)";
        $stmt = $this->conn->prepare($query);
        return $stmt->execute([
            ':naam' => $naam,
            ':geboortedatum' => $geboortedatum,
            ':familie_id' => $familie_id,
            ':soort_lid_id' => $soort_lid_id
        ]);
    }

    public function deleteFamilielid($id)
    {
        $stmt = $this->conn->prepare("DELETE FROM " . $this->table . " WHERE id = :id");
        return $stmt->execute([':id' => $id]);
    }

    public function getFamilielidById($id)
    {
        $stmt = $this->conn->prepare("SELECT * FROM " . $this->table . " WHERE id = :id");
        $stmt->execute([':id' => $id]);
        return $stmt;
    }

    public function updateFamilielid($id, $naam, $geboortedatum, $familie_id, $soort_lid_id)
    {
        $query = "UPDATE " . $this->table . "
                  SET naam = :naam, geboortedatum = :geboortedatum,
                      familie_id = :familie_id, soort_lid_id = :soort_lid_id
                  WHERE id = :id";
        $stmt = $this->conn->prepare($query);
        return $stmt->execute([
            ':id' => $id,
            ':naam' => $naam,
            ':geboortedatum' => $geboortedatum,
            ':familie_id' => $familie_id,
            ':soort_lid_id' => $soort_lid_id
        ]);
    }
}
