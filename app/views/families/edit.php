<h1>Familie wijzigen</h1>
<form method="post" action="index.php?action=update">
    <?php csrf_field(); ?>
    <input type="hidden" name="id" value="<?= h($row['id']) ?>">
    <p><label>Naam: <input type="text" name="naam" value="<?= h($row['naam']) ?>" maxlength="100" required></label></p>
    <p><label>Adres: <input type="text" name="adres" value="<?= h($row['adres']) ?>" maxlength="255" required></label></p>
    <button type="submit">Wijzigen</button>
</form>
<p><a href="index.php">Annuleren</a></p>
