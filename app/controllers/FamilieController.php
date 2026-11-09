<?php
require_once 'app/models/Familie.php';

class FamilieController
{
    public function index()
    {
        return (new Familie())->getFamiliesMetContributie();
    }

    public function create()
    {
        require 'app/views/families/create.php';
    }

    public function store()
    {
        controleer_post();
        $naam = tekstveld('naam', 'naam', 100);
        $adres = tekstveld('adres', 'adres', 255);
        (new Familie())->createFamilie($naam, $adres);
        header('Location: index.php');
        exit;
    }

    public function delete()
    {
        controleer_post();
        $id = geldig_id($_POST['id'] ?? null, 'Familie-ID');
        try {
            (new Familie())->deleteFamilie($id);
        } catch (PDOException $e) {
            aanvraag_fout('Deze familie kan niet worden verwijderd. Verwijder eerst de gekoppelde familieleden.');
        }
        header('Location: index.php');
        exit;
    }

    public function edit()
    {
        $id = geldig_id($_GET['id'] ?? null, 'Familie-ID');
        $row = (new Familie())->getFamilieById($id)->fetch(PDO::FETCH_ASSOC);
        if (!$row) {
            aanvraag_fout('Deze familie bestaat niet.', 404);
        }
        require 'app/views/families/edit.php';
    }

    public function update()
    {
        controleer_post();
        $id = geldig_id($_POST['id'] ?? null, 'Familie-ID');
        $naam = tekstveld('naam', 'naam', 100);
        $adres = tekstveld('adres', 'adres', 255);
        (new Familie())->updateFamilie($id, $naam, $adres);
        header('Location: index.php');
        exit;
    }
}
