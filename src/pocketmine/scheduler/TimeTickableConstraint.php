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
	 * @param float $seconds The duration to execute in each tick (in seconds)
	 */
	public function __construct(float $seconds){
		$this->seconds = $seconds;
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
