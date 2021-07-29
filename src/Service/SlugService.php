<?php

namespace App\Service;

use Symfony\Component\String\Slugger\SluggerInterface;

class SlugService {

	private $slugger;

	public function __construct(SluggerInterface $slugger)
	{
		$this->slugger = $slugger;
	}

	/**
	 * Convertis une chaine en slug
	 *
	 * @param String $slugUrl
	 * @return String
	 */
	public function slugConvert(string $slugUrl): string {

		return $this->slugger->slug($slugUrl)->lower();
	}
}