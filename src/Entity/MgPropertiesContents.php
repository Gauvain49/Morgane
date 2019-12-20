<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass="App\Repository\MgPropertiesContentsRepository")
 */
class MgPropertiesContents
{
    /**
     * @ORM\Id()
     * @ORM\GeneratedValue()
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\ManyToOne(targetEntity="App\Entity\MgProperties", inversedBy="contents")
     * @ORM\JoinColumn(nullable=false)
     */
    private $property;

    /**
     * @ORM\ManyToOne(targetEntity="App\Entity\MgLanguages")
     * @ORM\JoinColumn(nullable=false)
     */
    private $lang;

    /**
     * @ORM\ManyToOne(targetEntity="App\Entity\MgProducts", inversedBy="propertiesContents")
     * @ORM\JoinColumn(nullable=false)
     */
    private $product;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $value;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getProperty(): ?MgProperties
    {
        return $this->property;
    }

    public function setProperty(?MgProperties $property): self
    {
        $this->property = $property;

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

    public function getProduct(): ?MgProducts
    {
        return $this->product;
    }

    public function setProduct(?MgProducts $product): self
    {
        $this->product = $product;

        return $this;
    }

    public function getValue(): ?string
    {
        return $this->value;
    }

    public function setValue(string $value): self
    {
        $this->value = $value;

        return $this;
    }
}
