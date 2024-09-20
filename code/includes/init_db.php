<?php
    require "database.php";

    $db = new Database(); #maak database aan
    $db->createTable(); #maak tabel aan als hij nog niet bestaat
    $db->importDataFromCsv(); #importeer data van csv bestand
?>