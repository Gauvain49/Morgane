<?php
namespace App\Services;

class ResizeImg
{
	public function resizeImg($img, $img_dest, $ext, $max, $square = false, $center = false) {
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
        /*if($this->type == 'product') {
            $src_img = imagecreatefromjpeg($img);
        } else {*/
            if ($ext == 'jpg' || $ext == 'jpeg') { 
                $src_img = imagecreatefromjpeg($img);
            } elseif ($ext == 'png') {
                $src_img = imagecreatefrompng($img); 
            } elseif ($ext == 'gif') {
                $src_img = imagecreatefromgif($img); 
            } elseif ($ext == 'bmp') {
                $src_img = imagecreatefromwbmp($img); 
            }  
        //}
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
        }/* else {
            imagecolortransparent($dst_img, imagecolorallocatealpha($dst_img, 255, 255, 255, 0));
        }*/
        if($center == true) {
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
        //Une photo prise en mode portrait avec certain smartphone est en faite définie en paysage et retournée artificiellement en portrait.
        //Lors du redimensionnement du fichier, c'est le mode originale paysage qui est conservé, si bien que la photo est retrouné à 90 degré.
        //la fonction suivante permet de vérifier le mode d'orientation de l'image et de la retourner si elle passe de portrait à paysage
        /*if($ext == 'jpg') {
            $exif = exif_read_data($img);//Vérifie l'orientation de l'image, surtout utile pour photos prise avec smartphone (voir imagerotate() plus bas);
        }
        if(isset($exif)) {
            if(!empty($exif['Orientation'])) {
                switch($exif['Orientation']) {
                    case 8:
                        $dst_img = imagerotate($dst_img,90,0);
                        break;
                    case 3:
                        $dst_img = imagerotate($dst_img,180,0);
                        break;
                    case 6:
                        $dst_img = imagerotate($dst_img,-90,0);
                        break;
                }
            }
        }*/
        /*if($this->type == 'product') {
            imagejpeg($dst_img,$img_dest);
        } else {*/
            if ($ext == 'jpg' || $ext == 'jpeg') { 
                imagejpeg($dst_img,$img_dest); 
            } elseif ($ext == 'png') { 
                imagepng($dst_img,$img_dest); 
            } elseif ($ext == 'gif') { 
                imagegif($dst_img,$img_dest); 
            } elseif($ext == 'bmp') {
                imagewbmp($dst_img, $img_dest);
            }
        //}
    }
}