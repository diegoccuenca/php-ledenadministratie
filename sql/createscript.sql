-- Eindopdracht PHP - stap 1
-- Nieuwe, aparte database. De oude database 'ledenadministratie' blijft bestaan.
-- 5 tabellen; 2 relaties via familieleden.

CREATE DATABASE IF NOT EXISTS ledenadministratie_eindop
  CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;
USE ledenadministratie_eindop;

CREATE TABLE IF NOT EXISTS families (
    id INT AUTO_INCREMENT PRIMARY KEY,
    naam VARCHAR(100) NOT NULL,
    adres VARCHAR(255) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

CREATE TABLE IF NOT EXISTS soort_lid (
    id INT AUTO_INCREMENT PRIMARY KEY,
    omschrijving VARCHAR(100) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

CREATE TABLE IF NOT EXISTS familieleden (
    id INT AUTO_INCREMENT PRIMARY KEY,
    naam VARCHAR(100) NOT NULL,
    geboortedatum DATE NOT NULL,
    familie_id INT NOT NULL,
    soort_lid_id INT NOT NULL,
    CONSTRAINT fk_familieleden_families
        FOREIGN KEY (familie_id) REFERENCES families(id),
    CONSTRAINT fk_familieleden_soort_lid
        FOREIGN KEY (soort_lid_id) REFERENCES soort_lid(id)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- De bestaande AuthController zoekt deze velden in de tabel 'users'.
-- We voegen bewust geen standaardaccount of onveilig wachtwoord toe.
CREATE TABLE IF NOT EXISTS users (
    id INT AUTO_INCREMENT PRIMARY KEY,
    naam VARCHAR(100) NOT NULL,
    email VARCHAR(190) NOT NULL UNIQUE,
    wachtwoord VARCHAR(255) NOT NULL,
    rol VARCHAR(30) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- Boekjaren zijn al zichtbaar in de bestaande view; de selectie
-- wordt in een latere stap functioneel gekoppeld.
CREATE TABLE IF NOT EXISTS boekjaar (
    id INT AUTO_INCREMENT PRIMARY KEY,
    jaar SMALLINT NOT NULL UNIQUE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

INSERT IGNORE INTO boekjaar (jaar) VALUES (2025), (2026);

-- Voorbeeld lidsoort voor de formulierkeuzelijst.
INSERT IGNORE INTO soort_lid (id, omschrijving) VALUES (1, 'StandaardLid');
