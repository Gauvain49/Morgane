<?php
namespace App\Services;

use Symfony\Component\HttpFoundation\Response;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use App\Repository\MgProductsNumericalRepository;

class DownloadFileService extends AbstractController
{

	private $response;
	private $productNumerical;

	public function __construct(MgProductsNumericalRepository $numerical)
	{
		$this->response = new Response;
		$this->productNumerical = $numerical;
	}

	/**
	 * Forcer le chargement d'un manuscrit
	 * @param string file est le nom du fichier
	 * @param int id est l'id du fichier s'il est présent en base de données
	 */
	public function download($file, $id = null)
	{
    	//Forcer le navigateur à télécharger les fichier
		$chemin = $this->getParameter('upload_directory_numericals') . '/manuscripts/' . $id . '/';
		$fichier = pathinfo($file);
		$chemin_complet = $chemin.$fichier['basename'];

		if(!is_file($chemin_complet) or !is_readable($chemin_complet)) {
			die("Fichier inexistant.");
		}
		$extensions = array("txt"=>"text/plain", "pdf"=>"application/pdf", "doc"=>"application/msword", "docx"=>"application/vnd.openxmlformats-officedocument.wordprocessingml.document", "rtf"=>"application/rtf", "odt"=>"application/vnd.oasis.opendocument.text");
		if(!isset($fichier['extension']) or !array_key_exists($fichier['extension'], $extensions)) {
			die("Type de fichier non autorisé.");
		}
		if(ini_get('zlib.output_compression')) {
			ini_set('zlib.output_compression','Off');
		}

		$this->response->setContent(file_get_contents($chemin_complet));
		$this->response->headers->set('Content-Type', 'application/force-download'); // modification du content-type pour forcer le téléchargement (sinon le navigateur internet essaie d'afficher le document)
		$this->response->headers->set('Content-disposition', 'filename='. $file);
         
   		return $this->response;
	}

	/**
	 * Forcer le chargement d'un fichier numérique
	 * @param int id est le nom du dossier contenant le fichier
	 * @param string file est le nom du fichier
	 */
	public function downloadNumerical($id, $file)
	{
    	//Forcer le navigateur à télécharger les fichier
    	$numerical = $this->productNumerical->find($id);
    	$field = hash_hmac('sha256', $id, 'XB240061119133vc79', false);
		$chemin = $this->getParameter('upload_directory_numericals') . '/' . $field . '/';
		$fichier = pathinfo($numerical->getFilename());
		//filename est le nom "classique" qu'on donne au fichier pour le téléchargement
		$filename = pathinfo($numerical->getUseFilename());
		$chemin_complet = $chemin.$fichier['basename'];

		if(!is_file($chemin_complet) or !is_readable($chemin_complet)) {
			die("Fichier inexistant.");
		}

		$extensions = array("txt"=>"text/plain", "pdf"=>"application/pdf", "doc"=>"application/msword", "docx"=>"application/vnd.openxmlformats-officedocument.wordprocessingml.document", "rtf"=>"application/rtf", "odt"=>"application/vnd.oasis.opendocument.text", "epub" => "application/epub+zip");
		if(!isset($filename['extension']) or !array_key_exists($filename['extension'], $extensions)) {
			die("Type de fichier non autorisé.");
		}
		if(ini_get('zlib.output_compression')) {
			ini_set('zlib.output_compression','Off');
		}

		$this->response->setContent(file_get_contents($chemin_complet));
		$this->response->headers->set('Content-Description', 'File Transfer');
		$this->response->headers->set('Content-Type', $extensions[$filename['extension']]); // modification du content-type pour forcer le téléchargement (sinon le navigateur internet essaie d'afficher le document)
		$this->response->headers->set('Content-Disposition', 'attachement; filename='. $file);
		$this->response->headers->set('Expires', '0');
		$this->response->headers->set('Cache-Control', 'must-revalidate');
		$this->response->headers->set('Pragma', 'no-cache');
		$this->response->headers->set('Content-Length', filesize($chemin_complet));

   		return $this->response;
	}
}