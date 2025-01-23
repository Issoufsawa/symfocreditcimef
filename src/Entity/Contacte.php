<?php
namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;

#[ORM\Entity]
class Contacte
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column(type: 'integer')]
    private ?int $id = null;

    #[Assert\NotBlank(message: 'Le nom ne peut pas être vide.')]
    #[Assert\Regex(
        pattern: '/^[A-Za-zÀ-ÿ\s]+$/u',
        message: 'Le nom ne peut contenir que des lettres.'
    )]
    #[ORM\Column(type: 'string', length: 255)]
    private ?string $nom = null;

    #[ORM\Column(type: 'string', length: 255)]
    private ?string $email = null;

    #[Assert\NotBlank(message: 'Le sujet ne peut pas être vide.')]
    #[Assert\Regex(
        pattern: '/^[A-Za-zÀ-ÿ\s]+$/u',
        message: 'Le sujet ne peut contenir que des lettres.'
    )]
    #[ORM\Column(type: 'string', length: 255)]
    private ?string $subject = null;

    #[Assert\NotBlank(message: 'Le message ne peut pas être vide.')]
    #[ORM\Column(type: 'text')]
    private ?string $message = null;

    #[ORM\Column(type: 'datetime')]
    private ?\DateTimeInterface $createAd = null;

  

    #[ORM\Column(type: 'string', length: 20)]
    #[Assert\NotBlank(message: 'Le numéro de téléphone est obligatoire.')]
    #[Assert\Regex(
        pattern: '/^\+?[0-9\s\-]{7,15}$/',
        message: 'Veuillez entrer un numéro de téléphone valide.'
    )]
    private ?string $phone = null;

    // Getter et Setter pour phone
    public function getPhone(): ?string
    {
        return $this->phone;
    }

    public function setPhone(string $phone): self
    {
        $this->phone = $phone;
        return $this;
    }

    // Getters et setters pour les autres champs
    public function getId(): ?int
    {
        return $this->id;
    }

    public function getCreateAd(): ?\DateTimeInterface
    {
        return $this->createAd;
    }

    public function setCreateAd(\DateTimeInterface $createAd): self
    {
        $this->createAd = $createAd;
        return $this;
    }

    public function getNom(): ?string
    {
        return $this->nom;
    }

    public function setName(string $nom): self
    {
        $this->nom = $nom;
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

    public function getSubject(): ?string
    {
        return $this->subject;
    }

    public function setSubject(string $subject): self
    {
        $this->subject = $subject;
        return $this;
    }

   

    public function getMessage(): ?string
    {
        return $this->message;
    }

    public function setMessage(string $message): self
    {
        $this->message = $message;
        return $this;
    }
}
