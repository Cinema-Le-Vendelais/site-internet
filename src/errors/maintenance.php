<!-- PAGE A REFAIRE -->

<title>Le Vendelais - Maintenance</title>

<link rel="shortcut icon" href="https://levendelaiscinema.fr/cdn/icon.png" type="image/x-icon">



<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css">



<div class="maintenance">

  <div class="maintenance_contain">

    <img src="https://demo.wpbeaveraddons.com/wp-content/uploads/2018/02/main-vector.png" alt="maintenance">

    <span class="pp-infobox-title-prefix"></span>

    <div class="pp-infobox-title-wrapper">

		  <h3 class="pp-infobox-title">Nous effectuons actuellement une maintenance planifiée !</h3>

	  </div> 

  <div class="pp-infobox-description">

		<p>

        Nous espérons être de retour en ligne très prochainement. Merci de votre patience et nous nous excusons pour tout inconvénient causé.

        </p><code>- L'équipe du Vendelais</code>		</div>  

<div></div>

<br>

<?php if(!isset($_GET["hide_login_button"]))
{
    ?>
        <button class="Btn"></button>

        <?php 
}
    ?>

</div>

  </div>

</div>





<style>



body {

    margin: 0;

    padding: 0;

    width: 100%;

   background:#000;

}

* {

    box-sizing: border-box;

}

.maintenance {

    background-image: url(https://demo.wpbeaveraddons.com/wp-content/uploads/2018/02/main-1.jpg);

    background-repeat: no-repeat;

    background-position: center center;

    background-attachment: scroll;

    background-size: cover;

}



.maintenance {

    width: 100%;

    height: 100%;

    min-height: 100vh;

}



.maintenance {

    display: flex;

    flex-flow: column nowrap;

    justify-content: center;

    align-items: center;

}



.maintenance_contain {

    display: flex;

    flex-direction: column;

    flex-wrap: nowrap;

    align-items: center;

    justify-content: center;

    width: 100%;  

    padding: 15px;  

}

.maintenance_contain img {

    width: auto;

    max-width: 100%;

}

.pp-infobox-title-prefix {

    font-weight: 500;

    font-size: 20px;

    color: #000000;

    margin-top: 30px;

    text-align: center;

}



.pp-infobox-title-prefix {

    font-family: sans-serif;

}



.pp-infobox-title {

    color: #000000;

    font-family: sans-serif;

    font-weight: 700;

    font-size: 40px;

    margin-top: 10px;

    margin-bottom: 10px;

    text-align: center;

    display: block;

    word-break: break-word;  

}



.pp-infobox-description {

    color: #000000;

    font-family: "Poppins", sans-serif;

    font-weight: 400;

    font-size: 18px;

    margin-top: 0px;

    margin-bottom: 0px;

    text-align: center;

}



.pp-infobox-description p {

    margin: 0;

}



.title-text.pp-primary-title {

    color: #000000;

    padding-top: 0px;

    padding-bottom: 0px;

    padding-left: 0px;

    padding-right: 0px;

    font-family: sans-serif;

    font-weight: 500;

    font-size: 18px;

    line-height: 1.4;

    margin-top: 50px;

    margin-bottom: 0px;

}



.pp-social-icon {

    margin-left: 10px;

    margin-right: 10px;

    display: inline-block;

    line-height: 0;

    margin-bottom: 10px;

    margin-top: 10px;

    text-align: center;

}



.pp-social-icon a {

    display: inline-block;

    height: 40px;

    width: 40px;

}



.pp-social-icon a i {

    border-radius: 100px;

    font-size: 20px;

    height: 40px;

    width: 40px;

    line-height: 40px;

    text-align: center;

}



.pp-social-icon:nth-child(1) a i {

    color: #4b76bd;

}

.pp-social-icon:nth-child(1) a i {

    border: 2px solid #4b76bd;

}

.pp-social-icon:nth-child(2) a i {

    color: #00c6ff;

}

.pp-social-icon:nth-child(2) a i {

    border: 2px solid #00c6ff;

}

.pp-social-icon:nth-child(3) a i {

    color: #fb5245;

}

.pp-social-icon:nth-child(3) a i {

    border: 2px solid #fb5245;

}

.pp-social-icon:nth-child(4) a i {

    color: #158acb;

}

.pp-social-icon:nth-child(4) a i {

    border: 2px solid #158acb;

}



.pp-social-icons {

    display: flex;

    flex-flow: row wrap;

    align-items: center;

    justify-content: center;

}



.Btn {

  width: 140px;

  height: 40px;

  border: none;

  border-radius: 10px;

  background: linear-gradient(to right,#77530a,#ffd277,#77530a,#77530a,#ffd277,#77530a);

  background-size: 250%;

  background-position: left;

  color: #ffd277;

  position: relative;

  display: flex;

  align-items: center;

  justify-content: center;

  cursor: pointer;

  transition-duration: 1s;

  overflow: hidden;

}



.Btn::before {

  position: absolute;

  content: "Je suis bénévole";

  color: #ffd277;

  display: flex;

  align-items: center;

  justify-content: center;

  width: 97%;

  height: 90%;

  border-radius: 8px;

  transition-duration: 1s;

  background-color: rgba(0, 0, 0, 0.842);

  background-size: 200%;

}



.Btn:hover {

  background-position: right;

  transition-duration: 1s;

}



.Btn:hover::before {

  background-position: right;

  transition-duration: 1s;

}



.Btn:active {

  transform: scale(0.95);

}

</style>