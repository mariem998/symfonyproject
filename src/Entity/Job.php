<?php

namespace App\Entity;

use App\Repository\JobRepository;
use App\Traits\TimeStampTrait;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: JobRepository::class)]

class Job
{


    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 255)]
    private ?string $designation = null;

    #[ORM\OneToMany(mappedBy: 'job', targetEntity: Editor::class)]
    private Collection $editors;

    public function __construct()
    {
        $this->editors = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getDesignation(): ?string
    {
        return $this->designation;
    }

    public function setDesignation(string $designation): self
    {
        $this->designation = $designation;

        return $this;
    }

    /**
     * @return Collection<int, Editor>
     */
    public function getEditors(): Collection
    {
        return $this->editors;
    }

    public function addEditor(Editor $editor): self
    {
        if (!$this->editors->contains($editor)) {
            $this->editors->add($editor);
            $editor->setJob($this);
        }

        return $this;
    }

    public function removeEditor(Editor $editor): self
    {
        if ($this->editors->removeElement($editor)) {
            // set the owning side to null (unless already changed)
            if ($editor->getJob() === $this) {
                $editor->setJob(null);
            }
        }

        return $this;
    }
    public function __toString(): string
    {
        return $this->designation;
    }
}
