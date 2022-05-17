<?php

namespace App\Entity;

use App\Repository\BlogRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;
use Symfony\Component\Serializer\Annotation\Groups;



/**
 * @ORM\Entity(repositoryClass=BlogRepository::class)
 */
class Blog
{
    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     * @Groups("post:read")
     */
    private $id;

    /**
     * @ORM\Column(type="string", length=255)
     * @Groups("post:read")
     */
    private $Title;

    /**
     * @ORM\Column(type="text")
     *  @Assert\NotBlank(message="this field is required")
     * @Groups("post:read")
     */
     
    private $description;


    /**
     * @ORM\GeneratedValue
     * @ORM\Column(type="datetime_immutable")
     * @Groups("post:read")
     */
    private $publishedAt;


    /**
     * @ORM\ManyToOne(targetEntity=User::class, inversedBy="blogs")
     * @ORM\JoinColumn(nullable=false)
     */
    private $user;

    /**
     * @ORM\Column(type="text", nullable=false)
     * @Groups("post:read")
     */
    private $image;

    /**
     * @ORM\OneToMany(targetEntity=Comments::class, mappedBy="blog", cascade={"remove"})
     * @ORM\JoinColumn(nullable=false)
     * 
     */
    private $comments;

    /**
     * @ORM\OneToMany(targetEntity=Spam::class, mappedBy="post", cascade={"remove"})
     */
    private $spam;

    /**
     * @ORM\Column(type="integer")
     * @Groups("post:read")
     */
    private $Views;



    public function __construct()
    {
        $this->comments = new ArrayCollection();
        $this->spam = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getTitle(): ?string
    {
        return $this->Title;
    }

    public function setTitle(string $Title): self
    {
        $this->Title = $Title;

        return $this;
    }

    public function getDescription(): ?string
    {
        return $this->description;
    }

    public function setDescription(string $description): self
    {
        $this->description = $description;

        return $this;
    }

    public function getPublishedAt(): ?\DateTimeImmutable
    {
        return $this->publishedAt;
    }

    public function setPublishedAt(\DateTimeImmutable $publishedAt): self
    {
        $this->publishedAt = $publishedAt;

        return $this;
    }

    public function getUser(): ?User
    {
        return $this->user;
    }

    public function setUser(?User $user): self
    {
        $this->user = $user;

        return $this;
    }
    public function __toString(){
    return ("$this.id") ;
    }

    public function getImage(): ?string
    {
        return $this->image;
    }

    public function setImage(?string $image): self
    {
        $this->image = $image;

        return $this;
    }

    /**
     * @return Collection<int, Comments>
     */
    public function getComments(): Collection
    {
        return $this->comments;
    }

    public function addComment(Comments $comment): self
    {
        if (!$this->comments->contains($comment)) {
            $this->comments[] = $comment;
            $comment->setBlog($this);
        }

        return $this;
    }

    public function removeComment(Comments $comment): self
    {
        if ($this->comments->removeElement($comment)) {
            // set the owning side to null (unless already changed)
            if ($comment->getBlog() === $this) {
                $comment->setBlog(null);
            }
        }

        return $this;
    }

    /**
     * @return Collection<int, Spam>
     */
    public function getSpam(): Collection
    {
        return $this->spam;
    }

    public function addSpam(Spam $spam): self
    {
        if (!$this->spam->contains($spam)) {
            $this->spam[] = $spam;
            $spam->setPost($this);
        }

        return $this;
    }

    public function removeSpam(Spam $spam): self
    {
        if ($this->spam->removeElement($spam)) {
            // set the owning side to null (unless already changed)
            if ($spam->getPost() === $this) {
                $spam->setPost(null);
            }
        }

        return $this;
    }

    public function getViews(): ?int
    {
        return $this->Views;
    }

    public function setViews(int $Views): self
    {
        $this->Views = $Views;

        return $this;
    }
}
