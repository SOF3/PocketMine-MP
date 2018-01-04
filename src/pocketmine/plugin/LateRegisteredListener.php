<?php

/*
 *
 *  ____            _        _   __  __ _                  __  __ ____
 * |  _ \ ___   ___| | _____| |_|  \/  (_)_ __   ___      |  \/  |  _ \
 * | |_) / _ \ / __| |/ / _ \ __| |\/| | | '_ \ / _ \_____| |\/| | |_) |
 * |  __/ (_) | (__|   <  __/ |_| |  | | | | | |  __/_____| |  | |  __/
 * |_|   \___/ \___|_|\_\___|\__|_|  |_|_|_| |_|\___|     |_|  |_|_|
 *
 * This program is free software: you can redistribute it and/or modify
 * it under the terms of the GNU Lesser General Public License as published by
 * the Free Software Foundation, either version 3 of the License, or
 * (at your option) any later version.
 *
 * @author PocketMine Team
 * @link http://www.pocketmine.net/
 *
 *
*/

declare(strict_types=1);

namespace pocketmine\plugin;

use pocketmine\event\Listener;
use pocketmine\event\TimingsHandler;

class LateRegisteredListener extends RegisteredListener{
	/** @var float */
	private $timeout;

	public function __construct(Listener $listener, EventExecutor $executor, int $priority, Plugin $plugin, bool $ignoreCancelled, TimingsHandler $timings, float $timeout){
		parent::__construct($listener, $executor, $priority, $plugin, $ignoreCancelled, $timings);
		$this->timeout = $timeout;
	}

	/**
	 * @return float
	 */
	public function getTimeout() : float{
		return $this->timeout;
	}
}
