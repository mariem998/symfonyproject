<?php

namespace App\Entity;

use App\Repository\EditorsRepository;
use App\Traits\TimeStampTrait;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;

#[ORM\Entity(repositoryClass: EditorsRepository::class)]
class Editor
{


    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    /**
     * @ORM\Column(type="string", length=50)
     * @Assert\NotBlank(message="Veuillez renseigner ce champ")
     * @Assert\Length(min=4, minMessage="Veuillez avoir au moins 4 caractères")
     */
    private ?string $firstname = null;

    #[ORM\Column(length: 255)]
    #[Assert\Email(
        message: 'The email is not valid.',
    )]
    private ?string $email = null;

    #[ORM\ManyToOne(inversedBy: 'editors')]
    private ?Job $job = null;

//    #[ORM\Column(length: 255, nullable: true)]
//    private ?string $image = null;

//    #[ORM\Column(length: 100, nullable: true)]
//    private ?string $job = null;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getFirstname(): ?string
    {
        return $this->firstname;
    }

    public function setFirstname(string $firstname): self
    {
        $this->firstname = $firstname;

        return $this;
    }

    public function getEmail(): ?string
    {
        return $this->email;
    }

    public function setEmail(string $email): self
    {
        $this->email = $email;

        return $this;
    }

//    public function getJob(): ?string
//    {
//        return $this->job;
//    }
//
//    public function setJob(?string $job): self
//    {
//        $this->job = $job;
//
//        return $this;
//    }

public function getJob(): ?Job
{
    return $this->job;
}

public function setJob(?Job $job): self
{
    $this->job = $job;

    return $this;
}

//public function getImage(): ?string
//{
//    return $this->image;
//}
//
//public function setImage(?string $image): self
//{
//    $this->image = $image;
//
//    return $this;
//}
}
