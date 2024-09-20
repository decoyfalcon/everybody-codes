<?php
class Database
{
    public $pdo;
    private $sqliteDB;

    public function __construct($sqliteDB = null)
    {
        $this->sqliteDB = $sqliteDB ?: dirname(__DIR__, 2) . '\data\database.db';

        if(!file_exists($this->sqliteDB)) {
            die("Database file does not exist");
        }

        $this->connect();
    }

    private function connect(){
        try {
            $this->pdo = new PDO('sqlite:'.$this->sqliteDB);
            $this->pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
        } catch (PDOException $e) {
            die("Error connecting to database:" . $e->getMessage());
        }
    }

    public function createTable(){
        $cmd = "CREATE TABLE IF NOT EXISTS cameras (
                 id INTEGER(10),
                 adres VARCHAR(255),
                 latitude VARCHAR(255),
                 longitude VARCHAR(255))";

        try {
            $this->pdo->exec($cmd);
        } catch (PDOException $e) {
            die("Error creating table: " . $e->getMessage());
        }
    }

    public function isTableEmpty(){ #controleert of de tabel daadwerlijk leeg is
        $cmd = "SELECT COUNT(*) FROM cameras";
        $stmt = $this->pdo->query($cmd);
        $count = $stmt->fetchColumn();

        return $count == 0;
    }

    public function importDataFromCsv(){ #importeert data uit csv file
        if($this->isTableEmpty()){ #doet dit alleen als de table ook leeg is
            if (($handle = fopen("data/cameras-defb.csv", "r")) !== FALSE) {

                while (($row = fgetcsv($handle, 100, ";")) !== FALSE) {
                    if (count($row) >= 3) {

                        $naam = $row[0];
                        $split_naam = preg_split("/[\s-]+/", $naam);
                        $id = $split_naam[2] ?? 0;

                        $cmd = "INSERT INTO cameras (id, adres, latitude, longitude) VALUES (:id, :adres, :latitude, :longitude)";
                        $stmt = $this->pdo->prepare($cmd);

                        try{
                            $stmt->execute([
                                ":id" => $id,
                                ":adres" => $row[0],
                                ":latitude" => $row[1],
                                ":longitude" => $row[2],
                            ]);
                        } catch (PDOException $e) {
                            die("Error creating inserting data: " . $e->getMessage());
                        }
                    }
                }
                fclose($handle); #sluit csv file
            }
        }
    }
}

/* <?php
//gegevens voor database:
$DB_HOST = "127.0.0.1";
$DB_NAME = "database";
$DB_USER = "root";
$DB_PASS = "root";


//probeer verbinding te maken met db, geeft error terug als het niet lukt
try{
    $pdo = new PDO("mysql:host=$DB_HOST;dbname=$DB_NAME", $DB_USER, $DB_PASS);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
} catch (PDOException $e) {
    die("Connection failed: " . $e->getMessage());
}

#$db = mysqli_connect($DB_HOST, $DB_USER, $DB_PASS, $DB_NAME) or die("Error: " . mysqli_connect_error());

$sql = "CREATE DATABASE IF NOT EXISTS $DB_NAME";


#stopt de gegevens uit de csv file in de database
if (($handle = fopen("data/cameras-defb.csv", "r")) !== FALSE) {

    while (($row = fgetcsv($handle, 100, ";")) !== FALSE) {
        if(count($row) >= 3) {
            $sql = "INSERT INTO cameras (adres, latitude, longitude) VALUES (:adres, :latitude, :longitude)";
            $stmt = $pdo->prepare($sql);

            $stmt->execute([
                ":adres" => $row[0],
                ":latitude" => $row[1],
                ":longitude" => $row[2],
            ]);
        }
    }

    fclose($handle);
}
?> */