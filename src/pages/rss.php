<?php

ini_set("display_errors", 0);

require_once(__DIR__."/../../../vendor/autoload.php");


header("Content-type: text/xml");

use Lukaswhite\FeedWriter\RSS2;
use Lukaswhite\FeedWriter\Media\MediaContent;

$feed = new RSS2( );

/* Récupérer les films depuis l'api */

$ch = curl_init();
curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
curl_setopt($ch, CURLOPT_URL, "https://api.levendelaiscinema.fr/movies");
$result = json_decode(curl_exec($ch), true);
curl_close($ch);

if($result["status"] != "OK"){
    exit;
}

$movies = $result["data"];

$moviesChannel = $feed->addChannel( );

$moviesChannel
	->title( 'Programme' )
	->description( 'Programme de films mensuels' )
	->link( 'https://levendelaiscinema.fr/programme' )
	->lastBuildDate( new \DateTime( ) )
	->pubDate( new \DateTime( ) )
	->language( 'fr-FR' );


foreach( $movies as $film ) {
	$moviesChannel->addItem( )
		->title( $film["title"] )
		->description( array_key_exists("synopsis", $film) ? $film["synopsis"] : "Aucun synopsis." )
		->link( "https://levendelaiscinema.fr/film/".$film["id"] )
		//->pubDate( $film->publishedAt )
		->guid( $film["id"], true );
}

echo $feed;
