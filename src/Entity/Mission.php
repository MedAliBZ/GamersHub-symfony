<?php

namespace App\Entity;

use App\Repository\MissionRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass=MissionRepository::class)
 */
class Mission
{
    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\Column(type="text")
     */
    private $description;

    /**
     * @ORM\Column(type="integer")
     */
    private $prize;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $badge;

    /**
     * @ORM\OneToMany(targetEntity=MissionsDone::class, mappedBy="mission", orphanRemoval=true)
     */
    private $missionsDones;


    public function __construct()
    {
        $this->missionsDones = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
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

    public function getPrize(): ?int
    {
        return $this->prize;
    }

    public function setPrize(int $prize): self
    {
        $this->prize = $prize;

        return $this;
    }

    public function getBadge()
    {
        return $this->badge;
    }

    public function setBadge($badge): self
    {
        $this->badge = $badge;

        return $this;
    }

    /**
     * @return Collection|MissionsDone[]
     */
    public function getMissionsDones(): Collection
    {
        return $this->missionsDones;
    }

    public function addMissionsDone(MissionsDone $missionsDone): self
    {
        if (!$this->missionsDones->contains($missionsDone)) {
            $this->missionsDones[] = $missionsDone;
            $missionsDone->setMission($this);
        }

        return $this;
    }

    public function removeMissionsDone(MissionsDone $missionsDone): self
    {
        if ($this->missionsDones->removeElement($missionsDone)) {
            // set the owning side to null (unless already changed)
            if ($missionsDone->getMission() === $this) {
                $missionsDone->setMission(null);
            }
        }

        return $this;
    }
}
