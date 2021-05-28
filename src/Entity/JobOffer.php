<?php

namespace App\Entity;

use App\Repository\JobOfferRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * @ORM\Entity(repositoryClass=JobOfferRepository::class)
 */
class JobOffer
{
    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\Column(type="string", length=150)
     * @Assert\NotNull(message="Veuillez saisir un intitulé d'offre valide")
     * @Assert\NotBlank(message="Veuillez saisir un intitulé d'offre valide")
     */
    private $title;

    /**
     * @ORM\Column(type="text")
     * @Assert\NotNull(message="Veuillez saisir une description d'offre valide")
     * @Assert\NotBlank(message="Veuillez saisir une description d'offre valide")
     */
    private $description;

    /**
     * @ORM\Column(type="string", length=50)
     */
    private $reference;

    /**
     * @ORM\Column(type="date")
     */
    private $date_start;

    /**
     * @ORM\Column(type="float", nullable=true)
     */
    private $salary;

    /**
     * @ORM\Column(type="string", length=150)
     */
    private $city;

    /**
     * @ORM\Column(type="datetime")
     */
    private $created_at;

    /**
     * @ORM\Column(type="datetime", nullable=true)
     */
    private $updated_at;

    /**
     * @ORM\ManyToOne(targetEntity=JobType::class, inversedBy="job_offer")
     * @ORM\JoinColumn(nullable=false)
     */
    private $jobType;

    /**
     * @ORM\ManyToOne(targetEntity=Company::class, inversedBy="job_offer")
     * @ORM\JoinColumn(nullable=false)
     */
    private $company;

    /**
     * @ORM\OneToMany(targetEntity=JobRequirement::class, mappedBy="jobOffer")
     */
    private $jobRequirements;

    public function __construct()
    {
        $this->jobRequirements = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getTitle(): ?string
    {
        return $this->title;
    }

    public function setTitle(string $title): self
    {
        $this->title = $title;

        return $this;
    }

    public function getDescription(): ?string
    {
        return $this->description;
    }

    public function setDescription(string $description): self
    {
        $this->description = $description;

        return $this;
    }

    public function getReference(): ?string
    {
        return $this->reference;
    }

    public function setReference(string $reference): self
    {
        $this->reference = $reference;

        return $this;
    }

    public function getDateStart(): ?\DateTimeInterface
    {
        return $this->date_start;
    }

    public function setDateStart(\DateTimeInterface $date_start): self
    {
        $this->date_start = $date_start;

        return $this;
    }

    public function getSalary(): ?float
    {
        return $this->salary;
    }

    public function setSalary(?float $salary): self
    {
        $this->salary = $salary;

        return $this;
    }

    public function getCity(): ?string
    {
        return $this->city;
    }

    public function setCity(string $city): self
    {
        $this->city = $city;

        return $this;
    }

    public function getCreatedAt(): ?\DateTimeInterface
    {
        return $this->created_at;
    }

    public function setCreatedAt(\DateTimeInterface $created_at): self
    {
        $this->created_at = $created_at;

        return $this;
    }

    public function getUpdatedAt(): ?\DateTimeInterface
    {
        return $this->updated_at;
    }

    public function setUpdatedAt(?\DateTimeInterface $updated_at): self
    {
        $this->updated_at = $updated_at;

        return $this;
    }

    public function getJobType(): ?JobType
    {
        return $this->jobType;
    }

    public function setJobType(?JobType $jobType): self
    {
        $this->jobType = $jobType;

        return $this;
    }

    public function getCompany(): ?Company
    {
        return $this->company;
    }

    public function setCompany(?Company $company): self
    {
        $this->company = $company;

        return $this;
    }

    /**
     * @return Collection|JobRequirement[]
     */
    public function getJobRequirements(): Collection
    {
        return $this->jobRequirements;
    }

    public function addJobRequirement(JobRequirement $jobRequirement): self
    {
        if (!$this->jobRequirements->contains($jobRequirement)) {
            $this->jobRequirements[] = $jobRequirement;
            $jobRequirement->setJobOffer($this);
        }

        return $this;
    }

    public function removeJobRequirement(JobRequirement $jobRequirement): self
    {
        if ($this->jobRequirements->removeElement($jobRequirement)) {
            // set the owning side to null (unless already changed)
            if ($jobRequirement->getJobOffer() === $this) {
                $jobRequirement->setJobOffer(null);
            }
        }

        return $this;
    }
}
