# PHP Ledenadministratie

Een webapplicatie voor het beheren van families en familieleden, ontwikkeld als schoolproject met **PHP (MVC-structuur)** en **MySQL**. De applicatie werkt lokaal, bijvoorbeeld met AMPPS.

## Functionaliteiten

- Registratie van het eerste beheerdersaccount, inloggen en uitloggen met PHP-sessies.
- Families toevoegen, bekijken, wijzigen en verwijderen (CRUD).
- Familieleden toevoegen, bekijken, wijzigen en verwijderen (CRUD).
- Familieleden koppelen aan een familie en een lidsoort.
- Contributie per familie berekenen op basis van leeftijdskortingen.
- Basisbescherming met prepared statements, CSRF-tokens en output-escaping.

**Let op:** de boekjaarselectie in het overzicht is op dit moment alleen een visuele keuzelijst; deze verandert de berekening nog niet.

## Technologie

- PHP 8.2 of hoger
- MySQL 8 / PDO
- HTML
- MVC-achtige projectstructuur met models, controllers en views

## Installatie (lokaal)

1. Installeer en start Apache en MySQL, bijvoorbeeld via **AMPPS**.
2. Plaats de **inhoud** van deze projectmap in de webroot van AMPPS (bijvoorbeeld onder `www/ledenadministratie`).
3. Open phpMyAdmin en importeer `sql/createscript.sql`. Dit maakt de database en tabellen aan.
4. Kopieer `config/database.local.php.example` naar `config/database.local.php` en vul je lokale MySQL-gebruikersnaam en wachtwoord in.
5. Open `http://localhost/ledenadministratie/index.php` in de browser (pas het pad of de poort aan als jouw AMPPS-instelling afwijkt).
6. Als de tabel `users` nog leeg is, kun je via het inlogscherm één eerste beheerder registreren.

De echte database-inloggegevens staan uitsluitend in `config/database.local.php`. Dit bestand wordt door `.gitignore` uitgesloten.

## Projectstructuur

```text
app/
  controllers/    # Requestafhandeling en acties
  models/         # Databasequeries en berekeningen
  views/          # PHP/HTML-templates
  security.php    # Hulpfuncties voor formuliervalidatie, CSRF en escaping
config/
  Database.php                  # PDO-verbinding
  database.local.php.example    # Voorbeeldconfiguratie
sql/
  createscript.sql              # Databaseschema met veilige voorbeeldwaarden
index.php                       # Router en sessiestart
```

## Beveiliging en privacy

De GitHub-versie bevat **geen echte databasewachtwoorden, SQL-export met accounts of persoonsgegevens**. Publiceer nooit `config/database.local.php`, database-back-ups of gebruikerstabellen. De oorspronkelijke export en eventuele schooldocumenten zijn bewust uitgesloten.

Dit project is bedoeld om de gebruikte programmeertechnieken te laten zien; het is niet getest of ingericht voor een publieke productieomgeving.

## Wat ik heb geleerd

Met dit project heb ik geoefend met databases en relaties, PHP-controllers en models, CRUD-bewerkingen, authenticatie, formuliercontrole en veilige SQL-queries.
