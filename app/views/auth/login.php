<!doctype html>
<html lang="nl">
<head>
    <meta charset="utf-8">
    <title><?= $eersteAccount ? 'Eerste beheerder aanmaken' : 'Inloggen' ?></title>
</head>
<body>
    <h1><?= $eersteAccount ? 'Eerste beheerder aanmaken' : 'Inloggen' ?></h1>

    <?php if ($foutmelding !== ''): ?>
        <p role="alert" style="color:darkred;">
            <?= htmlspecialchars($foutmelding, ENT_QUOTES, 'UTF-8') ?>
        </p>
    <?php endif; ?>

    <?php if ($eersteAccount): ?>
        <p>Er is nog geen gebruiker. Maak eenmalig je beheerder aan.</p>
    <?php endif; ?>

    <form method="post" action="index.php?action=<?= $eersteAccount ? 'registreer' : 'login' ?>">
        <input type="hidden" name="csrf_token" value="<?= htmlspecialchars($_SESSION['csrf_token'], ENT_QUOTES, 'UTF-8') ?>">

        <?php if ($eersteAccount): ?>
            <p><label>Naam: <input type="text" name="naam" maxlength="100" required></label></p>
        <?php endif; ?>

        <p><label>E-mailadres: <input type="email" name="email" maxlength="190" required></label></p>
        <p><label>Wachtwoord: <input type="password" name="wachtwoord" <?= $eersteAccount ? 'minlength="12" ' : '' ?>required></label></p>

        <?php if ($eersteAccount): ?>
            <p><label>Wachtwoord herhalen: <input type="password" name="bevestig_wachtwoord" minlength="12" required></label></p>
        <?php endif; ?>

        <button type="submit"><?= $eersteAccount ? 'Account aanmaken' : 'Inloggen' ?></button>
    </form>
</body>
</html>
