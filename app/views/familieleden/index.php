<h1>Familieleden</h1>
<a href="index.php?action=createFamilielid">Familielid toevoegen</a>
<br><br>
<table border="1">
    <tr><th>ID</th><th>Naam</th><th>Geboortedatum</th><th>Familie</th><th>Soort lid</th><th>Acties</th></tr>
    <?php while ($row = $result->fetch(PDO::FETCH_ASSOC)): ?>
        <tr>
            <td><?= h($row['id']) ?></td>
            <td><?= h($row['naam']) ?></td>
            <td><?= h($row['geboortedatum']) ?></td>
            <td><?= h($row['familienaam']) ?></td>
            <td><?= h($row['soortnaam']) ?></td>
            <td>
                <a href="index.php?action=editFamilielid&amp;id=<?= h($row['id']) ?>">Wijzigen</a>
                |
                <form method="post" action="index.php?action=deleteFamilielid" style="display:inline" onsubmit="return confirm('Weet je zeker dat je dit familielid wilt verwijderen?')">
                    <?php csrf_field(); ?>
                    <input type="hidden" name="id" value="<?= h($row['id']) ?>">
                    <button type="submit">Verwijderen</button>
                </form>
            </td>
        </tr>
    <?php endwhile; ?>
</table>
<p><a href="index.php">Terug naar families</a></p>
