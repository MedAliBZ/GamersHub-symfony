<?php

namespace App\Entity;

use App\Repository\EquipeRepository;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass=EquipeRepository::class)
 */
class Equipe
{
    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\Column(type="integer")
     */
    private $equipeId;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $equipeName;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $creater;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $verified;

    /**
     * @ORM\Column(type="integer")
     */
    private $nbJoueurs;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $rank;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getEquipeId(): ?int
    {
        return $this->equipeId;
    }

    public function setEquipeId(int $equipeId): self
    {
        $this->equipeId = $equipeId;

        return $this;
    }

    public function getEquipeName(): ?string
    {
        return $this->equipeName;
    }

    public function setEquipeName(string $equipeName): self
    {
        $this->equipeName = $equipeName;

        return $this;
    }

    public function getCreater(): ?string
    {
        return $this->creater;
    }

    public function setCreater(string $creater): self
    {
        $this->creater = $creater;

        return $this;
    }

    public function getVerified(): ?string
    {
        return $this->verified;
    }

    public function setVerified(string $verified): self
    {
        $this->verified = $verified;

        return $this;
    }

    public function getNbJoueurs(): ?int
    {
        return $this->nbJoueurs;
    }

    public function setNbJoueurs(int $nbJoueurs): self
    {
        $this->nbJoueurs = $nbJoueurs;

        return $this;
    }

    public function getRank(): ?string
    {
        return $this->rank;
    }

    public function setRank(string $rank): self
    {
        $this->rank = $rank;

        return $this;
    }
}
