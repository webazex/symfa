<?php

namespace App\Entity;

//use ApiPlatform\Metadata\ApiResource;
use Doctrine\ORM\Mapping as ORM;
class Product
{
    #[ORM\Id]
    #[ORM\GeneratedValue(strategy: 'AUTO')]
    #[ORM\Column(type: 'integer')]
    private int $id;

    #[ORM\Column(type: 'string')]
    private string $name;

    #[ORM\Column(type: 'decimal')]
    private float $price;
}
