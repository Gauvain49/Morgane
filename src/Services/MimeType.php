<?php
namespace App\Services;

class MimeType
{
    protected $mimesTypeImg = [];
    protected $mimesType = [];

    public function __construct()
    {
        $this->mimesTypeImg = array(
                                'image/gif' => '.gif',
                                'image/pjpeg' => '.jpg',
                                'image/jpeg' => '.jpg',
                                'image/JPG' => '.jpg',
                                'image/X-PNG' => '.png',
                                'image/PNG' => '.png',
                                'image/png' => '.png',
                                'image/x-png' => '.png');
        $this->mimesType = $this->mimesTypeImg;
    }

    public function getExtension($mimeType)
    {
        if (!array_key_exists($mimeType, $this->mimesType)) {
            throw new \InvalidArgumentException(
                sprintf('Ce type_mime est inconnu : %s.', $mimeType)
            );
        }
        return $this->mimesType[$mimeType];
    }

    public function getExtensionResized($mimeType)
    {
        if (!array_key_exists($mimeType, $this->mimesTypeImg)) {
            throw new \InvalidArgumentException(
                sprintf('Ce type_mime ne peut être redimensionné : %s.', $mimeType)
            );
        } else {
            return true;
        }
    }

}