<?php

namespace App\Entity;

use App\Repository\MissionRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;

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
     * @Assert\NotBlank(message="This field cannot be blank.")
     */
    private $description;

    /**
     * @ORM\Column(type="integer")
     * @Assert\NotBlank(message="This field cannot be blank.")
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

    /**
     * @ORM\Column(type="string", length=255)
     * @Assert\NotBlank(message="This field cannot be blank.")
     */
    private $attribute;

    /**
     * @ORM\Column(type="string", length=255)
     * @Assert\NotBlank(message="This field cannot be blank.")
     */
    private $operator;

    /**
     * @ORM\Column(type="integer")
     * @Assert\NotBlank(message="This field cannot be blank.")
     * @Assert\Range(
     *      min = 0,
     *      minMessage = "the condition cannot be negative.",
     * )
     */
    private $variable;


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

    public function getAttribute(): ?string
    {
        return $this->attribute;
    }

    public function setAttribute(string $attribute): self
    {
        $this->attribute = $attribute;

        return $this;
    }

    public function getOperator(): ?string
    {
        return $this->operator;
    }

    public function setOperator(string $operator): self
    {
        $this->operator = $operator;

        return $this;
    }

    public function getVariable(): ?int
    {
        return $this->variable;
    }

    public function setVariable(int $variable): self
    {
        $this->variable = $variable;

        return $this;
    }
}
