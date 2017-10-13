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

use pocketmine\Server;

trait TickableTaskTrait{
	/** @var \Iterator */
	private $generator;
	/** @var TickableConstraint */
	private $constraint;

	protected function init(\Iterator $generator, TickableConstraint $constraint) : void{
		if(!($this instanceof Task)){
			throw new \RuntimeException("TickableTaskTrait can only be used in Task subclasses");
		}

		$this->generator = $generator;
		$this->constraint = $constraint;
	}

	public function onRun(int $ticks) : void{
		$this->constraint->reset();
		while($this->generator->valid()){
			$this->generator->next();
			if($this->constraint->consume()){
				break;
			}
		}
		if(!$this->generator->valid()){
			Server::getInstance()->getScheduler()->cancelTask($this->getTaskId());
		}
	}
}
