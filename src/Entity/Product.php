<?php

namespace App\Entity;

use App\Repository\ProductRepository;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\HttpFoundation\File\UploadedFile;

//use vich\UploaderBundle\Mapping\Annotation as Vich;

#[ORM\Entity(repositoryClass: ProductRepository::class)]
//#[Vich\Uploadable]
#[ORM\Table(name: 'product')]
class Product
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    public function getId(): ?int
    {
        return $this->id;
    }

    #[ORM\Column(length: 255)]
    private ?string $name = null;
    //#[ORM\Column(length: 255, nullable: true, options: ['default' => null])]
  //  private ?string $slug;

    #[ORM\Column(length: 255)]
    //#[Vich\UploadableField(mapping: "product_images", fileNameProperty: "illustration")]
    public ?string $illustration = null;

    #[ORM\Column(length: 255)]
    public ?string $subtitle = null;

    #[ORM\Column(type: 'text')]
    public ?string $description = null;

    #[ORM\Column]
    public ?float $price = null;

    #[ORM\ManyToOne(inversedBy: 'products')]
    #[ORM\JoinColumn(nullable: false)]
    public ?Category $category = null;

    #[ORM\Column]
    private ?bool $isBest = null;


    public function getName(): ?string
    {
        return $this->name;
    }

    public function setName(string $name): static
    {
        $this->name = $name;

        return $this;
    }

   /* public function getSlug(): ?string
    {
        return $this->slug;
    }

    public function setSlug(string $slug): static
    {
        $this->slug = $slug;

        return $this;
   }*/

    public function getIllustration(): ?string
    {
        return $this->illustration;
    }

    public function setIllustration(?string $illustration): static
    {
        $this->illustration = $illustration;

        return $this;
    }
    private $illustrationFile;

    public function getIllustrationFile(): ?UploadedFile
    {
        return $this->illustrationFile;
    }

    public function setIllustrationFile(UploadedFile $illustrationFile): self
    {
        $this->illustrationFile = $illustrationFile;

        // Générez un nom de fichier unique
        $filename = md5(uniqid()) . '.' . $illustrationFile->guessExtension();
        $this->illustration = $filename;

        return $this;
    }

    public function getSubtitle(): ?string
    {
        return $this->subtitle;
    }


    public function setCategory(?Category $category): self
    {
        $this->category = $category;

        return $this;
    }

    public function setSubtitle(string $subtitle)
    {
        $this->subtitle = $subtitle;

        return $this;
    }

    public function setDescription(string $description)
    {
        $this->description = $description;

        return $this;
    }

    /**
     * Get the price of the product.
     *
     * @return float|null
     */
    public function getPrice(): ?float
    {
        return $this->price;
    }
    public function setPrice(float $price)
    {
        $this->price = $price;

        return $this;
    }

    public function setSlug(string $slugify): static
    {
        $this->slug = $slugify;

        return $this;
    }

    public function isIsBest(): ?bool
    {
        return $this->isBest;
    }

    public function setIsBest(bool $isBest): static
    {
        $this->isBest = $isBest;

        return $this;
    }
}

