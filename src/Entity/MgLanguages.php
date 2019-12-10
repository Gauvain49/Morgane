<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass="App\Repository\MgLanguagesRepository")
 */
class MgLanguages
{
    /**
     * @ORM\Id()
     * @ORM\GeneratedValue()
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\Column(type="string", length=100)
     */
    private $lang_name;

    /**
     * @ORM\Column(type="string", length=3)
     */
    private $lang_iso_code;

    /**
     * @ORM\Column(type="string", length=7)
     */
    private $lang_code;

    /**
     * @ORM\Column(type="boolean")
     */
    private $lang_default;

    /**
     * @ORM\Column(type="boolean")
     */
    private $lang_active;

    /**
     * @ORM\Column(type="string", length=50)
     */
    private $lang_img;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getLangName(): ?string
    {
        return $this->lang_name;
    }

    public function setLangName(string $lang_name): self
    {
        $this->lang_name = $lang_name;

        return $this;
    }

    public function getLangIsoCode(): ?string
    {
        return $this->lang_iso_code;
    }

    public function setLangIsoCode(string $lang_iso_code): self
    {
        $this->lang_iso_code = $lang_iso_code;

        return $this;
    }

    public function getLangCode(): ?string
    {
        return $this->lang_code;
    }

    public function setLangCode(string $lang_code): self
    {
        $this->lang_code = $lang_code;

        return $this;
    }

    public function getLangDefault(): ?bool
    {
        return $this->lang_default;
    }

    public function setLangDefault(bool $lang_default): self
    {
        $this->lang_default = $lang_default;

        return $this;
    }

    public function getLangActive(): ?bool
    {
        return $this->lang_active;
    }

    public function setLangActive(bool $lang_active): self
    {
        $this->lang_active = $lang_active;

        return $this;
    }

    public function getLangImg(): ?string
    {
        return $this->lang_img;
    }

    public function setLangImg(string $lang_img): self
    {
        $this->lang_img = $lang_img;

        return $this;
    }
}
