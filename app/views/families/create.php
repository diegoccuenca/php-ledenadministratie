<h1>Familie toevoegen</h1>
<form method="post" action="index.php?action=store">
    <?php csrf_field(); ?>
    <p><label>Naam: <input type="text" name="naam" maxlength="100" required></label></p>
    <p><label>Adres: <input type="text" name="adres" maxlength="255" required></label></p>
    <button type="submit">Opslaan</button>
</form>
<p><a href="index.php">Annuleren</a></p>
