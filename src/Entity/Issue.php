<?php

namespace App\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: 'App\Repository\IssueRepository')]
class Issue
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column(type: 'integer')]
    private $id;

    #[ORM\Column(type: 'date')]
    private $issueDate;

    #[ORM\Column(type: 'string', length: 255)]
    private $issueNumber;

    #[ORM\Column(type: 'integer', nullable: false)]
    private $pageCount;

    #[ORM\ManyToOne(targetEntity: Volume::class, inversedBy: 'issues')]
    #[ORM\JoinColumn(name: 'volume_id', referencedColumnName: 'id', nullable: false)]
    private $volume;

    #[ORM\Column(type: 'string', length: 255, nullable: true, unique: true)]
    private $archiveKey;

    #[ORM\Column(type: 'text', nullable: true)]
    private $archiveNotes;

    #[ORM\OneToMany(targetEntity: 'App\Entity\Article', mappedBy: 'issue')]
    #[ORM\OrderBy(['printSection' => 'ASC', 'datePublished' => 'DESC'])]
    private $articles;

    #[ORM\Column(type: 'string', length: 255, nullable: true, unique: true)]
    private $utmDigitalArchiveUrl;

    public function __construct()
    {
        $this->issueDate = new \DateTime();
        $this->articles = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getIssueDate(): ?\DateTimeInterface
    {
        return $this->issueDate;
    }

    public function setIssueDate(\DateTimeInterface $issueDate): self
    {
        $this->issueDate = $issueDate;

        return $this;
    }

    public function getIssueNumber(): ?string
    {
        return $this->issueNumber;
    }

    public function setIssueNumber(string $issueNumber): self
    {
        $this->issueNumber = $issueNumber;

        return $this;
    }

    public function getPageCount(): ?int
    {
        return $this->pageCount;
    }

    public function setPageCount(int $pageCount): self
    {
        $this->pageCount = $pageCount;

        return $this;
    }

    public function getVolume(): ?Volume
    {
        return $this->volume;
    }

    public function setVolume(Volume $volume): self
    {
        $this->volume = $volume;

        return $this;
    }

    public function getArchiveKey(): ?string
    {
        return $this->archiveKey;
    }

    public function setArchiveKey(?string $archiveKey): self
    {
        $this->archiveKey = $archiveKey;

        return $this;
    }

    public function getArchiveNotes(): ?string
    {
        return $this->archiveNotes;
    }

    public function setArchiveNotes(?string $archiveNotes): self
    {
        $this->archiveNotes = $archiveNotes;

        return $this;
    }

    /* model methods */

    public function __toString()
    {
        return $this->issueDate->format('F j, Y');
    }

    /**
     * @return Collection|Article[]
     */
    public function getArticles(): Collection
    {
        return $this->articles;
    }

    public function addArticle(Article $article): self
    {
        if (!$this->articles->contains($article)) {
            $this->articles[] = $article;
            $article->setIssue($this);
        }

        return $this;
    }

    public function removeArticle(Article $article): self
    {
        if ($this->articles->contains($article)) {
            $this->articles->removeElement($article);
            // set the owning side to null (unless already changed)
            if ($article->getIssue() === $this) {
                $article->setIssue(null);
            }
        }

        return $this;
    }

    public function getUtmDigitalArchiveUrl(): ?string
    {
        return $this->utmDigitalArchiveUrl;
    }

    public function setUtmDigitalArchiveUrl(?string $utmDigitalArchiveUrl): self
    {
        $this->utmDigitalArchiveUrl = $utmDigitalArchiveUrl;

        return $this;
    }
}
