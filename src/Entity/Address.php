<?php

namespace App\Entity;

use App\Repository\AddressRepository;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;


/**
 *
 */
#[ORM\Entity(repositoryClass: AddressRepository::class)]
class Address
{
    /**
     * @var int|null
     */
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    /**
     * @var User|null
     */
    #[ORM\ManyToOne(inversedBy: 'addresses')]
    #[ORM\JoinColumn(nullable: false)]
    private ?User $user = null;

    /**
     * @var string|null
     */
    #[ORM\Column(length: 255)]
    private ?string $name = null;

    /**
     * @var string|null
     */
    #[ORM\Column(length: 255)]
    private ?string $firstname = null;

    /**
     * @var string|null
     */
    #[ORM\Column(length: 255)]
    private ?string $lastname = null;

    /**
     * @var string|null
     */
    #[ORM\Column(length: 255, nullable: true)]
    private ?string $company = null;

    /**
     * @var string|null
     */

    #[ORM\Column(length: 255)]
    private ?string $address = null;

    /**
     * @var string|null
     */


    #[ORM\Column(length: 255)]
    private ?string $postal = null;

    /**
     * @var string|null
     */
    #[ORM\Column(length: 255)]
    private ?string $city = null;

    /**
     * @var string|null
     */
    #[ORM\Column(length: 255)]
    private ?string $country = null;

    /**
     *  #[Assert\Type(type: ['alpha', 'digit'])]
     *
     *
     * @var string|null
     */
    #[Assert\Type(type: ['alpha', 'digit'])]
    #[ORM\Column(length: 255)]
    private ?string $phone = null;

    /**
     * @return int|null
     */
    public function getId(): ?int
    {
        return $this->id;
    }

    /**
     * @return User|null
     */
    public function getUser(): ?User
    {
        return $this->user;
    }

    /**
     * @param User|null $user
     * @return $this
     */
    public function setUser(?User $user): static
    {
        $this->user = $user;

        return $this;
    }

    /**
     * @return string|null
     */
    public function getName(): ?string
    {
        return $this->name;
    }

    /**
     * @param string $name
     * @return $this
     */
    public function setName(string $name): static
    {
        $this->name = $name;

        return $this;
    }

    /**
     * @return string|null
     */
    public function getFirstname(): ?string
    {
        return $this->firstname;
    }

    /**
     * @param string $firstname
     * @return $this
     */
    public function setFirstname(string $firstname): static
    {
        $this->firstname = $firstname;

        return $this;
    }

    /**
     * @return string|null
     */
    public function getLastname(): ?string
    {
        return $this->lastname;
    }

    /**
     * @param string $lastname
     * @return $this
     */
    public function setLastname(string $lastname): static
    {
        $this->lastname = $lastname;

        return $this;
    }

    /**
     * @return string|null
     */
    public function getCompany(): ?string
    {
        return $this->company;
    }

    /**
     * @param string|null $company
     * @return $this
     */
    public function setCompany(?string $company): static
    {
        $this->company = $company;

        return $this;
    }

    /**
     * @return string|null
     */
    public function getAddress(): ?string
    {
        return $this->address;
    }

    /**
     * @param string $address
     * @return $this
     */
    public function setAddress(string $address): static
    {
        $this->address = $address;

        return $this;
    }

    /**
     * @return string|null
     */
    #[Assert\NotBlank(message: "Le code postal ne peut pas être vide.")]
    #[Assert\Regex(pattern: "/^\d{5}$/", message: "Le code postal doit contenir 5 chiffres.")]
    public function getPostal(): ?string
    {
        return $this->postal;
    }

    /**
     * @param string $postal
     * @return $this
     */

    public function setPostal(string $postal): static
    {
        $this->postal = $postal;

        return $this;
    }



    /**
     * @return string|null
     */
    public function getCity(): ?string
    {
        return $this->city;
    }

    /**
     * @param string $city
     * @return $this
     */
    public function setCity(string $city): static
    {
        $this->city = $city;

        return $this;
    }

    /**
     * @return string|null
     */
    public function getCountry(): ?string
    {
        return $this->country;
    }

    /**
     * @param string $country
     * @return $this
     */
    public function setCountry(string $country): static
    {
        $this->country = $country;

        return $this;
    }

    /**
     * @return string|null
     */
    public function getPhone(): ?string
    {
        return $this->phone;
    }

    /**
     * @param string $phone
     * @return $this
     * #[Assert\Type(type: ['alpha', 'digit'])]
     * #[Assert\Regex('/^\d+$/')]
     */
    public function setPhone(string $phone): static
    {
        $this->phone = $phone;

        return $this;
    }
    // ...

    public function getFormattedAddress(): string
    {
        return sprintf('%s %s %s %s %s %s %s',
            $this->getFirstname(),
            $this->getLastname(),
            $this->getCompany() ? $this->getCompany() : '',
            $this->getAddress(),
            $this->getPostal(),
            $this->getCity(),
            $this->getCountry()
        );
    }


    /**
     * @return string
     */
    public function __toString(){
        return $this->name.'[br]'.$this->getAddress().'[br]'.$this->getCity().'-'.$this->getCountry();
    }
}
