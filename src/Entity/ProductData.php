<?php

declare(strict_types=1);

namespace App\Entity;

use App\Repository\ProductDataRepository;
use Cassandra\Date;
use Doctrine\Common\Annotations\Annotation\IgnoreAnnotation;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass=ProductDataRepository::class)
 * @ORM\Table(name="tblProductData")
 */
class ProductData
{
    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer", name="intProductDataId", options={"unsigned"=true})
     */
    private int $id;

    /**
     * @ORM\Column(type="string", length=50, name="strProductName")
     */
    private string $name;

    /**
     * @ORM\Column(type="string", length=255, name="strProductDesc")
     */
    private string $description;

    /**
     *
     * @ORM\Column(type="string", length=10, name="strProductCode", unique=true)
     */
    private string $code;

    /**
     * @ORM\Column(type="datetime", name="dtmAdded")
     */
    private \DateTime $added;

    /**
     * @ORM\Column(type="datetime", nullable=true, name="dtmDiscontinued")
     */
    private \DateTime $discontinued;

    /**
     * @ORM\Column(type="datetime", name="stmTimestamp")
     */
    private \DateTime $timestamp;

    /**
     * @ORM\Column(type="decimal", nullable=true, name="price", scale=2, precision=8)
     */
    private ?float $price;

    /**
     * @ORM\Column(type="integer", nullable=true)
     */
    private ?int $stock;

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

    public function getDescription(): ?string
    {
        return $this->description;
    }

    public function setDescription(string $description): self
    {
        $this->description = $description;

        return $this;
    }

    public function getCode(): ?string
    {
        return $this->code;
    }

    public function setCode(string $code): self
    {
        $this->code = $code;

        return $this;
    }

    public function getAdded(): ?\DateTimeInterface
    {
        return $this->added;
    }

    public function setAdded(\DateTimeInterface $added): self
    {
        $this->added = $added;

        return $this;
    }

    public function getDiscontinued(): ?\DateTimeInterface
    {
        return $this->discontinued;
    }

    public function setDiscontinued(?\DateTimeInterface $discontinued): self
    {
        $this->discontinued = $discontinued;

        return $this;
    }

    public function getTimestamp(): ?\DateTimeInterface
    {
        return $this->timestamp;
    }

    public function setTimestamp(\DateTimeInterface $timestamp): self
    {
        $this->timestamp = $timestamp;

        return $this;
    }

    /**
     * @return float|null
     */
    public function getPrice(): ?float
    {
        return $this->price;
    }

    /**
     * @param float|null $price
     */
    public function setPrice(?float $price): void
    {
        $this->price = $price;
    }

    /**
     * @return int|null
     */
    public function getStock(): ?int
    {
        return $this->stock;
    }

    /**
     * @param int|null $stock
     */
    public function setStock(?int $stock): void
    {
        $this->stock = $stock;
    }
}
