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

namespace pocketmine\permission;

use pocketmine\plugin\Plugin;

class RegisteredGroupPermissionPredicate{
	/** @var Plugin */
	private $plugin;
	/** @var GroupPermissionPredicate */
	private $predicate;
	/** @var float */
	private $priority;

	public function __construct(Plugin $plugin, GroupPermissionPredicate $predicate, float $priority){
		$this->plugin = $plugin;
		$this->predicate = $predicate;
		$this->priority = $priority;
	}

	public function getPlugin() : Plugin{
		return $this->plugin;
	}

	public function getPredicate() : GroupPermissionPredicate{
		return $this->predicate;
	}

	public function getPriority() : float{
		return $this->priority;
	}
}
