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

namespace pocketmine\form;

use pocketmine\form\element\CustomFormElement;
use pocketmine\plugin\Plugin;

abstract class ServerSettingsAttachment{
	/** @var Plugin|null */
	private $owner;

	/** @var float */
	private $priority = 100.0;

	/** @var CustomFormElement[] */
	private $elements = [];

	/**
	 * @param null|Plugin         $owner
	 * @param float               $priority
	 * @param CustomFormElement[] $elements
	 */
	public function __construct(?Plugin $owner, float $priority, array $elements){
		$this->owner = $owner;
		$this->priority = $priority;
		$this->elements = $elements;
	}

	/**
	 * @return CustomFormElement[]
	 */
	public function getElements() : array{
		return $this->elements;
	}

	/**
	 * @param CustomFormElement[] $elements
	 */
	public function setElements(array $elements) : void{
		$this->elements = $elements;
	}

	/**
	 * Returns the priority of the attachment. The greater the priority, the upper it appears.
	 *
	 * @return float
	 */
	public function getPriority() : float{
		return $this->priority;
	}

	public function setPriority(float $priority) : void{
		$this->priority = $priority;
	}

	public function getOwner() : ?Plugin{
		return $this->owner;
	}
}
