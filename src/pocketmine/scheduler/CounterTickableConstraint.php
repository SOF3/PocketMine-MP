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

class CounterTickableConstraint implements TickableConstraint{
	/** @var int */
	private $frequency;

	/** @var int */
	private $counter;

	/**
	 * @param int $frequency the number of loops to be executed per loop
	 */
	public function __construct(int $frequency){
		$this->frequency = $frequency;
	}

	public function reset() : void{
		$this->counter = $this->frequency;
	}

	public function consume() : bool{
		return (--$this->counter) > 0;
	}
}
