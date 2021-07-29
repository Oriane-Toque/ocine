<?php

namespace App\Service;

use Symfony\Component\String\Slugger\SluggerInterface;

class SlugService {

	private $slugger;

	private $toLower;

	public function __construct(SluggerInterface $slugger, bool $toLower)
	{
		$this->slugger = $slugger;
		$this->toLower = $toLower;
	}

	/**
	 * Convertis une chaine en slug
	 *
	 * @param String $slugUrl
	 * @return String
	 */
	public function slugConvert(string $slugUrl): string {

		if($this->toLower) {
			return $this->slugger->slug($slugUrl)->lower();
		} else {
			return $this->slugger->slug($slugUrl);
		}
	}
}