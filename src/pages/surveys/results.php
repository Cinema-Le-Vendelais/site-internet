<?php

ini_set("display_errors", 1);

$url = $_SERVER['REQUEST_URI'];
$segments = explode('/', trim($url, '/'));
$surveyId = $segments[count($segments) - 2] ?? null;

$survey = $db->prepare("SELECT * FROM `surveys` WHERE `id` = ?");
$survey->execute(
    array(
        $surveyId
    )
);

if ($survey->rowCount() != 1) {
    echo "Sondage introuvable !";
    exit;
}


$survey = $survey->fetch();

$elements = json_decode($survey["data"], true)['pages'][0]['elements'];

if ($survey["privacy"] == "private") {
    echo "Sondage privé !";
    exit;
}

$exe = $db->prepare("SELECT data FROM `surveys-replies` WHERE `surveyId` = ?");
$exe->execute(array($surveyId));

$replies = $exe->fetchAll(PDO::FETCH_ASSOC);

$repliesList = [];
foreach ($replies as $reply) {

    $encryption_key = $_ENV["NEWSLETTER_KEY"];
    $cipher = $_ENV["NEWSLETTER_CIPHER"];

    $rawData = openssl_decrypt(json_encode($reply["data"], JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES), $cipher, $encryption_key, 0);

    $repliesList[] = json_decode($rawData, true);
}

function parseLinks($text) {
    // Regex pour détecter les liens URL
    $urlPattern = '/\b((https?:\/\/|www\.)[^\s<>]+(?:\.[^\s<>]+)*(\/[^\s<>]*)?)/i';

    // Remplacement par un lien cliquable
    $parsedText = preg_replace_callback($urlPattern, function ($matches) {
        $url = $matches[0];
        
        if (strpos($url, 'http') !== 0) {
            $url = 'http://' . $url;
        }

        return '<a href="' . htmlspecialchars($url) . '" download target="_blank">' . htmlspecialchars(basename($matches[0])) . '</a>';
    }, $text);

    return $parsedText;
}

echo "<div style='overflow-x: auto;'><table>";
echo "<thead><tr>";

// En-tête avec les noms des questions
foreach ($elements as $question) {
    echo "<th>" . htmlspecialchars(array_key_exists("title", $question) ? $question["title"] : $question["name"]) . "</th>";
}

echo "</tr></thead><tbody>";

foreach ($repliesList as $response) {
    echo "<tr>";

    foreach ($elements as $question) {
        echo "<td>";
        // Afficher la réponse si elle existe, sinon laisser la cellule vide

        $d = null;

        if (array_key_exists($question["name"], $response)) {
            switch($question["type"]){
                case "boolean":
                    if($response[$question["name"]])
                    {
                        $response[$question["name"]] = $question["labelTrue"];
                    }
                    else{
                        $response[$question["name"]] = $question["labelFalse"];
                    }
                    break;
                case "rating":
                    switch($question["rateType"]){
                        case "stars":
                            $response[$question["name"]] = str_repeat('⭐', intval($response[$question["name"]]));
                            break;
                        default:
                            break;
                    }
                    break;
                case "checkbox":
                    $v = $response[$question["name"]];
                    $response[$question["name"]] = array();
                    foreach($v as $choice)
                    {
                        array_push($response[$question["name"]], array_column($question["choices"], 'text', 'value')[$choice] ?? "");
                    }
                    $response[$question["name"]] = join(", ", $response[$question["name"]]);
                    break;

                case "dropdown":
                    if($response[$question["name"]] == "other")
                    {
                        $response[$question["name"]] = "Autre : ".$response[$question["name"]."-Comment"];
                    }
                    else{
                        $response[$question["name"]] = array_column($question["choices"], 'text', 'value')[$response[$question["name"]]] ?? "";
                    }
                    
                    break;

                case "file":
                    $response[$question["name"]] = join(", ", array_map(function($a){return $a["content"];}, $response[$question["name"]]));

                default:
                    break;
            }

            $d = htmlspecialchars($response[$question["name"]]);
            $d = parseLinks($d);
        }

        

        
        echo isset($response[$question["name"]]) ? $d : "";
        echo "</td>";
    }

    echo "</tr>";
}

echo "</tbody></table></div>";
?>



<div id="surveyVizPanel"></div>

<style>
    table {
  border-collapse: collapse;
  margin: 25px 0;
  font-size: 0.9em;
  min-width: 400px;
  border-radius: 5px 5px 0 0;
  overflow: hidden;
  box-shadow: 0 0 20px rgba(0, 0, 0, 0.15);
}

table thead tr {
  background-color: #009879;
  color: #ffffff;
  text-align: left;
  font-weight: bold;
}

table th,
table td {
  padding: 12px 15px;
}

table tbody tr {
  border-bottom: 1px solid #dddddd;
}

table tbody tr:nth-of-type(even) {
  background-color: #f3f3f3;
}

table tbody tr:last-of-type {
  border-bottom: 2px solid #009879;
}

table tbody tr.active-row {
  font-weight: bold;
  color: #009879;
}

</style>


<script>
    document.addEventListener("DOMContentLoaded", function() {
        postApi("surveys/<?= $surveyId ?>", "v2").then(res => {
            if (res.status == "OK") {
                let surveyData = res.data.data;

                const vizPanelOptions = {
                    allowHideQuestions: false
                }


                const survey = new Survey.Model(surveyData);

                const vizPanel = new SurveyAnalytics.VisualizationPanel(
                    survey.getAllQuestions(),
                    JSON.parse(`<?= json_encode($repliesList, JSON_UNESCAPED_SLASHES | JSON_UNESCAPED_UNICODE) ?>`),
                    vizPanelOptions
                );

                vizPanel.locale = "fr";

                vizPanel.render(document.getElementById("surveyVizPanel"));

                document.querySelector(".sa-visualizer__toolbar.sa-panel__header").style.display = "none";
            }
        })

    });
</script>