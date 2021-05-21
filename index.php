<?php
include('./src/Incidence.php');

### Configure here ###

# Find your region here and get the OBJECTid: 
# https://npgeo-corona-npgeo-de.hub.arcgis.com/datasets/917fc37a709542548cc3be077a786c17_0
$idKN = 211; //konstanz
$idSW = 209; //Schwarzwald Baar
$cache_file_KN = './data/dataKn.json';
$cache_file_SW = './data/dataSw.json';
$threshold = 100;


### End of configs ###


$incidenceKN = new Incidence($idKN, $cache_file_KN);
$incidenceSW = new Incidence($idSW, $cache_file_SW);

$todayKN = $incidenceKN->getDaily(0);
$todaySW = $incidenceSW->getDaily(0);

echo "<div class='widget'>";

echo "<h3>Inzidenz-Ampel für " . $todayKN['GEN'] . "</h3>";
echo "<h6>(Fälle pro 100.000 Einwohner in 7 Tagen)</h6>";

drawStoplight($todayKN, $threshold);

echo "<table id='tbl_incidence'>";
echo drawLine($todayKN);
echo drawLine($incidenceKN->getDaily(1));
echo drawLine($incidenceKN->getDaily(2));
echo "</table>";
echo "<h6>Quelle: <a href='https://www.rki.de/DE/Home/homepage_node.html'>RKI</a></h6>";

echo "<p><a href='/details.php'>Details</a><p>";
echo "</div>";


echo "<div class='widget'>";

echo "<h3>Inzidenz-Ampel für " . $todaySW['GEN'] . "</h3>";
echo "<h6>(Fälle pro 100.000 Einwohner in 7 Tagen)</h6>";

drawStoplight($todaySW, $threshold);

echo "<table id='tbl_incidence'>";
echo drawLine($todaySW);
echo drawLine($incidenceSW->getDaily(1));
echo drawLine($incidenceSW->getDaily(2));
echo "</table>";
echo "<h6>Quelle: <a href='https://www.rki.de/DE/Home/homepage_node.html'>RKI</a></h6>";

echo "<p><a href='/detailsSW.php'>Details</a><p>";
echo "</div>";


function drawLine($data)
{
    if ($data) {

        $inc = round($data['cases7_per_100k'], 2);
        if ($inc < 100) {
            $co = "value_ok";
        } else {
            $co = "value_stop";
        }

        echo "<tr>
                <td>" . germanDay($data['ts']) . ", " . date("d.m.Y", $data['ts']) . "</td>
                <td class='" . $co . "'>" . round($data['cases7_per_100k'], 2) . "</td>
            </tr>";
    }
}

function drawStoplight($data, $threshold)
{
    if ($data['cases7_per_100k'] > $threshold) {
        $color = "stoplight_stop";
        $text = "Geschlossen";
    } else {
        $color = "stoplight_ok";
        $text = "Geöffnet";
    }
    echo "<div id='div_stoplight' class='" . $color . "'>";
    echo $text;
    echo "</div>";
}

function germanDay($ts)
{
    $d = [
        1 => "Montag",
        2 => "Dienstag",
        3 => "Mittwoch",
        4 => "Donnerstag",
        5 => "Freitag",
        6 => "Samstag",
        7 => "Sonntag"
    ];
    return $d[date("N", $ts)];
}

?>
<style>
    body,
    html {
        font-family: Arial, Helvetica, sans-serif;
    }

    h3 {
        text-align: center;
        margin: 1%;
    }

    h6 {
        text-align: center;
        margin: 1%;
        font-size: 0.5em;
    }

    .widget {
        width: 250px;
        border: thin solid #ccc;
        min-height: 300px;
    }

    #tbl_incidence {
        width: 100%;
        text-align: center;
    }

    #tbl_incidence td {
        width: 50%;
        border-bottom: thin solid #ccc;
    }



    #div_stoplight {
        margin-top: 5%;
        margin-bottom: 5%;
        padding-top: 5%;
        width: 100%;
        height: 50px;
        text-align: center;
        vertical-align: middle;
        font-size: 2em;
        color: #ccc;
    }

    .stoplight_stop {
        background-color: darkred;
    }

    .stoplight_ok {
        background-color: darkgreen;
    }

    .value_stop {
        color: darkred;
    }

    .value_ok {
        color: darkgreen;
    }
</style>
