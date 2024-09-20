<!DOCTYPE html>
<html lang="nl">
<head>
    <title>index</title>

    <meta name="description" content="">
    <meta name="author" content="">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=yes">

</head>

<body>
<main>
    <div class="content">
        <?php
        $row = 1; #uit documentatie gehaald, alleen nog in een array proberen te stoppen??
        if (($handle = fopen("data/cameras-defb.csv", "r")) !== FALSE) { #leest opgegeven csv bestand
            while (($data = fgetcsv($handle, 100, ",")) !== FALSE) { #leest data uit bestand, tot 100 regels, breekt op ","
                $num = count($data);

                $row++;

                for ($c=0; $c < $num; $c++) {
                    echo $data[$c] . "<br />\n";
                }
            }
            fclose($handle); #sluit bestand
        }

        if (($handle = fopen("data/cameras-defb.csv", "r")) !== FALSE) {
            $array = [];

            while (($row = fgetcsv($handle, 100, ";")) !== FALSE) {
                if(count($row) >= 3) {
                    $array[$row[0]] = [$row[1], $row[2]]; #maakt een associative array gebaseerd op de waarden in de csv bestand (adres, lat, long)
                }
            }

            fclose($handle);
        }

        #var_dump($array); #ooh, deze geeft meer info
        print_r($array); #print de array
        ?>
    </div>
</main>
</body>
</html>