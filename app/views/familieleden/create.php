<h1>Familielid toevoegen</h1>
<form method="post" action="index.php?action=storeFamilielid">
    <?php csrf_field(); ?>
    <p><label>Naam: <input type="text" name="naam" maxlength="100" required></label></p>
    <p><label>Geboortedatum: <input type="date" name="geboortedatum" max="<?= date('Y-m-d') ?>" required></label></p>
    <p><label>Familie:
        <select name="familie_id" required>
            <?php while ($familie = $families->fetch(PDO::FETCH_ASSOC)): ?>
                <option value="<?= h($familie['id']) ?>"><?= h($familie['naam']) ?></option>
            <?php endwhile; ?>
        </select>
    </label></p>
    <p><label>Soort lid:
        <select name="soort_lid_id" required>
            <?php while ($soort = $soorten->fetch(PDO::FETCH_ASSOC)): ?>
                <option value="<?= h($soort['id']) ?>"><?= h($soort['omschrijving']) ?></option>
            <?php endwhile; ?>
        </select>
    </label></p>
    <button type="submit">Opslaan</button>
</form>
<p><a href="index.php?action=familieleden">Annuleren</a></p>
