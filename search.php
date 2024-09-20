<?php
function laadCameras()
{
    $cameras = [];
    if (($handle = fopen("data/cameras-defb.csv", "r")) !== false) {
        while (($data = fgetcsv($handle, 100, ";")) !== false) {

            if (count($data) < 3) {
                continue; #sla deze regel over als er minder dan 3 kolommen zijn
            }

            $naam = $data[0];
            $split_naam = preg_split("/[\s-]+/", $naam); #split de straatnaam string op spaties en -

            if (count($split_naam) < 3) {
                continue;
            }

            $id = $split_naam[2]; #id voor de camera

            $cameras[] = [
                'nummer' => $id,
                'naam' => $data[0],
                'latitude' => $data[1],
                'longitude' => $data[2]
            ];
        }
        fclose($handle);
    }
    return $cameras;
}

function zoekCameras($cameras, $name)
{
    return array_filter($cameras, function ($camera) use ($name) {
        return stripos($camera['naam'], $name) !== false;
    });
}

function main()
{
    $options = getopt("", ["name:"]);

    if (!isset($options['name'])) {
        echo "php search.php --name <straatnaam>\n";
        exit(1);
    }

    $cameras = laadCameras();
    $results = zoekCameras($cameras, $options['name']);

    foreach ($results as $camera) {
        echo "{$camera['nummer']} | {$camera['naam']} | {$camera['latitude']} | {$camera['longitude']}\n";
    }
}

main();