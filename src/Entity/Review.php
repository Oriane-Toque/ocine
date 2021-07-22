<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * @ORM\Entity(repositoryClass=ReviewRepository::class)
 */
class Review
{
	/**
	 * @ORM\Id
	 * @ORM\GeneratedValue
	 * @ORM\Column(type="integer")
	 */
	private $id;

	/**
	 * @ORM\Column(type="string", length=50)
	 * 
	 * @Assert\NotBlank
	 */
	private $username;

	/**
	 * @ORM\Column(type="string", length=255)
	 * 
	 * @Assert\NotBlank
	 * @Assert\Email(message="Cette adresse {{value}} n'est pas une adresse valide")
	 */
	private $email;

	/**
	 * @ORM\Column(type="text")
	 * 
	 * @Assert\NotBlank
	 * @Assert\Length(min=100, minMessage="La longueur min d'une critique est de {{ limit }} caractères",)
	 */
	private $content;

	/**
	 * @ORM\Column(type="smallint")
	 * 
	 * @Assert\NotBlank
	 * @Assert\Range(min=1, max=5, notInRangeMessage="This value should be between {{ min }} and {{ max }}")
	 */
	private $rating;

	/**
	 * @ORM\Column(type="json")
	 *  
	 * @Assert\NotBlank
   * @Assert\Choice({"smile", "cry", "think", "sleep", "dream"}, multiple=true)
	 */
	private $reactions = [];

	/**
	 * @ORM\Column(type="datetime_immutable")
	 */
	private $watchedAt;

    /**
     * @ORM\ManyToOne(targetEntity=Movie::class, inversedBy="reviews")
     * @ORM\JoinColumn(nullable=false)
     */
    private $movie;

	public function getId(): ?int
                  	{
                  		return $this->id;
                  	}

	public function getUsername(): ?string
                  	{
                  		return $this->username;
                  	}

	public function setUsername(string $username): self
                  	{
                  		$this->username = $username;
                  
                  		return $this;
                  	}

	public function getEmail(): ?string
                  	{
                  		return $this->email;
                  	}

	public function setEmail(string $email): self
                  	{
                  		$this->email = $email;
                  
                  		return $this;
                  	}

	public function getContent(): ?string
                  	{
                  		return $this->content;
                  	}

	public function setContent(string $content): self
                  	{
                  		$this->content = $content;
                  
                  		return $this;
                  	}

	public function getRating(): ?int
                  	{
                  		return $this->rating;
                  	}

	public function setRating(int $rating): self
                  	{
                  		$this->rating = $rating;
                  
                  		return $this;
                  	}

	public function getReactions(): ?array
                  	{
                  		return $this->reactions;
                  	}

	public function setReactions(array $reactions): self
                  	{
                  		$this->reactions = $reactions;
                  
                  		return $this;
                  	}

	public function getWatchedAt(): ?\DateTimeImmutable
                  	{
                  		return $this->watchedAt;
                  	}

	public function setWatchedAt(\DateTimeImmutable $watchedAt): self
                  	{
                  		$this->watchedAt = $watchedAt;
                  
                  		return $this;
                  	}

    public function getMovie(): ?Movie
    {
        return $this->movie;
    }

    public function setMovie(?Movie $movie): self
    {
        $this->movie = $movie;

        return $this;
    }
}
