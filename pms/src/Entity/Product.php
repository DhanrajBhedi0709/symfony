<?php

namespace App\Entity;

use App\Repository\ProductRepository;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * @ORM\Entity(repositoryClass=ProductRepository::class)
 */
class Product
{
    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\Column(type="string", length=100)
     */
    private $Name;

    /**
     * @ORM\Column(type="string", length=50)
     */
    private $Category;

    /**
     * @ORM\Column(type="integer")
     */
    private $Price;

    /**
     * @ORM\Column(type="integer")
     */
    private $Quantity;

    /**
     * @ORM\Column(type="string", length=500)
     */
    private $Short_Description;

    /**
     * @ORM\Column(type="string", length=5000)
     */
    private $Long_Description;

    /**
     * @ORM\Column(type="string", length=500)
     */
    private $Small_img;

    /**
     * @ORM\Column(type="string", length=500)
     */
    private $Large_img;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getName(): ?string
    {
        return $this->Name;
    }

    public function setName(string $Name): self
    {
        $this->Name = $Name;

        return $this;
    }

    public function getCategory(): ?string
    {
        return $this->Category;
    }

    public function setCategory(string $Category): self
    {
        $this->Category = $Category;

        return $this;
    }

    public function getPrice(): ?int
    {
        return $this->Price;
    }

    public function setPrice(int $Price): self
    {
        $this->Price = $Price;

        return $this;
    }

    public function getQuantity(): ?int
    {
        return $this->Quantity;
    }

    public function setQuantity(int $Quantity): self
    {
        $this->Quantity = $Quantity;

        return $this;
    }

    public function getShortDescription(): ?string
    {
        return $this->Short_Description;
    }

    public function setShortDescription(string $Short_Description): self
    {
        $this->Short_Description = $Short_Description;

        return $this;
    }

    public function getLongDescription(): ?string
    {
        return $this->Long_Description;
    }

    public function setLongDescription(string $Long_Description): self
    {
        $this->Long_Description = $Long_Description;

        return $this;
    }

    public function getSmallImg(): ?string
    {
        return $this->Small_img;
    }

    public function setSmallImg(string $Small_img): self
    {
        $this->Small_img = $Small_img;

        return $this;
    }

    public function getLargeImg(): ?string
    {
        return $this->Large_img;
    }

    public function setLargeImg(string $Large_img): self
    {
        $this->Large_img = $Large_img;

        return $this;
    }
}
