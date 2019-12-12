<?php
namespace App\Services;

class MimeType
{
    protected $mimesType = [];

    public function __construct()
    {
        $this->mimesType = array('image/gif' => '.gif',
                                'image/pjpeg' => '.jpg',
                                'image/jpeg' => '.jpg',
                                'image/JPG' => '.jpg',
                                'image/X-PNG' => '.png',
                                'image/PNG' => '.png',
                                'image/png' => '.png',
                                'image/x-png' => '.png');
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

}