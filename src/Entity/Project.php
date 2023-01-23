<?php

namespace App\Entity;

use App\Repository\ProjectRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass=ProjectRepository::class)
 * @ORM\Table(name="test_Project")

 */
class Project
{
    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private $title;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private $description;

    /**
     * @var string
     *
     * @ORM\Column(name="picture", type="string", length=255, nullable=true)
     */
    private $picture;
    /**
     * @var string
     *
     * @ORM\Column(name="filename", type="string", length=255, nullable=true)
     */
    private $filename;
    /**
     * @var string
     *
     * @ORM\Column(name="number_task", type="integer", nullable=true)
     */
    private $number_task;
    /**
     * @var string
     *
     * @ORM\Column(name="status", type="string", length=255, nullable=true)
     */
    private $status;

    /**
     * @var \DateTime
     *
     * @ORM\Column(name="created_at", type="date", length=255, nullable=true)
     */
    private $createdat;
    /**
     * @var string
     *
     * @ORM\Column(name="test", type="integer", nullable=true)
     */
    private $test;





    public function getId(): ?int
    {
        return $this->id;
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

    public function getDescription(): ?string
    {
        return $this->description;
    }

    public function setDescription(?string $description): self
    {
        $this->description = $description;

        return $this;
    }

    /**
     * @return mixed
     */
    public function getPicture(): mixed
    {
        return $this->picture;
    }

    /**
     * @param mixed $picture
     */
    public function setPicture(mixed $picture)
    {
        $this->picture = $picture;
    }

    /**
     * @return mixed
     */
    public function getFilename(): mixed
    {
        return $this->filename;
    }

    /**
     * @param mixed $filename
     */
    public function setFilename(mixed $filename): void
    {
        $this->filename = $filename;
    }

    /**
     * @return mixed
     */
    public function getNumberTask(): mixed
    {
        return $this->number_task;
    }

    /**
     * @param mixed $number_task
     */
    public function setNumberTask(string $number_task): void
    {
        $this->number_task = $number_task;
    }

    /**
     * @return mixed
     */
    public function getStatus(): mixed
    {
        return $this->status;
    }

    /**
     * @param mixed $status
     */
    public function setStatus(mixed $status): void
    {
        $this->status = $status;
    }

    /**
     * @return mixed
     */
    public function getTest(): mixed
    {
        return $this->test;
    }

    /**
     * @param mixed $test
     */
    public function setTest(mixed $test): void
    {
        $this->test = $test;
    }







    /**
     * @return \DateTime
     */
    public function getCreatedat(): \DateTime
    {
        return $this->createdat;
    }

    /**
     * @param \DateTime $createdat
     */
    public function setCreatedat(\DateTime $createdat): void
    {
        $this->createdat = $createdat;
    }


    public function getCoverPictureFullPath() {
        if ($this->picture != null) {
            return 'uploads/project/' . $this->picture;
        } else {
            return '';
        }
    }
    public function getPdfFullPath() {
        if ($this->filename != null) {
            return 'uploads/project/' . $this->filename;
        } else {
            return '';
        }
    }

}
