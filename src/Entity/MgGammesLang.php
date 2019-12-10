<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass="App\Repository\MgGammesLangRepository")
 */
class MgGammesLang
{
    /**
     * @ORM\Id()
     * @ORM\GeneratedValue()
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\ManyToOne(targetEntity="App\Entity\MgGammes", inversedBy="gammesLangs")
     * @ORM\JoinColumn(nullable=false)
     */
    private $gamme;

    /**
     * @ORM\ManyToOne(targetEntity="App\Entity\MgLanguages")
     * @ORM\JoinColumn(nullable=false)
     */
    private $lang;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $gamme_name;

    /**
     * @ORM\Column(type="text", nullable=true)
     */
    private $gamme_description;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getGamme(): ?MgGammes
    {
        return $this->gamme;
    }

    public function setGamme(?MgGammes $gamme): self
    {
        $this->gamme = $gamme;

        return $this;
    }

    public function getLang(): ?MgLanguages
    {
        return $this->lang;
    }

    public function setLang(?MgLanguages $lang): self
    {
        $this->lang = $lang;

        return $this;
    }

    public function getGammeName(): ?string
    {
        return $this->gamme_name;
    }

    public function setGammeName(string $gamme_name): self
    {
        $this->gamme_name = $gamme_name;

        return $this;
    }

    public function getGammeDescription(): ?string
    {
        return $this->gamme_description;
    }

    public function setGammeDescription(?string $gamme_description): self
    {
        $this->gamme_description = $gamme_description;

        return $this;
    }
}
