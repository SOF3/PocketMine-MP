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

namespace pocketmine\event\player\form;

use pocketmine\form\attachment\FormAttachment;
use pocketmine\form\FormHandler;
use pocketmine\form\layout\FormLayout;

/**
 * Called when a form is queued for sending.
 */
class PlayerFormSendEvent extends PlayerFormEvent{
	public function __construct(FormHandler $handler){
		$this->player = $handler->getPlayer();
		$this->form = $handler;
	}

	public function getLayout() : FormLayout{
		return $this->form->getLayout();
	}

	public function addAttachment() : FormAttachment{
		return $this->form->createAttachment();
	}
}
