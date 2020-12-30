<?php

declare(strict_types=1);

namespace App\Form\Model;

use Symfony\Component\Validator\Constraints as Assert;

class ProductFormModel
{
    /** 
     * @Assert\NotBlank()
     * @Assert\Length(max="255")
     * @var string|null 
     * */
    private $name;

    /** 
     * @Assert\NotBlank()
     * @Assert\Length(min="100")
     * @var string|null 
     * */
    private $description;

    /** 
     * @Assert\NotBlank()
     * @Assert\Positive
     * @var float|null 
     * */
    private $price;

    /** 
     * @Assert\NotBlank
     * @Assert\Currency
     * @var string|null 
     */
    private $currency;

    /**
     * @return string|null
     */
    public function getName(): ?string
    {
        return $this->name;
    }

    /**
     * @var string|null $name
     * 
     * @return Product
     */
    public function setName(?string $name): self
    {
        $this->name = $name;

        return $this;
    }

    /**
     * @return string|null
     */
    public function getDescription(): ?string
    {
        return $this->description;
    }

    /**
     * @var string|null $description
     * 
     * @return Product
     */
    public function setDescription(?string $description): self
    {
        $this->description = $description;

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
     * @var float|null $price
     * 
     * @return Product
     */
    public function setPrice(?float $price): self
    {
        $this->price = $price;

        return $this;
    }

    /**
     * @return string|null
     */
    public function getCurrency(): ?string
    {
        return $this->currency;
    }

    /**
     * @var string|null $currency
     * 
     * @return Product
     */
    public function setCurrency(?string $currency): self
    {
        $this->currency = $currency;

        return $this;
    }
}
