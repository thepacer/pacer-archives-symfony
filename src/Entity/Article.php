<?php

namespace App\Entity;

use Gedmo\Mapping\Annotation as Gedmo;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass="App\Repository\ArticleRepository")
 */
class Article
{
    /**
     * @ORM\Id()
     * @ORM\GeneratedValue()
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private $printColumn;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private $printPage;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private $printSection;

    /**
     * @ORM\Column(type="text")
     */
    private $articleBody;

    /**
     * @ORM\Column(type="text")
     */
    private $headline;

    /**
     * @ORM\Column(type="text")
     */
    private $alternativeHeadline;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $author_byline;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private $contributor_byline;

    /**
     * @ORM\Column(type="datetime")
     */
    private $dateCreated;

    /**
     * @ORM\Column(type="datetime")
     */
    private $datePublished;

    /**
     * @ORM\Column(type="datetime")
     */
    private $dateModified;

    /**
     * @ORM\Column(type="text")
     */
    private $keywords;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $modifiedBy;

    /**
     * @ORM\ManyToOne(targetEntity="App\Entity\Issue", inversedBy="articles")
     */
    private $issue;

    /**
     * @ORM\Column(type="integer", nullable=true)
     */
    private $legacyId;

    /**
     * @Gedmo\Slug(fields={"headline", "id"})
     * @ORM\Column(length=128, unique=true)
     */
    private $slug;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getPrintColumn(): ?string
    {
        return $this->printColumn;
    }

    public function setPrintColumn(?string $printColumn): self
    {
        $this->printColumn = $printColumn;

        return $this;
    }

    public function getPrintPage(): ?string
    {
        return $this->printPage;
    }

    public function setPrintPage(?string $printPage): self
    {
        $this->printPage = $printPage;

        return $this;
    }

    public function getPrintSection(): ?string
    {
        return $this->printSection;
    }

    public function setPrintSection(string $printSection): self
    {
        $this->printSection = $printSection;

        return $this;
    }

    public function getArticleBody(): ?string
    {
        return $this->articleBody;
    }

    public function setArticleBody(string $articleBody): self
    {
        $this->articleBody = $articleBody;

        return $this;
    }

    public function getHeadline(): ?string
    {
        return $this->headline;
    }

    public function setHeadline(string $headline): self
    {
        $this->headline = $headline;

        return $this;
    }

    public function getAlternativeHeadline(): ?string
    {
        return $this->alternativeHeadline;
    }

    public function setAlternativeHeadline(string $alternativeHeadline): self
    {
        $this->alternativeHeadline = $alternativeHeadline;

        return $this;
    }

    public function getAuthorByline(): ?string
    {
        return $this->author_byline;
    }

    public function setAuthorByline(string $author_byline): self
    {
        $this->author_byline = $author_byline;

        return $this;
    }

    public function getContributorByline(): ?string
    {
        return $this->contributor_byline;
    }

    public function setContributorByline(string $contributor_byline): self
    {
        $this->contributor_byline = $contributor_byline;

        return $this;
    }

    public function getDateCreated(): ?\DateTimeInterface
    {
        return $this->dateCreated;
    }

    public function setDateCreated(\DateTimeInterface $dateCreated): self
    {
        $this->dateCreated = $dateCreated;

        return $this;
    }

    public function getDatePublished(): ?\DateTimeInterface
    {
        return $this->datePublished;
    }

    public function setDatePublished(\DateTimeInterface $datePublished): self
    {
        $this->datePublished = $datePublished;

        return $this;
    }

    public function getDateModified(): ?\DateTimeInterface
    {
        return $this->dateModified;
    }

    public function setDateModified(\DateTimeInterface $dateModified): self
    {
        $this->dateModified = $dateModified;

        return $this;
    }

    public function getKeywords(): ?string
    {
        return $this->keywords;
    }

    public function setKeywords(string $keywords): self
    {
        $this->keywords = $keywords;

        return $this;
    }

    public function getModifiedBy(): ?string
    {
        return $this->modifiedBy;
    }

    public function setModifiedBy(string $modifiedBy): self
    {
        $this->modifiedBy = $modifiedBy;

        return $this;
    }

    public function getIssue(): ?Issue
    {
        return $this->issue;
    }

    public function setIssue(?Issue $issue): self
    {
        $this->issue = $issue;

        return $this;
    }

    public function getLegacyId(): ?int
    {
        return $this->legacyId;
    }

    public function setLegacyId(int $legacyId): self
    {
        $this->legacyId = $legacyId;

        return $this;
    }

    public function getSlug(): ?string
    {
        return $this->slug;
    }
}
