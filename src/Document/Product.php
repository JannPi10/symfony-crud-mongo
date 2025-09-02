<?php

namespace App\Document;

use DateTime;
use Doctrine\ODM\MongoDB\Mapping\Annotations as ODM;
use Symfony\Component\Validator\Constraints as Assert;
use JsonSerializable;

#[ODM\Document(collection: 'products')]
class Product implements JsonSerializable
{
    #[ODM\Id]
    private ?string $id = null;

    #[ODM\Field(type: 'string')]
    #[Assert\NotBlank(message: 'El nombre es obligatorio')]
    #[Assert\Length(min: 2, max: 100, minMessage: 'El nombre debe tener al menos 2 caracteres')]
    private ?string $name = null;

    #[ODM\Field(type: 'string')]
    #[Assert\NotBlank(message: 'La descripción es obligatoria')]
    #[Assert\Length(min: 10, minMessage: 'La descripción debe tener al menos 10 caracteres')]
    private ?string $description = null;

    #[ODM\Field(type: 'float')]
    #[Assert\NotBlank(message: 'El precio es obligatorio')]
    #[Assert\Positive(message: 'El precio debe ser positivo')]
    private ?float $price = null;

    #[ODM\Field(type: 'int')]
    #[Assert\NotBlank(message: 'El stock es obligatorio')]
    #[Assert\PositiveOrZero(message: 'El stock no puede ser negativo')]
    private ?int $stock = null;

    #[ODM\Field(type: 'string')]
    #[Assert\Choice(choices: ['disponible', 'agotado', 'descontinuado'], message: 'Estado no válido')]
    private ?string $status = 'disponible';

    #[ODM\Field(type: 'date')]
    private ?DateTime $createdAt;

    #[ODM\Field(type: 'date')]
    private ?DateTime $updatedAt;

    public function __construct()
    {
        $this->createdAt = new DateTime();
        $this->updatedAt = new DateTime();
    }

    public function jsonSerialize(): array
    {
        return [
            'id' => $this->id,
            'name' => $this->name,
            'description' => $this->description,
            'price' => $this->price,
            'stock' => $this->stock,
            'status' => $this->status,
            'created_at' => $this->createdAt?->format('Y-m-d H:i:s'),
            'updated_at' => $this->updatedAt?->format('Y-m-d H:i:s'),
        ];
    }

    // Getters y Setters
    public function getId(): ?string
    {
        return $this->id;
    }

    public function getName(): ?string
    {
        return $this->name;
    }

    public function setName(?string $name): self
    {
        $this->name = $name;
        $this->updatedAt = new DateTime();
        return $this;
    }

    public function getDescription(): ?string
    {
        return $this->description;
    }

    public function setDescription(?string $description): self
    {
        $this->description = $description;
        $this->updatedAt = new DateTime();
        return $this;
    }

    public function getPrice(): ?float
    {
        return $this->price;
    }

    public function setPrice(?float $price): self
    {
        $this->price = $price;
        $this->updatedAt = new DateTime();
        return $this;
    }

    public function getStock(): ?int
    {
        return $this->stock;
    }

    public function setStock(?int $stock): self
    {
        $this->stock = $stock;
        $this->updatedAt = new DateTime();
        return $this;
    }

    public function getStatus(): ?string
    {
        return $this->status;
    }

    public function setStatus(?string $status): self
    {
        $this->status = $status;
        $this->updatedAt = new DateTime();
        return $this;
    }

    public function getCreatedAt(): ?DateTime
    {
        return $this->createdAt;
    }

    public function setCreatedAt(?DateTime $createdAt): self
    {
        $this->createdAt = $createdAt;
        return $this;
    }

    public function getUpdatedAt(): ?DateTime
    {
        return $this->updatedAt;
    }

    public function setUpdatedAt(?DateTime $updatedAt): self
    {
        $this->updatedAt = $updatedAt;
        return $this;
    }
}