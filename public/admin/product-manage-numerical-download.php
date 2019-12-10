<?php
//require '../vendor/autoload.php';
//include '../config/config.php';
//include('../' . MYSQL);
//
//use Morgane\DB\Database;
//$db = new Database;
if(isset($_GET['usefilename'])) {
	$usefilename = $_GET['usefilename'];
} else {
	die("Veuillez spécifier le fichier à télécharger.");
}
if(isset($_GET['filename'])) {
	$filename = $_GET['filename'];
} else {
	die("Veuillez spécifier le produit associé au fichier à télécharger.");
}
//Forcer le navigateur à télécharger les fichier
$chemin = realpath("../assets/download")."/";
$fichier = pathinfo($filename);
$chemin_complet = $chemin.$fichier['basename'];
if(!is_file($chemin_complet) or !is_readable($chemin_complet)) {
	die("Fichier inexistant.$chemin_complet");
}
/*$extensions = array("txt"=>"text/plain", "jpg"=>"image/jpeg");
if(!isset($fichier['extension']) or !array_key_exists($fichier['extension'], $extensions)) {
	die("Type de fichier non autorisé.");
}*/
if(ini_get('zlib.output_compression')) {
	ini_set('zlib.output_compression','Off');
}
header("Pragma: no-cache");
header("Expire: 0");
header("Cache-Control: must-revalidate, post-check=0, pre-check=0");
header("Cache-Control: private",false);
header("Content-Disposition: attachment; filename=".$usefilename);
header("Content-type: application/octet-stream");
//header("Content-Transfert-Encoding: ".$extensions[$fichier['extentions']]);
header("Content-Transfert-Encoding: text/plain");
header("Content-Length: ".filesize($chemin_complet));
readfile($chemin_complet);
?>