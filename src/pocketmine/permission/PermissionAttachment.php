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

class PermissionAttachment{
	/** @var Permission */
	private $permission;
	/** @var Plugin */
	private $plugin;
	/** @var float */
	private $priority;
	/** @var bool */
	private $inherit;
	/** @var bool|null */
	private $ternary;

	/** @var PermissionAttachment|null */
	public $higher = null;
	/** @var PermissionAttachment|null */
	public $lower = null;
	/** @var bool|null */
	public $result;

	public function __construct(Permission $permission, Plugin $plugin, float $priority, bool $inherit, ?bool $ternary){
		$this->permission = $permission;
		$this->plugin = $plugin;
		$this->priority = $priority;
		$this->inherit = $inherit;
		$this->ternary = $ternary;
	}

	public function getPermission() : Permission{
		return $this->permission;
	}

	public function getPlugin() : Plugin{
		return $this->plugin;
	}

	public function getPriority() : float{
		return $this->priority;
	}

	public function isInherit() : bool{
		return $this->inherit;
	}

	public function getTernary() : ?bool{
		return $this->ternary;
	}

	public function setRule(bool $inherit, ?bool $ternary) : void{
		if($inherit === $this->inherit && $ternary === $this->ternary){
			return;
		}
		$this->inherit = $inherit;
		$this->ternary = $ternary;
		$this->recalculateValue();
	}

	public function recalculateValue() : void{
		if($this->inherit){
			$result = $this->higher !== null ? ($this->higher->result ?? $this->ternary) : $this->ternary;
		}else{
			$result = $this->ternary;
		}

		if($result !== $this->result){
			$this->result = $result;
			if($this->lower !== null){
				$this->lower->recalculateValue();
			}
		}
	}
}
