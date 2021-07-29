<?php

namespace App\Service;

use Psr\Log\LoggerInterface;

class MessageGenerator {

	private $logger;

	private $isRandom;

	public function __construct(LoggerInterface $logger, bool $isRandom)
	{
		$this->logger = $logger;

		$this->isRandom = $isRandom;
	}

	private $messages = [
		'You did it! You updated the system! Amazing!',
		'That was one of the coolest updates I\'ve seen all day!',
		'Great work! Keep going!',
	];

	/**
	 * Get random message
	 */ 
	public function getRandomMessage()
	{
		if($this->isRandom) {
			$message = $this->messages[array_rand($this->messages)];
		} else {
			$message = 'Action effectuée avec succès !';
		}

			$this->logger->info('Random message', [
					'message' => $message,
			]);

			return $message;
	}
}