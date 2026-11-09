<?php

require_once 'config/Database.php';

class AuthController
{
    private function database(): PDO
    {
        $verbinding = (new Database())->connect();
        if (!$verbinding instanceof PDO) {
            throw new RuntimeException('Kan niet verbinden met de database.');
        }
        return $verbinding;
    }

    private function heeftGebruikers(): bool
    {
        return (int) $this->database()->query('SELECT COUNT(*) FROM users')->fetchColumn() > 0;
    }

    private function geldigeToken(): bool
    {
        return isset($_SESSION['csrf_token'], $_POST['csrf_token'])
            && is_string($_POST['csrf_token'])
            && hash_equals($_SESSION['csrf_token'], $_POST['csrf_token']);
    }

    public function showLogin(string $foutmelding = ''): void
    {
        if (isset($_SESSION['user'])) {
            header('Location: index.php');
            exit;
        }

        if (empty($_SESSION['csrf_token'])) {
            $_SESSION['csrf_token'] = bin2hex(random_bytes(32));
        }

        // Alleen wanneer de database nog geen gebruikers heeft, is het
        // mogelijk om via het inlogscherm het eerste account aan te maken.
        $eersteAccount = !$this->heeftGebruikers();
        require 'app/views/auth/login.php';
    }

    public function registreerEersteGebruiker(): void
    {
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            http_response_code(405);
            exit('Ongeldige aanvraag.');
        }

        if (!$this->geldigeToken()) {
            $this->showLogin('Formulier verlopen. Vernieuw de pagina en probeer opnieuw.');
            return;
        }

        if ($this->heeftGebruikers()) {
            $this->showLogin('Er is al een account. Log daarmee in.');
            return;
        }

        $naam = is_string($_POST['naam'] ?? null) ? trim($_POST['naam']) : '';
        $email = is_string($_POST['email'] ?? null) ? strtolower(trim($_POST['email'])) : '';
        $wachtwoord = is_string($_POST['wachtwoord'] ?? null) ? $_POST['wachtwoord'] : '';
        $bevestiging = is_string($_POST['bevestig_wachtwoord'] ?? null) ? $_POST['bevestig_wachtwoord'] : '';

        if ($naam === '' || strlen($naam) > 100 || !filter_var($email, FILTER_VALIDATE_EMAIL)
            || strlen($email) > 190 || strlen($wachtwoord) < 12
            || $wachtwoord !== $bevestiging) {
            $this->showLogin('Controleer naam, e-mailadres en wachtwoord (minimaal 12 tekens).');
            return;
        }

        $verbinding = $this->database();
        $stmt = $verbinding->prepare(
            'INSERT INTO users (naam, email, wachtwoord, rol) VALUES (:naam, :email, :wachtwoord, :rol)'
        );
        $stmt->execute([
            ':naam' => $naam,
            ':email' => $email,
            ':wachtwoord' => password_hash($wachtwoord, PASSWORD_DEFAULT),
            ':rol' => 'beheerder',
        ]);

        session_regenerate_id(true);
        $_SESSION['user'] = [
            'id' => (int) $verbinding->lastInsertId(),
            'naam' => $naam,
            'rol' => 'beheerder',
        ];

        header('Location: index.php');
        exit;
    }

    public function login(): void
    {
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            http_response_code(405);
            exit('Ongeldige aanvraag.');
        }

        if (!$this->geldigeToken()) {
            $this->showLogin('Formulier verlopen. Vernieuw de pagina en probeer opnieuw.');
            return;
        }

        $email = is_string($_POST['email'] ?? null) ? strtolower(trim($_POST['email'])) : '';
        $wachtwoord = is_string($_POST['wachtwoord'] ?? null) ? $_POST['wachtwoord'] : '';

        $stmt = $this->database()->prepare(
            'SELECT id, naam, email, wachtwoord, rol FROM users WHERE email = :email LIMIT 1'
        );
        $stmt->execute([':email' => $email]);
        $gebruiker = $stmt->fetch(PDO::FETCH_ASSOC);

        if (!$gebruiker || !password_verify($wachtwoord, $gebruiker['wachtwoord'])) {
            $this->showLogin('E-mailadres of wachtwoord onjuist.');
            return;
        }

        session_regenerate_id(true);
        // Sla geen wachtwoordhash op in de sessie.
        $_SESSION['user'] = [
            'id' => (int) $gebruiker['id'],
            'naam' => $gebruiker['naam'],
            'rol' => $gebruiker['rol'],
        ];

        header('Location: index.php');
        exit;
    }

    public function logout(): void
    {
        if ($_SERVER['REQUEST_METHOD'] !== 'POST' || !$this->geldigeToken()) {
            http_response_code(403);
            exit('Ongeldige uitlogaanvraag.');
        }

        $_SESSION = [];
        if (ini_get('session.use_cookies')) {
            $opties = session_get_cookie_params();
            setcookie(session_name(), '', [
                'expires' => time() - 3600,
                'path' => $opties['path'],
                'domain' => $opties['domain'],
                'secure' => $opties['secure'],
                'httponly' => $opties['httponly'],
                'samesite' => $opties['samesite'] ?? 'Lax',
            ]);
        }
        session_destroy();

        header('Location: index.php?action=showLogin');
        exit;
    }
}
