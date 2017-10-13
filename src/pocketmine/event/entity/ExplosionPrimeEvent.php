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

namespace pocketmine\event\entity;

use pocketmine\entity\Entity;
use pocketmine\event\Cancellable;
use pocketmine\scheduler\TickableConstraint;

/**
 * Called when a entity decides to explode
 */
class ExplosionPrimeEvent extends EntityEvent implements Cancellable{
	public static $handlerList = null;

	/** @var float */
	protected $force;
	/** @var bool */
	private $blockBreaking;
	/** @var TickableConstraint|null */
	private $tickableConstraint;
	/** @var int */
	private $tickingPeriod;

	/**
	 * @param Entity                  $entity
	 * @param float                   $force
	 * @param null|TickableConstraint $tickableConstraint
	 * @param int                     $tickingPeriod
	 */
	public function __construct(Entity $entity, float $force, ?TickableConstraint $tickableConstraint = null, int $tickingPeriod = 1){
		$this->entity = $entity;
		$this->force = $force;
		$this->blockBreaking = true;
		$this->tickableConstraint = $tickableConstraint;
		$this->tickingPeriod = $tickingPeriod;
	}

	/**
	 * @return float
	 */
	public function getForce() : float{
		return $this->force;
	}

	public function setForce(float $force){
		$this->force = $force;
	}

	/**
	 * @return bool
	 */
	public function isBlockBreaking() : bool{
		return $this->blockBreaking;
	}

	/**
	 * @param bool $affectsBlocks
	 */
	public function setBlockBreaking(bool $affectsBlocks){
		$this->blockBreaking = $affectsBlocks;
	}

	public function getTickableConstraint() : ?TickableConstraint{
		return $this->tickableConstraint;
	}

	public function setTickableConstraint(?TickableConstraint $tickableConstraint){
		$this->tickableConstraint = $tickableConstraint;
	}

	public function getTickingPeriod() : int{
		return $this->tickingPeriod;
	}

	public function setTickingPeriod(int $tickingPeriod){
		$this->tickingPeriod = $tickingPeriod;
	}
}
