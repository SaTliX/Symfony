<?php

// src/Entity/Menu.php

namespace App\Entity;

use App\Repository\MenuRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: MenuRepository::class)]
class Menu
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 255)]
    private ?string $name = null;

    #[ORM\Column(nullable: true)]
    private ?int $menuOrder = null;

    #[ORM\ManyToMany(targetEntity: self::class, mappedBy: 'subMenus')]
    private Collection $parentMenus;

    #[ORM\ManyToMany(targetEntity: self::class, inversedBy: 'parentMenus')]
    #[ORM\JoinTable(name: 'menu_submenus')]
    private Collection $subMenus;

    #[ORM\Column]
    private ?bool $isVisible = null;

    #[ORM\ManyToOne]
    private ?Cours $cours = null;

    #[ORM\ManyToOne]
    #[ORM\JoinColumn(nullable: false)]
    private ?Categorie $categorie = null;

    #[ORM\ManyToOne]
    private ?Qcm $qcm = null;

    #[ORM\Column(length: 255, nullable: true)]
    private ?string $link = null;

    public function __construct()
    {
        $this->parentMenus = new ArrayCollection();
        $this->subMenus = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getName(): ?string
    {
        return $this->name;
    }

    public function setName(string $name): static
    {
        $this->name = $name;

        return $this;
    }

    public function getMenuOrder(): ?int
    {
        return $this->menuOrder;
    }

    public function setMenuOrder(?int $menuOrder): static
    {
        $this->menuOrder = $menuOrder;

        return $this;
    }

    /**
     * @return Collection<int, self>
     */
    public function getParentMenus(): Collection
    {
        return $this->parentMenus;
    }

    public function addParentMenu(self $parentMenu): static
    {
        if (!$this->parentMenus->contains($parentMenu)) {
            $this->parentMenus->add($parentMenu);
            $parentMenu->addSubMenu($this);
        }

        return $this;
    }

    public function removeParentMenu(self $parentMenu): static
    {
        if ($this->parentMenus->removeElement($parentMenu)) {
            $parentMenu->removeSubMenu($this);
        }

        return $this;
    }

    /**
     * @return Collection<int, self>
     */
    public function getSubMenus(): Collection
    {
        return $this->subMenus;
    }

    public function addSubMenu(self $subMenu): static
    {
        if (!$this->subMenus->contains($subMenu)) {
            $this->subMenus->add($subMenu);
            $subMenu->addParentMenu($this);
        }

        return $this;
    }

    public function removeSubMenu(self $subMenu): static
    {
        if ($this->subMenus->removeElement($subMenu)) {
            $subMenu->removeParentMenu($this);
        }

        return $this;
    }

    public function isIsVisible(): ?bool
    {
        return $this->isVisible;
    }

    public function setIsVisible(bool $isVisible): static
    {
        $this->isVisible = $isVisible;

        return $this;
    }

    public function getCours(): ?Cours
    {
        return $this->cours;
    }

    public function setCours(?Cours $cours): static
    {
        $this->cours = $cours;

        return $this;
    }

    public function getCategorie(): ?Categorie
    {
        return $this->categorie;
    }

    public function setCategorie(?Categorie $categorie): static
    {
        $this->categorie = $categorie;

        return $this;
    }

    public function getQcm(): ?Qcm
    {
        return $this->qcm;
    }

    public function setQcm(?Qcm $qcm): static
    {
        $this->qcm = $qcm;

        return $this;
    }

    public function getLink(): ?string
    {
        return $this->link;
    }

    public function setLink(?string $link): static
    {
        $this->link = $link;

        return $this;
    }
}
