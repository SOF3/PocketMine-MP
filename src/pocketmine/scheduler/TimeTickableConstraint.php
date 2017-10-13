<?php

/*
 *
 * PocketMine-MP
 *
 * Copyright (C) 2017 SOFe
 *
 * This program is free software: you can redistribute it and/or modify
 * it under the terms of the GNU Lesser General Public License as published by
 * the Free Software Foundation, either version 3 of the License, or
 * (at your option) any later version.
 *
 */

declare(strict_types=1);

namespace pocketmine\scheduler;

class TimeTickableConstraint implements TickableConstraint{
	/** @var float */
	private $seconds;

	/** @var float */
	private $tempTime;

	/**
	 * @param float $ticks The duration to execute in each tick, in ticks. Passing 0.3 implies that ideally 30% of a tick is allocated to executing this task. Passing a value greater than 1 may cause server overloading.
	 */
	public function __construct(float $ticks){
		$this->seconds = $ticks / 20;
	}

	public function reset() : void{
		$this->tempTime = microtime(true);
	}

	/**
	 * Reports one execution in a tick
	 *
	 * @return bool
	 */
	public function consume() : bool{
		return microtime(true) - $this->tempTime < $this->seconds;
	}
}
