<?php

namespace App\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * @ORM\Entity(repositoryClass="App\Repository\MgPostsRepository")
 */
class MgPosts
{
    //ENUM de la colonne status
    const PUBLISH = 'publish';
    const DRAFT = 'draft';
    const WAIT = 'wait';
    const TRASH = 'trash';
    //ENUM de la colonne type
    const PAGE = 'page';
    const POST = 'post';
    const ATTACHMENT = 'attachment';
    const REVISION = 'revision';

    private $statusValues = array(
        self::PUBLISH, self::DRAFT, self::WAIT, self::TRASH
    );
    private $typeValues = array(
        self::PAGE, self::POST, self::ATTACHMENT, self::REVISION
    );

    /**
     * @ORM\Id()
     * @ORM\GeneratedValue()
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\ManyToOne(targetEntity="App\Entity\MgPosts", inversedBy="posts")
     */
    private $parent;

    /**
     * @ORM\Column(type="string", length=10)
     */
    private $status;

    /**
     * @ORM\Column(type="string", length=15)
     */
    private $type;

    /**
     * @ORM\Column(type="string", length=25, nullable=true)
     */
    private $mime_type;

    /**
     * @ORM\Column(type="string", length=150, nullable=true)
     */
    private $sizes;

    /**
     * @ORM\Column(type="integer", nullable=true)
     */
    private $position;

    /**
     * @ORM\Column(type="string", length=100, nullable=true)
     */
    private $password;

    /**
     * @ORM\Column(type="boolean")
     */
    private $comment;

    /**
     * @ORM\Column(type="string", length=100, nullable=true)
     *
     * @Assert\File(mimeTypes={
     *     "image/png",
     *     "image/jpeg",
     *     "image/jpg",
     *     "image/gif",
     *     "application/pdf",
     *     "application/x-pdf"
     * })
     */
    private $filename;

    /**
     * @ORM\Column(type="string", length=25, nullable=true)
     */
    private $reserved;

    /**
     * @ORM\Column(type="datetime")
     */
    private $date_add;

    /**
     * @ORM\Column(type="datetime")
     */
    private $date_publish;

    /**
     * @ORM\Column(type="datetime", nullable=true)
     */
    private $date_expire;

    /**
     * @ORM\ManyToOne(targetEntity="App\Entity\MgUsers", inversedBy="posts")
     * @ORM\JoinColumn(nullable=false)
     */
    private $user;

    /**
     * @ORM\OneToMany(targetEntity="App\Entity\MgPosts", mappedBy="parent")
     */
    private $posts;

    /**
     * @ORM\OneToMany(targetEntity="App\Entity\MgPostsLang", mappedBy="post", orphanRemoval=true)
     */
    private $contents;

    /**
     * @ORM\ManyToMany(targetEntity="App\Entity\MgCategories", inversedBy="posts")
     */
    private $categories;

    public function __construct()
    {
        $this->posts = new ArrayCollection();
        $this->contents = new ArrayCollection();
        $this->date_add = new \Datetime();
        $this->categories = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getParent(): ?self
    {
        return $this->parent;
    }

    public function setParent(?self $parent): self
    {
        $this->parent = $parent;

        return $this;
    }

    public function getStatus(): ?string
    {
        return $this->status;
    }

    public function setStatus(string $status): self
    {
        if (!in_array($status, $this->statusValues)) {
            throw new \InvalidArgumentException(
                sprintf('Valeur invalide pour mg_posts.status : %s.', $status)
            );
        }
        $this->status = $status;

        return $this;
    }

    public function getType(): ?string
    {
        return $this->type;
    }

    public function setType(string $type): self
    {
        if (!in_array($type, $this->typeValues)) {
            throw new \InvalidArgumentException(
                sprintf('Valeur invalide pour mg_posts.type : %s.', $type)
            );
        }
        $this->type = $type;

        return $this;
    }

    public function getMimeType(): ?string
    {
        return $this->mime_type;
    }

    public function setMimeType(?string $mime_type): self
    {
        $this->mime_type = $mime_type;

        return $this;
    }

    public function getSizes(): ?string
    {
        return $this->sizes;
    }

    public function setSizes(?string $sizes): self
    {
        $this->sizes = $sizes;

        return $this;
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

    public function getPassword(): ?string
    {
        return $this->password;
    }

    public function setPassword(?string $password): self
    {
        $this->password = $password;

        return $this;
    }

    public function getComment(): ?bool
    {
        return $this->comment;
    }

    public function setComment(bool $comment): self
    {
        $this->comment = $comment;

        return $this;
    }

    public function getFilename()
    {
        return $this->filename;
    }

    public function setFilename($filename): self
    {
        $this->filename = $filename;

        return $this;
    }

    public function getReserved(): ?string
    {
        return $this->reserved;
    }

    public function setReserved(?string $reserved): self
    {
        $this->reserved = $reserved;

        return $this;
    }

    public function getDateAdd(): ?\DateTimeInterface
    {
        return $this->date_add;
    }

    public function setDateAdd(\DateTimeInterface $date_add): self
    {
        $this->date_add = $date_add;

        return $this;
    }

    public function getDatePublish(): ?\DateTimeInterface
    {
        return $this->date_publish;
    }

    public function setDatePublish(?\DateTimeInterface $date_publish): self
    {
        if (empty($date_publish)) {
            $this->date_publish = new \Datetime();
        } else {
            $this->date_publish = $date_publish;
        }

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

    public function getUser(): ?MgUsers
    {
        return $this->user;
    }

    public function setUser(?MgUsers $user): self
    {
        $this->user = $user;

        return $this;
    }

    /**
     * @return Collection|self[]
     */
    public function getPosts(): Collection
    {
        return $this->posts;
    }

    public function addPost(self $post): self
    {
        if (!$this->posts->contains($post)) {
            $this->posts[] = $post;
            $post->setParent($this);
        }

        return $this;
    }

    public function removePost(self $post): self
    {
        if ($this->posts->contains($post)) {
            $this->posts->removeElement($post);
            // set the owning side to null (unless already changed)
            if ($post->getParent() === $this) {
                $post->setParent(null);
            }
        }

        return $this;
    }

    /**
     * @return Collection|MgPostsLang[]
     */
    public function getContents(): Collection
    {
        return $this->contents;
    }

    public function addContent(MgPostsLang $content): self
    {
        if (!$this->contents->contains($content)) {
            $this->contents[] = $content;
            $content->setPost($this);
        }

        return $this;
    }

    public function removeContent(MgPostsLang $content): self
    {
        if ($this->contents->contains($content)) {
            $this->contents->removeElement($content);
            // set the owning side to null (unless already changed)
            if ($content->getPost() === $this) {
                $content->setPost(null);
            }
        }

        return $this;
    }

    /**
     * @return Collection|MgCategories[]
     */
    public function getCategories(): Collection
    {
        return $this->categories;
    }

    public function addCategory(MgCategories $category): self
    {
        if (!$this->categories->contains($category)) {
            $this->categories[] = $category;
        }

        return $this;
    }

    public function removeCategory(MgCategories $category): self
    {
        if ($this->categories->contains($category)) {
            $this->categories->removeElement($category);
        }

        return $this;
    }
}
