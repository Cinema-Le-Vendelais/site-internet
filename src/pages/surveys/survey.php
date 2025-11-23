<?php

ini_set("display_errors", 1);

$url = $_SERVER['REQUEST_URI'];
$segments = explode('/', trim($url, '/'));
$surveyId = end($segments);


$survey = $db->prepare("SELECT * FROM `surveys` WHERE `id` = ?");
$survey->execute(
    array(
        $surveyId
    )
);

$survey = $survey->fetch();


$replies = $db->prepare("SELECT * FROM `surveys-replies` WHERE `surveyId` = ?");
$replies->execute(
    array(
        $surveyId
    )
);

$now = new DateTime();
$now->setTime(0, 0);


if(new DateTime($survey["start_date"]) > $now)
{
    echo "<h2 style='background: red;border-radius:8px;padding:8px;color:white;text-align:center'>Ce sondage n'a pas encore commencé !</h2>";
    die();
    exit;
}

if(new DateTime($survey["end_date"]) < $now)
{
    echo "<h2 style='background: red;border-radius:8px;padding:8px;color:white;text-align:center'>Ce sondage est terminé !</h2>";
    die();
    exit;
}


$rp = $replies->fetchAll();

$nbCount = 0;

foreach($rp as $resp){
    $nbCount += $resp["nbCount"];
}

if ($nbCount >= $survey["max_replies"])
{
    echo "<h2 style='background: red;border-radius:8px;padding:8px;color:white;text-align:center'>Nombre maximum de réponses atteint pour ce sondage</h2>";
}
else{
?>

<div id="surveyContainer"></div>


<script>

function detectIEOrEdge() {
    var ua = window.navigator.userAgent;
    var msie = ua.indexOf("MSIE ");
    var trident = ua.indexOf("Trident/");
    var edge = ua.indexOf("Edge/");
    return edge > 0 || trident > 0 || msie > 0;
}


    function getLastPathSegment(url) {
        const pathname = new URL(url).pathname;
        const segments = pathname.split('/');
        return segments.pop() || segments.pop();
    }

    const surveyId = getLastPathSegment(window.location.href);

    document.addEventListener("DOMContentLoaded", function() {
        postApi("surveys/" + surveyId, "v2").then(res => {
            if (res.status == "OK") {

                let survey = res.data.data;

                if (res.data.type == "simple") return;

                function addCaptchaToSurvey(json) {
                    const hCaptchaElement = {
                        type: "html",
                        name: "captcha",
                        html: `<div class="cf-turnstile" data-theme="light" data-expired-callback="turnstile.reset(this)" data-sitekey="<?= $_ENV["TURNSTILE_SITE_KEY"] ?>"></div>`
                    };

                    json.pages[0].elements.push(hCaptchaElement);

                    return json;
                }

                Survey.ComponentCollection.Instance.add({
                    name: "numbercount",
                    title: "Nombre (remplace nombre de réponses)",
                    defaultQuestionTitle: "Nombre (remplace nombre de réponses)",
                    questionJSON: {
                        type: "text",
                        min: 1,
                        inputType: "number",
                        defaultValue: 1
                    },
                    inheritBaseProps: true
                });

                survey.locale = "fr";

                survey.pages.forEach(page => {
                    page.elements.forEach(element => {
                        if(element.type == "file") element.storeDataAsText = false;
                    })
                })

                survey = addCaptchaToSurvey(survey);

                const surveyModel = new Survey.Model(survey);
                window.survey = surveyModel

                surveyModel.onCompleting.add(function(result, options) {
                    options.allowComplete = false;
                    options.allow = false;

                    

                    fetch('/survey/'+surveyId+ "/post", {
                        method: 'POST',
                        headers: {
                            'Content-Type': 'application/json'
                        },
                        body: JSON.stringify({
                            "data": result.data,
                            "captcha-response": turnstile.getResponse()
                        })
                    }).then(response => response.json()).then(data => {

                        const captchaElement = document.querySelector(".cf-turnstile");
                            if (captchaElement) {
                                // Réinitialise Turnstile
                                turnstile.reset(captchaElement);
                                console.log("CAPTCHA réinitialisé.");
                            }


                        if (data.success) {
                            Swal.fire({
                                icon: "success",
                                title: "C'est ok !",
                                text: data.message,
                            });

                            result.doComplete()
  
                            document.querySelector("main").innerHTML = data.message;
                        } else {
                            Swal.fire({
                                icon: "error",
                                title: "Oops...",
                                text: data.message,
                            })
                            
                        }
                    }).catch(error => console.error("Erreur:", error));
                });

                /*surveyModel
                .onClearFiles
                .add(function (survey, options) {
                    // Code to remove files stored on your service
                    // SurveyJS Service doesn't allow to remove files
                    options.callback("success");
                });*/

                surveyModel.onDownloadFile.add((survey, options) => {
                    var xhr = new XMLHttpRequest();
                    xhr.open("GET", options.content);
                    xhr.onloadstart = function (ev) {
                        xhr.responseType = "blob";
                    }
                    xhr.onload = () => {
                        var file;
                        if (detectIEOrEdge()) {
                            file = new Blob([xhr.response], options.fileValue.name, { type: options.fileValue.type });
                        }
                        else {
                            file = new File([xhr.response], options.fileValue.name, { type: options.fileValue.type });
                        }
                        var reader = new FileReader();
                        reader.onload = function (e) {
                            options.callback("success", e.target.result);
                        };
                        reader.readAsDataURL(file);
                    };
                    xhr.send();
                });

                surveyModel.onUploadFiles
                    .add((survey, options) => {
                        var formData = new FormData();
                        options
                            .files
                            .forEach(function(file) {
                                formData.append(file.name, file);
                            });
                        formData.append("surveyId", surveyId)
                        var xhr = new XMLHttpRequest();
                        xhr.responseType = "json";
                        xhr.open("POST", "/survey/"+surveyId+"/post")
                        xhr.onload = function() {
                            var data = xhr.response;
                            if(data.success){
                                options.callback("success", options.files.map(file => {
                                    return {
                                        file: file,
                                        content: data[file.name]
                                    };
                                }));
                            }
                            else{
                                console.log(data.message)
                                notyf.error(data.message)
                                options.callback("error");
                            }
                            
                        };
                        xhr.send(formData);
                    });


                surveyModel.render(document.getElementById("surveyContainer"));

                turnstile.render(document.querySelector(".cf-turnstile"))
            }

            if (res.status == "SURVEY_NOT_FOUND") {
                document.querySelector("#surveyContainer").innerHTML = "<h1>Sondage introuvable !</h1>"
            }
        })
    });
</script>

<?php
}