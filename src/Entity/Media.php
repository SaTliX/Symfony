<?php

namespace App\Entity;

use App\Repository\MediaRepository;
use Doctrine\ORM\Mapping as ORM;
use PhpParser\Node\Scalar\MagicConst\File;
use Vich\UploaderBundle\Mapping\Annotation as Vich;

#[Vich\Uploadable]
#[ORM\Entity(repositoryClass: MediaRepository::class)]
class Media
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 255)]
    private ?string $name = null;

    #[ORM\Column(length: 255, nullable: true)]
    private ?string $altText = null;

    #[Vich\UploadableField(mapping:"cours_pdfs", fileNameProperty:"Filename")]
    private $pdfFile = null;

    #[ORM\Column(length: 255)]
    private ?string $filename = null;


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

    public function getAltText(): ?string
    {
        return $this->altText;
    }

    public function setAltText(?string $altText): static
    {
        $this->altText = $altText;

        return $this;
    }

    public function getFilename(): ?string
    {
        return $this->filename;
    }

    public function setFilename(string $filename): static
    {
        $this->filename = $filename;

        return $this;
    }

    // Getter et setter pour le champ pdfFile
    public function getPdfFile(): ? File
    {
        return $this->pdfFile;
    }

    public function setPdfFile(?File $pdfFile): static
    {
        $this->pdfFile = $pdfFile;

        return $this;
    }

    public function __toString(): string
    {
        return $this->getName() ?? 'Media ID: ' . $this->getId();
    }

}

