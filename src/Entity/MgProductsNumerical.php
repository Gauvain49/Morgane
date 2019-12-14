<?php

namespace App\Entity;

use Cocur\Slugify\Slugify;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass="App\Repository\MgProductsNumericalRepository")
 */
class MgProductsNumerical
{
    /**
     * @ORM\Id()
     * @ORM\GeneratedValue()
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\ManyToOne(targetEntity="App\Entity\MgProducts", inversedBy="numericals")
     * @ORM\JoinColumn(nullable=false)
     */
    private $product;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $filename;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $use_filename;

    /**
     * @ORM\Column(type="boolean")
     */
    private $exclusive;

    /**
     * @ORM\Column(type="datetime", nullable=true)
     */
    private $date_expire;

    /**
     * @ORM\Column(type="integer", nullable=true)
     */
    private $nb_days_accessibles;

    /**
     * @ORM\Column(type="integer", nullable=true)
     */
    private $nb_downloadable;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getProduct(): ?MgProducts
    {
        return $this->product;
    }

    public function setProduct(?MgProducts $product): self
    {
        $this->product = $product;

        return $this;
    }

    public function getFilename(): ?string
    {
        return $this->filename;
    }

    public function setFilename(string $filename): self
    {
        $this->filename = $filename;

        return $this;
    }

    public function getUseFilename(): ?string
    {
        return $this->use_filename;
    }

    public function setUseFilename(string $use_filename): self
    {
        $file = explode('.', $use_filename);
        $extension = end($file);
        $filename = str_replace($extension, '', $use_filename);
        $slugify = new Slugify();
        $this->use_filename = $slugify->slugify($filename) . ".$extension";

        return $this;
    }

    public function getExclusive(): ?bool
    {
        return $this->exclusive;
    }

    public function setExclusive(bool $exclusive): self
    {
        $this->exclusive = $exclusive;

        return $this;
    }

    public function getDateExpire(): ?\DateTimeInterface
    {
        return $this->date_expire;
    }

    public function setDateExpire(?\DateTimeInterface $date_expire): self
    {
        $this->date_expire = $date_expire;

        return $this;
    }

    public function getNbDaysAccessibles(): ?int
    {
        return $this->nb_days_accessibles;
    }

    public function setNbDaysAccessibles(?int $nb_days_accessibles): self
    {
        $this->nb_days_accessibles = $nb_days_accessibles;

        return $this;
    }

    public function getNbDownloadable(): ?int
    {
        return $this->nb_downloadable;
    }

    public function setNbDownloadable(?int $nb_downloadable): self
    {
        $this->nb_downloadable = $nb_downloadable;

        return $this;
    }
}
