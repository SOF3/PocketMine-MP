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

namespace pocketmine\event\player;

use pocketmine\form\ServerSettingsForm;
use pocketmine\Player;

class ServerSettingsPopulationEvent extends PlayerEvent{
	public static $handlerList = null;

	/** @var ServerSettingsForm */
	private $form;

	public function __construct(Player $player, ServerSettingsForm $form){
		$this->form = $form;
	}

	public function getForm() : ServerSettingsForm{
		return $this->form;
	}
}
