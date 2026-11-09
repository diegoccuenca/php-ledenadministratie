<?php
require_once 'app/models/Familielid.php';

class FamilielidController2
{
    public function index()
    {
        return (new Familielid())->getFamilieleden();
    }

    public function create()
    {
        $familielid = new Familielid();
        $families = $familielid->getFamilies();
        $soorten = $familielid->getSoorten();
        require 'app/views/familieleden/create.php';
    }

    public function store()
    {
        controleer_post();
        $naam = tekstveld('naam', 'naam', 100);
        $geboortedatum = geboortedatum_veld();
        $familieId = geldig_id($_POST['familie_id'] ?? null, 'Familie');
        $soortId = geldig_id($_POST['soort_lid_id'] ?? null, 'Soort lid');
        try {
            (new Familielid())->createFamilielid($naam, $geboortedatum, $familieId, $soortId);
        } catch (PDOException $e) {
            aanvraag_fout('Toevoegen is niet gelukt. Controleer of de familie en soort lid bestaan.');
        }
        header('Location: index.php?action=familieleden');
        exit;
    }

    public function delete()
    {
        controleer_post();
        $id = geldig_id($_POST['id'] ?? null, 'Familielid-ID');
        (new Familielid())->deleteFamilielid($id);
        header('Location: index.php?action=familieleden');
        exit;
    }

    public function edit()
    {
        $id = geldig_id($_GET['id'] ?? null, 'Familielid-ID');
        $familielid = new Familielid();
        $row = $familielid->getFamilielidById($id)->fetch(PDO::FETCH_ASSOC);
        if (!$row) {
            aanvraag_fout('Dit familielid bestaat niet.', 404);
        }
        $families = $familielid->getFamilies();
        $soorten = $familielid->getSoorten();
        require 'app/views/familieleden/edit.php';
    }

    public function update()
    {
        controleer_post();
        $id = geldig_id($_POST['id'] ?? null, 'Familielid-ID');
        $naam = tekstveld('naam', 'naam', 100);
        $geboortedatum = geboortedatum_veld();
        $familieId = geldig_id($_POST['familie_id'] ?? null, 'Familie');
        $soortId = geldig_id($_POST['soort_lid_id'] ?? null, 'Soort lid');
        try {
            (new Familielid())->updateFamilielid($id, $naam, $geboortedatum, $familieId, $soortId);
        } catch (PDOException $e) {
            aanvraag_fout('Wijzigen is niet gelukt. Controleer of de familie en soort lid bestaan.');
        }
        header('Location: index.php?action=familieleden');
        exit;
    }
}
