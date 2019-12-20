<?php

namespace App\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass="App\Repository\MgPropertiesRepository")
 */
class MgProperties
{
    /**
     * @ORM\Id()
     * @ORM\GeneratedValue()
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\Column(type="smallint")
     */
    private $position;

    /**
     * @ORM\OneToMany(targetEntity="App\Entity\MgPropertiesLang", mappedBy="property", orphanRemoval=true)
     */
    private $properties;

    /**
     * @ORM\OneToMany(targetEntity="App\Entity\MgPropertiesContents", mappedBy="property", orphanRemoval=true)
     */
    private $contents;

    public function __construct()
    {
        $this->properties = new ArrayCollection();
        $this->contents = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getPosition(): ?int
    {
        return $this->position;
    }

    public function setPosition(int $position): self
    {
        $this->position = $position;

        return $this;
    }

    /**
     * @return Collection|MgPropertiesLang[]
     */
    public function getProperties(): Collection
    {
        return $this->properties;
    }

    public function addProperty(MgPropertiesLang $property): self
    {
        if (!$this->properties->contains($property)) {
            $this->properties[] = $property;
            $property->setProperty($this);
        }

        return $this;
    }

    public function removeProperty(MgPropertiesLang $property): self
    {
        if ($this->properties->contains($property)) {
            $this->properties->removeElement($property);
            // set the owning side to null (unless already changed)
            if ($property->getProperty() === $this) {
                $property->setProperty(null);
            }
        }

        return $this;
    }

    /**
     * @return Collection|MgPropertiesContents[]
     */
    public function getContents(): Collection
    {
        return $this->contents;
    }

    public function addContent(MgPropertiesContents $content): self
    {
        if (!$this->contents->contains($content)) {
            $this->contents[] = $content;
            $content->setProperty($this);
        }

        return $this;
    }

    public function removeContent(MgPropertiesContents $content): self
    {
        if ($this->contents->contains($content)) {
            $this->contents->removeElement($content);
            // set the owning side to null (unless already changed)
            if ($content->getProperty() === $this) {
                $content->setProperty(null);
            }
        }

        return $this;
    }
}
