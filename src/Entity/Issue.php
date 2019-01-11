<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass="App\Repository\IssueRepository")
 */
class Issue
{
    /**
     * @ORM\Id()
     * @ORM\GeneratedValue()
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\Column(type="date")
     */
    private $issueDate;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $issueNumber;

    /**
     * @ORM\Column(type="integer")
     */
    private $pageCount;

    /**
    * @ORM\ManyToOne(targetEntity="Volume", inversedBy="issues")
    */
    private $volume;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private $archiveKey;

    /**
     * @ORM\Column(type="text", nullable=true)
     */
    private $archiveNotes;

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

    public function getVolume(): Volume
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
}
