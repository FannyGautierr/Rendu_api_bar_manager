<?php

namespace App\Entity;

use App\Enum\OrderStatus;
use App\Repository\OrderRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use ApiPlatform\Metadata\ApiResource;

use Symfony\Component\Serializer\Annotation\Groups;
use ApiPlatform\Metadata\GetCollection;
use ApiPlatform\Metadata\Get;
use ApiPlatform\Metadata\Post;
use ApiPlatform\Metadata\Put;
use ApiPlatform\Metadata\Patch;
use ApiPlatform\Metadata\Delete;
use App\State\UserPasswordHasherProcessor;
use App\State\OrderProcessor;
use ApiPlatform\Metadata\ApiFilter;
use ApiPlatform\Doctrine\Orm\Filter\SearchFilter;
use ApiPlatform\Doctrine\Orm\Filter\DateFilter;


#[ApiResource(
    normalizationContext: ['groups' => ['read']],
    denormalizationContext: ['groups' => ['write']],
    // forceEager: false,
    operations:[
        new GetCollection(),
        new Post(processor:OrderProcessor::class, security: "is_granted('ROLE_WAITER')", securityMessage: 'You are not allowed to create an order'),
        // new Get(security: "is_granted('ROLE_ADMIN') or object == user", securityMessage: 'You are not allowed to get this user'),
        new Get(),
        new Put(),
        new Patch(processor:OrderProcessor::class, security: "is_granted('ROLE_WAITER') || is_granted('ROLE_BARMAN')", securityMessage: 'You are not allowed to edit this order'),
        // new Delete(security: "is_granted('ROLE_ADMIN') or object == user", securityMessage: 'You are not allowed to delete this user'),
        new Delete(),
    ]
)]
#[ApiFilter(DateFilter::class, properties: ['createdAt'=>DateFilter::EXCLUDE_NULL])]
#[ORM\Entity(repositoryClass: OrderRepository::class)]
#[ORM\Table(name: '`order`')]

class Order
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    #[Groups(['read'])]
    private ?int $id = null;

    #[ORM\Column]
    #[Groups(['read'])]
    private ?\DateTimeImmutable $createdAt = null;

    /**
     * @var Collection<int, Drink>
     */
    #[ORM\ManyToMany(targetEntity: Drink::class, inversedBy: 'orders')]
    #[Groups(['write', 'read'])]
    private Collection $drinks;

    #[ORM\Column]
    #[Groups(['write', 'read'])]
    private ?int $table_number = null;

    #[ORM\ManyToOne(inversedBy: 'orders')]
    #[Groups(['write', 'read'])]
    private ?User $waiter = null;

    #[ORM\ManyToOne(inversedBy: 'orders')]
    #[Groups(['write', 'read'])]
    private ?User $barman = null;

    #[ORM\Column(enumType: OrderStatus::class)]
    #[Groups(['write', 'read'])]
    private ?OrderStatus $status = null;

    public function __construct()
    {
        $this->drinks = new ArrayCollection();
        $this->createdAt = new \DateTimeImmutable();
        $this->status = OrderStatus::In_progress;
        // assign the user who send the request with the jwt

    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getCreatedAt(): ?\DateTimeImmutable
    {
        return $this->createdAt;
    }

    public function setCreatedAt(\DateTimeImmutable $createdAt): static
    {
        $this->createdAt = $createdAt;

        return $this;
    }

    /**
     * @return Collection<int, Drink>
     */
    public function getDrinks(): Collection
    {
        return $this->drinks;
    }

    public function addDrink(Drink $drink): static
    {
        if (!$this->drinks->contains($drink)) {
            $this->drinks->add($drink);
        }

        return $this;
    }

    public function removeDrink(Drink $drink): static
    {
        $this->drinks->removeElement($drink);

        return $this;
    }

    public function getTableNumber(): ?int
    {
        return $this->table_number;
    }

    public function setTableNumber(int $table_number): static
    {
        $this->table_number = $table_number;

        return $this;
    }

    public function getWaiter(): ?User
    {
        return $this->waiter;
    }

    public function setWaiter(?User $waiter): static
    {
        $this->waiter = $waiter;

        return $this;
    }

    public function getBarman(): ?User
    {
        return $this->barman;
    }

    public function setBarman(?User $barman): static
    {
        $this->barman = $barman;

        return $this;
    }

    public function getStatus(): ?OrderStatus
    {
        return $this->status;
    }

    public function setStatus(OrderStatus $status): static
    {
        $this->status = $status;

        return $this;
    }

}
