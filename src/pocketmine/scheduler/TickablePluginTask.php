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

use pocketmine\plugin\Plugin;

class TickablePluginTask extends PluginTask{
	use TickableTaskTrait;

	public function __construct(Plugin $owner, \Iterator $generator, TickableConstraint $constraint){
		parent::__construct($owner);
		$this->init($generator, $constraint);
	}
}
