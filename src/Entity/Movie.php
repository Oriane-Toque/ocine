<?php

namespace App\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
// on va appliquer la logique de mapping via l'annotation @ORM
// qui correspond à un dossier "Mapping" de Doctrine
use Doctrine\ORM\Mapping as ORM;
use \DateTime;

/**
 * Classe qui représente la table movie et ses enregistrements
 * 
 * @ORM\Entity(repositoryClass="App\Repository\MovieRepository")
 * @ORM\Table(name="movie", indexes={@ORM\Index(columns={"title"}, flags={"fulltext"})})
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
	 */
	private $id;

	/**
	 * Titre
	 * 
	 * @ORM\Column(type="string", length=211)
	 */
	private $title;

	/**
	 * @ORM\Column(type="datetime")
	 */
	private $releaseDate;

	/**
	 * @ORM\Column(type="integer")
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
	 */
	private $genres;

	/**
	 * @ORM\OneToMany(targetEntity=Casting::class, mappedBy="movie")
	 */
	private $castings;

	public function __construct()
	{
		$this->genres = new ArrayCollection();
		$this->castings = new ArrayCollection();
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
	public function setUpdatedAt(DateTime $updatedAt)
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
}
