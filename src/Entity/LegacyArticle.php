<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * Article
 *
 * @ORM\Table(name="legacy_article")
 * @ORM\Entity
 */
class LegacyArticle
{
    /**
     * @var int
     *
     * @ORM\Column(name="article_id", type="integer", nullable=false)
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="IDENTITY")
     */
    private $articleId;

    /**
     * @var \DateTime
     *
     * @ORM\Column(name="issue_id", type="string", nullable=false)
     */
    private $issueId;

    /**
     * @var string
     *
     * @ORM\Column(name="section_id", type="string", length=15, nullable=false)
     */
    private $sectionId = '';

    /**
     * @var string|null
     *
     * @ORM\Column(name="author_id", type="string", length=40, nullable=true)
     */
    private $authorId;

    /**
     * @var string|null
     *
     * @ORM\Column(name="co_author_id", type="string", length=40, nullable=true)
     */
    private $coAuthorId;

    /**
     * @var string|null
     *
     * @ORM\Column(name="author_title", type="string", length=40, nullable=true)
     */
    private $authorTitle;

    /**
     * @var string|null
     *
     * @ORM\Column(name="co_author_title", type="string", length=40, nullable=true)
     */
    private $coAuthorTitle;

    /**
     * @var string|null
     *
     * @ORM\Column(name="title", type="string", length=200, nullable=true)
     */
    private $title;

    /**
     * @var string|null
     *
     * @ORM\Column(name="subtitle", type="string", length=200, nullable=true)
     */
    private $subtitle;

    /**
     * @var string|null
     *
     * @ORM\Column(name="summary", type="text", length=65535, nullable=true)
     */
    private $summary;

    /**
     * @var string|null
     *
     * @ORM\Column(name="full_text", type="text", length=65535, nullable=true)
     */
    private $fullText;

    /**
     * @var string|null
     *
     * @ORM\Column(name="photo_src", type="string", length=150, nullable=true)
     */
    private $photoSrc;

    /**
     * @var string|null
     *
     * @ORM\Column(name="photo_align", type="string", length=15, nullable=true)
     */
    private $photoAlign;

    /**
     * @var string|null
     *
     * @ORM\Column(name="photo_border", type="string", length=2, nullable=true, options={"fixed"=true})
     */
    private $photoBorder;

    /**
     * @var int|null
     *
     * @ORM\Column(name="priority", type="integer", nullable=true)
     */
    private $priority = '0';

    /**
     * @var string|null
     *
     * @ORM\Column(name="photo_credit", type="string", length=40, nullable=true)
     */
    private $photoCredit;

    /**
     * @var string|null
     *
     * @ORM\Column(name="photo_caption", type="text", length=65535, nullable=true)
     */
    private $photoCaption;

    /**
     * @var string|null
     *
     * @ORM\Column(name="keywords", type="text", length=65535, nullable=true)
     */
    private $keywords;

    /**
     * @var string
     *
     * @ORM\Column(name="last_edited", type="string", length=200, nullable=false, options={"default"="Unknown"})
     */
    private $lastEdited = 'Unknown';

    public function getArticleId(): ?int
    {
        return $this->articleId;
    }

    public function getIssueId(): ?string
    {
        return $this->issueId;
    }

    public function setIssueId(\DateTimeInterface $issueId): self
    {
        $this->issueId = $issueId;

        return $this;
    }

    public function getSectionId(): ?string
    {
        return $this->sectionId;
    }

    public function setSectionId(string $sectionId): self
    {
        $this->sectionId = $sectionId;

        return $this;
    }

    public function getAuthorId(): ?string
    {
        return $this->authorId;
    }

    public function setAuthorId(?string $authorId): self
    {
        $this->authorId = $authorId;

        return $this;
    }

    public function getCoAuthorId(): ?string
    {
        return $this->coAuthorId;
    }

    public function setCoAuthorId(?string $coAuthorId): self
    {
        $this->coAuthorId = $coAuthorId;

        return $this;
    }

    public function getAuthorTitle(): ?string
    {
        return $this->authorTitle;
    }

    public function setAuthorTitle(?string $authorTitle): self
    {
        $this->authorTitle = $authorTitle;

        return $this;
    }

    public function getCoAuthorTitle(): ?string
    {
        return $this->coAuthorTitle;
    }

    public function setCoAuthorTitle(?string $coAuthorTitle): self
    {
        $this->coAuthorTitle = $coAuthorTitle;

        return $this;
    }

    public function getTitle(): ?string
    {
        return $this->title;
    }

    public function setTitle(?string $title): self
    {
        $this->title = $title;

        return $this;
    }

    public function getSubtitle(): ?string
    {
        return $this->subtitle;
    }

    public function setSubtitle(?string $subtitle): self
    {
        $this->subtitle = $subtitle;

        return $this;
    }

    public function getSummary(): ?string
    {
        return $this->summary;
    }

    public function setSummary(?string $summary): self
    {
        $this->summary = $summary;

        return $this;
    }

    public function getFullText(): ?string
    {
        return $this->fullText;
    }

    public function setFullText(?string $fullText): self
    {
        $this->fullText = $fullText;

        return $this;
    }

    public function getPhotoSrc(): ?string
    {
        $this->photoSrc = trim($this->photoSrc);
        $this->photoSrc = preg_replace('/\\\\/i', '/', $this->photoSrc);
        $this->photoSrc = preg_replace('/^\/photo_upload/i', 'photo_upload', $this->photoSrc);
        $this->photoSrc = preg_replace('/\/\//', '/', $this->photoSrc);
        return $this->photoSrc;
    }

    public function setPhotoSrc(?string $photoSrc): self
    {
        $this->photoSrc = $photoSrc;

        return $this;
    }

    public function getPhotoAlign(): ?string
    {
        return $this->photoAlign;
    }

    public function setPhotoAlign(?string $photoAlign): self
    {
        $this->photoAlign = $photoAlign;

        return $this;
    }

    public function getPhotoBorder(): ?string
    {
        return $this->photoBorder;
    }

    public function setPhotoBorder(?string $photoBorder): self
    {
        $this->photoBorder = $photoBorder;

        return $this;
    }

    public function getPriority(): ?int
    {
        return $this->priority;
    }

    public function setPriority(?int $priority): self
    {
        $this->priority = $priority;

        return $this;
    }

    public function getPhotoCredit(): ?string
    {
        return $this->photoCredit;
    }

    public function setPhotoCredit(?string $photoCredit): self
    {
        $this->photoCredit = $photoCredit;

        return $this;
    }

    public function getPhotoCaption(): ?string
    {
        return $this->photoCaption;
    }

    public function setPhotoCaption(?string $photoCaption): self
    {
        $this->photoCaption = $photoCaption;

        return $this;
    }

    public function getKeywords(): ?string
    {
        return $this->keywords;
    }

    public function setKeywords(?string $keywords): self
    {
        $this->keywords = $keywords;

        return $this;
    }

    public function getLastEdited(): ?string
    {
        return $this->lastEdited;
    }

    public function setLastEdited(string $lastEdited): self
    {
        $this->lastEdited = $lastEdited;

        return $this;
    }
}
