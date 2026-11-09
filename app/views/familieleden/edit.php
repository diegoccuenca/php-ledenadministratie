<h1>Familielid wijzigen</h1>
<form method="post" action="index.php?action=updateFamilielid">
    <?php csrf_field(); ?>
    <input type="hidden" name="id" value="<?= h($row['id']) ?>">
    <p><label>Naam: <input type="text" name="naam" value="<?= h($row['naam']) ?>" maxlength="100" required></label></p>
    <p><label>Geboortedatum: <input type="date" name="geboortedatum" value="<?= h($row['geboortedatum']) ?>" max="<?= date('Y-m-d') ?>" required></label></p>
    <p><label>Familie:
        <select name="familie_id" required>
            <?php while ($familie = $families->fetch(PDO::FETCH_ASSOC)): ?>
                <option value="<?= h($familie['id']) ?>" <?= (int) $familie['id'] === (int) $row['familie_id'] ? 'selected' : '' ?>><?= h($familie['naam']) ?></option>
            <?php endwhile; ?>
        </select>
    </label></p>
    <p><label>Soort lid:
        <select name="soort_lid_id" required>
            <?php while ($soort = $soorten->fetch(PDO::FETCH_ASSOC)): ?>
                <option value="<?= h($soort['id']) ?>" <?= (int) $soort['id'] === (int) $row['soort_lid_id'] ? 'selected' : '' ?>><?= h($soort['omschrijving']) ?></option>
            <?php endwhile; ?>
        </select>
    </label></p>
    <button type="submit">Wijzigen</button>
</form>
<p><a href="index.php?action=familieleden">Annuleren</a></p>
