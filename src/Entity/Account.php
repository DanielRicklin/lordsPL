<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;
use Doctrine\Common\Collections\Collection;
use Doctrine\Common\Collections\ArrayCollection;
use Symfony\Component\Serializer\Annotation\Groups;

/**
 * @ORM\Entity(repositoryClass="App\Repository\AccountRepository")
 */
class Account
{
    /**
     * @ORM\Id()
     * @ORM\GeneratedValue()
     * @ORM\Column(type="integer")
     * @Groups("account:read")
     */
    private $id;

    /**
     * @ORM\Column(type="string", length=255)
     * @Groups("account:read")
     */
    private $name;

    /**
     * @ORM\Column(type="integer")
     * @Groups("account:read")
     */
    private $might;

    /**
     * @ORM\ManyToOne(targetEntity="App\Entity\Guild", inversedBy="accounts")
     * @ORM\JoinColumn(nullable=false)
     * @Groups("account:read")
     */
    private $guild;

    /**
     * @ORM\OneToMany(targetEntity="App\Entity\Image", mappedBy="account")
     * @Groups("account:read")
     */
    private $images;

    /**
     * @ORM\Column(type="datetime")
     */
    private $date;

    public function __construct()
    {
        $this->images = new ArrayCollection();
    }

    // public function __toString()
    // {
    //     return $this->guild->getId();
    // }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getName(): ?string
    {
        return $this->name;
    }

    public function setName(string $name): self
    {
        $this->name = $name;

        return $this;
    }

    public function getMight(): ?int
    {
        return $this->might;
    }

    public function setMight(int $might): self
    {
        $this->might = $might;

        return $this;
    }

    public function getGuild(): ?Guild
    {
        return $this->guild;
    }

    public function setGuild(?Guild $guild): self
    {
        $this->guild = $guild;

        return $this;
    }

    /**
     * @return Collection|Image[]
     */
    public function getImages(): Collection
    {
        return $this->images;
    }

    public function addImage(Image $image): self
    {
        if (!$this->images->contains($image)) {
            $this->images[] = $image;
            $image->setAccount($this);
        }

        return $this;
    }

    public function removeImage(Image $image): self
    {
        if ($this->images->contains($image)) {
            $this->images->removeElement($image);
            // set the owning side to null (unless already changed)
            if ($image->getAccount() === $this) {
                $image->setAccount(null);
            }
        }

        return $this;
    }

    public function getDate(): ?\DateTimeInterface
    {
        return $this->date;
    }

    public function setDate(\DateTimeInterface $date): self
    {
        $this->date = $date;

        return $this;
    }
}
