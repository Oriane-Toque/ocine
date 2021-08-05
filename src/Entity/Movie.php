<?php

namespace App\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
// on va appliquer la logique de mapping via l'annotation @ORM
// qui correspond à un dossier "Mapping" de Doctrine
use Doctrine\ORM\Mapping as ORM;
use \DateTime;
use Symfony\Component\Validator\Constraints as Assert;
use Symfony\Component\Serializer\Annotation\Groups;

use Symfony\Bridge\Doctrine\Validator\Constraints\UniqueEntity;

/**
 * Classe qui représente la table movie et ses enregistrements
 * 
 * @ORM\Entity(repositoryClass="App\Repository\MovieRepository")
 * @ORM\HasLifecycleCallbacks()
 * @UniqueEntity("title")
 */
class Movie
{

	/**
	 * Clé primaire
	 * Auto-increment
	 * type INT
	 * 
	 * @ORM\Id
	 * @ORM\GeneratedValue
	 * @ORM\Column(type="integer")
	 * @Groups("movies_get")
	 */
	private $id;

	/**
	 * Titre
	 * 
	 * @ORM\Column(type="string", length=211, unique=true)
	 * @Groups("movies_get")
	 * 
	 * @Assert\NotBlank
	 * @Assert\Length(max=211)
	 */
	private $title;

	/**
	 * Slug
	 * 
	 * @ORM\Column(type="string", length=255, unique=true)
	 * @Groups("movies_get")
	 */
	private $slug;

	/**
	 * @ORM\Column(type="datetime")
	 * @Groups("movies_get")
	 * 
	 * @Assert\NotBlank
	 */
	private $releaseDate;

	/**
	 * @ORM\Column(type="smallint")
	 * @Groups("movies_get")
	 * 
	 * @Assert\NotBlank
	 * @Assert\LessThanOrEqual(1440)
	 * @Assert\Positive
	 */
	private $duration;

	/**
	 * @ORM\Column(type="datetime")
	 *
	 */
	private $createdAt;

	/**
	 * @ORM\Column(type="datetime", nullable=true)
	 * 
	 */
	private $updatedAt;

	/**
	 * @ORM\ManyToMany(targetEntity=Genre::class, inversedBy="movies")
	 * 
	 * @Groups("movies_get")
	 * 
	 * @Assert\Count(min=1)
	 */
	private $genres;

	/**
	 * @ORM\OneToMany(targetEntity=Casting::class, mappedBy="movie", cascade={"remove"})
	 * @Groups("movies_get")
	 */
	private $castings;

	/**
	 * @ORM\OneToMany(targetEntity=Review::class, mappedBy="movie", orphanRemoval=true)
	 */
	private $reviews;

	/**
	 * @ORM\Column(type="smallint", nullable=true)
	 * 
	 * @Assert\NotBlank
	 * @Assert\Type("integer") 
	 * @Assert\Range(min=1, max=5) 
	 */
	private $rating;

	/**
	 * @ORM\Column(type="string", length=255, nullable=true)
	 * @Groups("movies_get")
	 */
	private $poster;

	/**
	 * Les valeurs par défaut utiles
	 */
	public function __construct()
	{
		$this->createdAt = new DateTime();
		$this->releaseDate = new DateTime();
		$this->genres = new ArrayCollection();
		$this->castings = new ArrayCollection();
		$this->reviews = new ArrayCollection();
		$this->teams = new ArrayCollection();
	}


	/**
	 * Set auto the value of updatedAt
	 * 
	 * @ORM\PreUpdate
	 * 
	 * @return  self
	 */
	public function setUpdatedAtToNow()
	{
		$this->updatedAt = new DateTime();

		return $this;
	}

	/**
	 * Get titre
	 */
	public function getTitle()
	{
		return $this->title;
	}

	/**
	 * Set titre
	 *
	 * @param string $title, titre du film
	 * @return  self
	 */
	public function setTitle(string $title)
	{
		$this->title = $title;

		return $this;
	}

	/**
	 * Get clé primaire
	 */
	public function getId()
	{
		return $this->id;
	}


	/**
	 * Get the value of createdAt
	 */
	public function getCreatedAt()
	{
		return $this->createdAt;
	}

	/**
	 * Set the value of createdAt
	 * 
	 * @return  self
	 */
	public function setCreatedAt(DateTime $createdAt)
	{
		$this->createdAt = $createdAt;

		return $this;
	}

	/**
	 * Get the value of updatedAt
	 */
	public function getUpdatedAt()
	{
		return $this->updatedAt;
	}

	/**
	 * Set the value of updatedAt
	 *
	 * @return  self
	 */
	public function setUpdatedAt($updatedAt)
	{
		$this->updatedAt = $updatedAt;

		return $this;
	}

	/**
	 * @return Collection|Genre[]
	 */
	public function getGenres(): Collection
	{
		return $this->genres;
	}

	public function addGenre(Genre $genre): self
	{
		if (!$this->genres->contains($genre)) {
			$this->genres[] = $genre;
		}

		return $this;
	}

	public function removeGenre(Genre $genre): self
	{
		$this->genres->removeElement($genre);

		return $this;
	}

	/**
	 * @return Collection|Casting[]
	 */
	public function getCastings(): Collection
	{
		return $this->castings;
	}

	public function addCasting(Casting $casting): self
	{
		if (!$this->castings->contains($casting)) {
			$this->castings[] = $casting;
			$casting->setMovie($this);
		}

		return $this;
	}

	public function removeCasting(Casting $casting): self
	{
		if ($this->castings->removeElement($casting)) {
			// set the owning side to null (unless already changed)
			if ($casting->getMovie() === $this) {
				$casting->setMovie(null);
			}
		}

		return $this;
	}

	public function getReleaseDate(): ?\DateTimeInterface
	{
		return $this->releaseDate;
	}

	public function setReleaseDate(\DateTimeInterface $releaseDate): self
	{
		$this->releaseDate = $releaseDate;

		return $this;
	}

	public function getDuration(): ?int
	{
		return $this->duration;
	}

	public function setDuration(int $duration): self
	{
		$this->duration = $duration;

		return $this;
	}

	/**
	 * @return Collection|Review[]
	 */
	public function getReviews(): Collection
	{
		return $this->reviews;
	}

	public function addReview(Review $review): self
	{
		if (!$this->reviews->contains($review)) {
			$this->reviews[] = $review;
			$review->setMovie($this);
		}

		return $this;
	}

	public function removeReview(Review $review): self
	{
		if ($this->reviews->removeElement($review)) {
			// set the owning side to null (unless already changed)
			if ($review->getMovie() === $this) {
				$review->setMovie(null);
			}
		}

		return $this;
	}

	public function getRating(): ?int
	{
		return $this->rating;
	}

	public function setRating(?int $rating): self
	{
		$this->rating = $rating;

		return $this;
	}

	public function getPoster(): ?string
	{
		return $this->poster;
	}

	public function setPoster(?string $poster): self
	{
		$this->poster = $poster;

		return $this;
	}

	/**
	 * Get slug
	 */
	public function getSlug()
	{
		return $this->slug;
	}

	/**
	 * Set slug
	 *
	 * @return  self
	 */
	public function setSlug($slug)
	{
		$this->slug = $slug;

		return $this;
	}
}
