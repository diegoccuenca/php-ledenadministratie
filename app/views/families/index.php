<p>
    Ingelogd als: <?= h($_SESSION['user']['naam'] ?? 'gast') ?> |
    Rol: <?= h($_SESSION['user']['rol'] ?? 'geen') ?> |
    <form method="post" action="index.php?action=logout" style="display:inline">
        <?php csrf_field(); ?>
        <button type="submit">Uitloggen</button>
    </form>
</p>
<h1>Families</h1>
<form>
    <label>Boekjaar:</label>
    <select><option>2025</option><option>2026</option></select>
</form>
<br>
<a href="index.php?action=create">Familie toevoegen</a>
<br><br>
<a href="index.php?action=familieleden">Familieleden bekijken</a>
<br><br>
<table border="1">
    <tr><th>ID</th><th>Naam</th><th>Adres</th><th>Contributie</th><th>Acties</th></tr>
    <?php foreach ($result as $row): ?>
        <tr>
            <td><?= h($row['id']) ?></td>
            <td><?= h($row['naam']) ?></td>
            <td><?= h($row['adres']) ?></td>
            <td>€ <?= h(number_format((float) $row['contributie'], 2, ',', '.')) ?></td>
            <td>
                <a href="index.php?action=edit&amp;id=<?= h($row['id']) ?>">Wijzigen</a>
                |
                <form method="post" action="index.php?action=delete" style="display:inline" onsubmit="return confirm('Weet je zeker dat je deze familie wilt verwijderen?')">
                    <?php csrf_field(); ?>
                    <input type="hidden" name="id" value="<?= h($row['id']) ?>">
                    <button type="submit">Verwijderen</button>
                </form>
            </td>
        </tr>
    <?php endforeach; ?>
</table>
