<?php
// Kleine herbruikbare functies voor veilige formulieren.
function h($waarde): string
{
    return htmlspecialchars((string) $waarde, ENT_QUOTES | ENT_SUBSTITUTE, 'UTF-8');
}

function csrf_field(): void
{
    echo '<input type="hidden" name="csrf_token" value="' . h($_SESSION['csrf_token'] ?? '') . '">';
}

function aanvraag_fout(string $melding, int $status = 400): void
{
    http_response_code($status);
    echo '<!doctype html><html lang="nl"><meta charset="utf-8">';
    echo '<title>Er ging iets mis</title><h1>Er ging iets mis</h1>';
    echo '<p>' . h($melding) . '</p>';
    echo '<p><a href="index.php">Terug naar de Ledenadministratie</a></p></html>';
    exit;
}

function controleer_post(): void
{
    if (($_SERVER['REQUEST_METHOD'] ?? '') !== 'POST') {
        aanvraag_fout('Deze handeling moet via een formulier worden uitgevoerd.', 405);
    }
    $token = $_POST['csrf_token'] ?? null;
    if (!is_string($token) || !isset($_SESSION['csrf_token']) ||
        !hash_equals($_SESSION['csrf_token'], $token)) {
        aanvraag_fout('Formulier verlopen. Ga terug, vernieuw de pagina en probeer opnieuw.', 403);
    }
}

function geldig_id($waarde, string $veld = 'Nummer'): int
{
    $id = filter_var($waarde, FILTER_VALIDATE_INT, ['options' => ['min_range' => 1]]);
    if ($id === false) {
        aanvraag_fout($veld . ' is ongeldig.');
    }
    return $id;
}

function tekstveld(string $veld, string $label, int $maxLengte): string
{
    $waarde = $_POST[$veld] ?? null;
    if (!is_string($waarde)) {
        aanvraag_fout('Vul ' . $label . ' in.');
    }
    $waarde = trim($waarde);
    if ($waarde === '' || strlen($waarde) > $maxLengte) {
        aanvraag_fout($label . ' is verplicht en mag maximaal ' . $maxLengte . ' tekens bevatten.');
    }
    return $waarde;
}

function geboortedatum_veld(): string
{
    $waarde = $_POST['geboortedatum'] ?? null;
    if (!is_string($waarde)) {
        aanvraag_fout('Vul een geldige geboortedatum in.');
    }
    $datum = DateTimeImmutable::createFromFormat('!Y-m-d', $waarde);
    if (!$datum || $datum->format('Y-m-d') !== $waarde ||
        $datum > new DateTimeImmutable('today')) {
        aanvraag_fout('Vul een geldige geboortedatum in die niet in de toekomst ligt.');
    }
    return $waarde;
}
