<?php
namespace App\Services;

use App\Services\ResizeImg;

class qqFileUploader {
    protected $assignedName;
    protected $filename_secure;
    protected $allowedExtensions = array();
    protected $sizeLimit = 10485760;
    protected $file;
    protected $type;
    protected $id_product;
    protected $resize;
    //public $products_images_manage;
    public $product_manage;

    function __construct(?string $assignedName, array $allowedExtensions = array(), $sizeLimit = 10485760, $filename_secure = false)
    {
        $allowedExtensions = array_map("strtolower", $allowedExtensions);
            
        $this->allowedExtensions = $allowedExtensions;        
        $this->sizeLimit = $sizeLimit;
        $this->assignedName = $assignedName;
        $this->filename_secure = $filename_secure;
        $this->resize = new ResizeImg();
        
        $this->checkServerSettings();       

        if (isset($_GET['qqfile'])) {
            $this->file = new qqUploadedFileXhr();
        } elseif (isset($_FILES['qqfile'])) {
            $this->file = new qqUploadedFileForm();
        } else {
            $this->file = false; 
        }
        if(isset($_GET['type']) && isset($_GET['id_product'])) {
            if($_GET['type'] == 'product') {
                $this->type = 'product';
                $this->id_product = $_GET['id_product'];
            } elseif($_GET['type'] == 'numeric') {
                $this->type = 'numeric';
                $this->id_product = $_GET['id_product'];
            }
        } else {
            $this->type = 'media';
        }
    }

    public function setType($type) {
        $this->type = $type;
    }

    public function type() {
        return $this->type;
    }

    public function setAssignedName($assignedName) {
        $this->assignedName = $assignedName;
    }

    public function getAssignedName() {
        return $this->assignedName;
    }
    
    public function getName() {
        if ($this->assignedName) {
            return $this->getAssignedName();
        } else {
            if ($this->file) {
                $pathinfo = pathinfo($this->file->getName());
                return $filename = $pathinfo['filename'];
                //return $this->file->getName();
            }
        }
    }
    
    private function checkServerSettings(){        
        $postSize = $this->toBytes(ini_get('post_max_size'));
        $uploadSize = $this->toBytes(ini_get('upload_max_filesize'));        
        
        if ($postSize < $this->sizeLimit || $uploadSize < $this->sizeLimit){
            $size = max(1, $this->sizeLimit / 1024 / 1024) . 'M';             
            die("{'error':'increase post_max_size and upload_max_filesize to $size'}");    
        }        
    }
    
    private function toBytes($str){
        $val = trim($str);
        $last = strtolower($str[strlen($str)-1]);
        $val = intval($val);
        switch($last) {
            case 'g': $val *= 1024;
            case 'm': $val *= 1024;
            case 'k': $val *= 1024;        
        }
        return $val;
    }
    
    /**
     * Returns array('success' => true, 'newFilename' => 'myDoc123.doc') or array('error' => 'error message')
     */
    function handleUpload($uploadDirectory, $replaceOldFile = FALSE){
        if (!is_writable($uploadDirectory)){
            return array('error' => "Erreur du serveur. Le répertoire de téléchargement n'est pas accessible en écriture.");
        }
        
        if (!$this->file){
            return array('error' => 'Aucun fichier n\'a été téléchargé.');
        }
        
        $size = $this->file->getSize();
        
        if ($size == 0) {
            return array('error' => 'Le fichier est vide.');
        }
        
        if ($size > $this->sizeLimit) {
            return array('error' => 'Le fichier est trop lourd.');
        }
        
        $pathinfo = pathinfo($this->file->getName());
        $filename = $pathinfo['filename'];
        //$filename = md5(uniqid());
        $ext = @$pathinfo['extension'];     // hide notices if extension is empty

        if($this->allowedExtensions && !in_array(strtolower($ext), $this->allowedExtensions)){
            $these = implode(', ', $this->allowedExtensions);
            return array('error' => 'Le fichier a une extension non valide, il doit être de type '. $these . '.');
        }
        
        if(!$replaceOldFile){
            /// don't overwrite previous files that were uploaded
            while (file_exists($uploadDirectory . $filename . '.' . $ext)) {
                $filename.= rand(10, 99);
            }
        }
 
        if ($this->file->save($uploadDirectory . $this->getName() . '.' . $ext)){
            //$finfo = finfo_open(FILEINFO_MIME_TYPE);
            $mime = mime_content_type($uploadDirectory . $this->getName() . '.' . $ext);
            
            if($this->type == 'numeric') {
                rename($uploadDirectory . $this->getName() . '.' . $ext, $uploadDirectory . $this->filename_secure);
            }

            //On créée une copie des fichiers sous différentes tailles uniquement si ce ne sont pas des numérics
            if($this->type == 'product') {
                $ext = strtolower($ext);
                $dimension = getimagesize($uploadDirectory . $this->getName() . '.' . $ext);
                // Si les dimensions ne dépassent la valeur 150px, l'image est trop petite pour la duplication dans les autres tailles.
                // On crée donc un carré à partir de sa taille original
                if ($dimension[0] <= 150) {
                    if ($this->type == 'product') {
                        $this->resize->resizeImg( $uploadDirectory . $this->getName() . '.' . $ext, $uploadDirectory . $this->getName() . 'square.' . $ext, $ext, $dimension[0], true);
                    }
                }
                if($dimension[0] > 150) {
                    if($this->type == 'product') {
                        $this->resize->resizeImg( $uploadDirectory . $this->getName() . '.' . $ext, $uploadDirectory . $this->getName() . '_small.' . $ext, $ext, 150, false);
                        //On duplique également l'image dans un carré
                        $this->resize->resizeImg( $uploadDirectory . $this->getName() . '.' . $ext, $uploadDirectory . $this->getName() . '_smallsquare.' . $ext, $ext, 150, true);
                    }
                }
                if($dimension[0] > 300) {
                    if($this->type == 'product') {
                        $this->resize->resizeImg( $uploadDirectory . $this->getName() . '.' . $ext, $uploadDirectory . $this->getName() . '_medium.' . $ext, $ext, 300, false);
                        $this->resize->resizeImg( $uploadDirectory . $this->getName() . '.' . $ext, $uploadDirectory . $this->getName() . '_mediumsquare.' . $ext, $ext, 300, true);
                    }
                }
                if($dimension[0] > 850) {
                    if($this->type == 'product') {
                        $this->resize->resizeImg( $uploadDirectory . $this->getName() . '.' . $ext, $uploadDirectory . $this->getName() . '_large.' . $ext, $ext, 850, false);
                        $this->resize->resizeImg( $uploadDirectory . $this->getName() . '.' . $ext, $uploadDirectory . $this->getName() . '_largesquare.' . $ext, $ext, 850, true);
                    }
                }
            }
            return array('success' => true, 'newFilename' => $this->getName() . '.' . $ext);
        } else {
            return array('error' => 'Could not save uploaded file.' .
                'The upload was cancelled, or server error encountered');
        }
        
    }    
}
