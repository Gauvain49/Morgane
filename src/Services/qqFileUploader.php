<?php
namespace App\Services;

use App\Services\ResizeImg;

class qqFileUploader {
    protected $filename;
    protected $filename_secure;
    protected $allowedExtensions = array();
    protected $sizeLimit = 10485760;
    protected $file;
    protected $type;
    protected $id_product;
    protected $resize;
    //public $products_images_manage;
    public $product_manage;

    function __construct(string $filename, array $allowedExtensions = array(), $sizeLimit = 10485760, $filename_secure = false)
    {
        $allowedExtensions = array_map("strtolower", $allowedExtensions);
            
        $this->allowedExtensions = $allowedExtensions;        
        $this->sizeLimit = $sizeLimit;
        $this->filename = $filename;
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
        }

        //$this->products_images_manage = new ProductsImagesManage($this->q);
    }

    public function setType($type) {
        $this->type = $type;
    }

    public function type() {
        return $this->type;
    }

    public function setFilename($filename) {
        $this->filename = $filename;
    }

    public function filename() {
        return $this->filename;
    }
    
    public function getName(){
        if ($this->file)
            return $this->file->getName();
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

    /*public function resizeImg($img, $img_dest, $ext, $max, $square = false) {
        $form_errors = array();
        $size = getimagesize($img);
        
        $src_w = $size[0];
        $src_h = $size[1];
        if ($max > 0) {
            if ($src_w > $src_h ) { 
                if ($src_w > $max){
                    $width = $max;
                } else {
                    $width = $src_w;
                }
                $height = ceil(($src_h / $src_w) * $width); 
            } else if ($src_h > $src_w) {
                if ($src_h > $max){
                    $height = $max;
                } else {
                    $height = $src_h;
                }
                $width = ceil(($src_w / $src_h) * $height); 
            } else if ($src_h == $src_w) {
                $height = $max;
                $width = $max;
            }
        } else if ($max == 0) {
            $width = $src_w;
            $height = $src_h;
        } 

        if ($ext == 'jpg') { 
            $src_img = imagecreatefromjpeg($img);
        } elseif ($ext == 'png') {
            $src_img = imagecreatefrompng($img); 
        } elseif ($ext == 'gif') {
            $src_img = imagecreatefromgif($img); 
        } elseif ($ext == 'bmp') {
            $src_img = imagecreatefromwbmp($img); 
        }  
        if($square == true) {//Si square est true, on créé l'image dans un carré 
            $dst_img = imagecreatetruecolor($max,$max);
            $white = imagecolorallocate($dst_img, 255, 255, 255);
            imagefill($dst_img, 0, 0, $white);
        } else {
            $dst_img = imagecreatetruecolor($width,$height);
        }
        
        if($ext == "gif" || $ext == "png") {
            imagecolortransparent($dst_img, imagecolorallocatealpha($dst_img, 255, 255, 255, 127));
            imagealphablending($dst_img, false);
            imagesavealpha($dst_img, true);
        }
        if($this->type == 'product') {
            //($width - $src_w) / 2
            if($square == true) {
                $dst_x = ($max - $width) / 2;//Coordonnées x du point de destination
                $dst_y = ($max - $height) / 2;//Coordonnées y du point de destination
                $src_x = 0;//Coordonnées x du point source
                $src_y = 0;//Coordonnées y du point source
            } else {
                $dst_x = 0;//Coordonnées x du point de destination
                $dst_y = 0;//Coordonnées y du point de destination
                $src_x = 0;//Coordonnées x du point source
                $src_y = 0;//Coordonnées y du point source
            }
        } else {
            $dst_x = 0;//Coordonnées x du point de destination
            $dst_y = 0;//Coordonnées y du point de destination
            $src_x = 0;//Coordonnées x du point source
            $src_y = 0;//Coordonnées y du point source
        }
        imagecopyresampled($dst_img,$src_img,$dst_x,$dst_y,$src_x,$src_y,$width,$height,$src_w,$src_h);

        if ($ext == 'jpg') { 
            imagejpeg($dst_img,$img_dest); 
        } elseif ($ext == 'png') { 
            imagepng($dst_img,$img_dest); 
        } elseif ($ext == 'gif') { 
            imagegif($dst_img,$img_dest); 
        } elseif($ext == 'bmp') {
            imagewbmp($dst_img, $img_dest);
        }
    }*/
    
    /**
     * Returns array('success' => true, 'newFilename' => 'myDoc123.doc') or array('error' => 'error message')
     */
    function handleUpload($uploadDirectory, $replaceOldFile = FALSE){
        if (!is_writable($uploadDirectory)){
            return array('error' => "Server error. Upload directory isn't writable.");
        }
        
        if (!$this->file){
            return array('error' => 'No files were uploaded.');
        }
        
        $size = $this->file->getSize();
        
        if ($size == 0) {
            return array('error' => 'File is empty');
        }
        
        if ($size > $this->sizeLimit) {
            return array('error' => 'File is too large');
        }
        
        $pathinfo = pathinfo($this->file->getName());
        //$filename = $pathinfo['filename'];
        $filename_origine = $pathinfo['filename'];
        //$filename = \Core\No_special_characters::no_special_character_utf8($filename);
        //$filename = md5(uniqid());
        $ext = @$pathinfo['extension'];     // hide notices if extension is empty

        if($this->allowedExtensions && !in_array(strtolower($ext), $this->allowedExtensions)){
            $these = implode(', ', $this->allowedExtensions);
            return array('error' => 'File has an invalid extension, it should be one of '. $these . '.');
        }
        
        if(!$replaceOldFile){
            /// don't overwrite previous files that were uploaded
            while (file_exists($uploadDirectory . $this->filename() . '.' . $ext)) {
                $this->setFilename($this->filename.= rand(10, 99));
            }
        }
        //attention : si l'image est pour illustrer un produit, le nom de l'image prend celui de l'ID du produit, {!et converti en .jpg}
        /*if($this->type == 'product') {
            $id_image = 1;
            if($id_image != false) {
                $id_image++;
            } else {
                $id_image = 1;
            }
            
            $filename = $id_image;
        }*/
 
        if ($this->file->save($uploadDirectory . $this->filename() . '.' . $ext)){
            //$finfo = finfo_open(FILEINFO_MIME_TYPE);
            $mime = mime_content_type($uploadDirectory . $this->filename() . '.' . $ext);
            if($ext == 'jpg' || $ext == 'jpeg' || $ext == 'gif' || $ext == 'png') {
                $dimensions = getimagesize($uploadDirectory . $this->filename() . '.' . $ext);
                $w_h = $dimensions[0] . 'x' . $dimensions[1];
            } else {
                $w_h = null;
            }
            
            if($this->type == 'numeric') {
                //$filename_secure = sha1($this->id_product.$this->filename() . '.' . $ext);
                //$prices = $this->product_manage->get($this->id_product);
                //$add_numeric = $this->q->insert("INSERT INTO mg_products_numerical (product_id, filename, use_filename, numerical_selling_price, numerical_selling_price_all_taxes, numerical_taxe_id, date_add) VALUES (?, ?, ?, ?, ?, ?, NOW())", array($this->id_product, $filename_secure, $filename . '.' . $ext, $prices->selling_price(), $prices->     selling_price_all_taxes(), $prices->taxe_id()));
                //if($add_numeric['nb'] == 1) {
                    rename($uploadDirectory . $this->filename() . '.' . $ext, $uploadDirectory . $this->filename_secure);
                //}
            }
            //On créée une copie des fichiers sous différentes tailles uniquement si ce ne sont pas des numérics
            if($this->type == 'product') {
                $ext = strtolower($ext);
                if($ext == 'jpg' || $ext == 'jpeg' || $ext == 'gif' || $ext == 'png') {
                    $dimension = getimagesize($uploadDirectory . $this->filename() . '.' . $ext);
                    // Si les dimensions ne dépassent la valeur 150px, l'image est trop petite pour la duplication dans les autres tailles.
                    // On crée donc un carré à partir de sa taille original
                    if ($dimension[0] <= 150) {
                        if ($this->type == 'product') {
                            $this->resize->resizeImg( $uploadDirectory . $this->filename() . '.' . $ext, $uploadDirectory . $this->filename() . 'square.' . $ext, $ext, $dimension[0], true);
                        }
                    }
                    if($dimension[0] > 150) {
                        if($this->type == 'product') {
                            $this->resize->resizeImg( $uploadDirectory . $this->filename() . '.' . $ext, $uploadDirectory . $this->filename() . '_small.' . $ext, $ext, 150, false);
                            //On duplique également l'image dans un carré
                            $this->resize->resizeImg( $uploadDirectory . $this->filename() . '.' . $ext, $uploadDirectory . $this->filename() . '_smallsquare.' . $ext, $ext, 150, true);
                        }
                    }
                    if($dimension[0] > 300) {
                        if($this->type == 'product') {
                            $this->resize->resizeImg( $uploadDirectory . $this->filename() . '.' . $ext, $uploadDirectory . $this->filename() . '_medium.' . $ext, $ext, 300, false);
                            $this->resize->resizeImg( $uploadDirectory . $this->filename() . '.' . $ext, $uploadDirectory . $this->filename() . '_mediumsquare.' . $ext, $ext, 300, true);
                        }
                    }
                    if($dimension[0] > 850) {
                        if($this->type == 'product') {
                            $this->resize->resizeImg( $uploadDirectory . $this->filename() . '.' . $ext, $uploadDirectory . $this->filename() . '_large.' . $ext, $ext, 850, false);
                            $this->resize->resizeImg( $uploadDirectory . $this->filename() . '.' . $ext, $uploadDirectory . $this->filename() . '_largesquare.' . $ext, $ext, 850, true);
                        }
                    }
                }
            }
            return array('success' => true, 'newFilename' => $this->filename() . '.' . $ext);
        } else {
            return array('error' => 'Could not save uploaded file.' .
                'The upload was cancelled, or server error encountered');
        }
        
    }    
}
