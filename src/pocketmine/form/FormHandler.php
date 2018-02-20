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

namespace pocketmine\form;

use pocketmine\form\layout\FormLayout;
use pocketmine\Player;
use pocketmine\utils\Utils;

final class FormHandler{
	public static $nextFormId = 1;

	/** @var Player */
	private $player;
	/** @var FormLayout */
	private $layout;
	/** @var int */
	private $formId;
	/** @var callable */
	private $onSubmit, $onClose;

	/** @var float */
	private $sentAt = 0.0;
	private $returned = false;

	public function __construct(Player $player, FormLayout $layout, callable $onSubmit, ?callable $onClose = null){
		$this->player = $player;
		$this->layout = $layout;
		$this->formId = self::$nextFormId++;
		assert($this->acceptsCorrectLayout($onSubmit), get_class($this->layout) . " is not assignable to \$onSubmit");
		$this->onSubmit = $onSubmit;
		if($onClose !== null){
			assert($this->layout->isCloseable(), "\$onClose is not applicable for a non-closeable form layout");
			assert(Utils::reflectCallable($onClose)->getNumberOfRequiredParameters() === 0, "\$onClose must not accept required parameters");
			$this->onClose = $onClose;
		}
	}

	private function acceptsCorrectLayout(callable $callable) : bool{
		$ref = Utils::reflectCallable($callable);
		$params = $ref->getParameters();
		if(isset($params[0])){
			$type = $params[0]->getType();
			if($type !== null && !is_a($this->layout, $type->getName())){
				return false;
			}
		}
		return true;
	}

	public function handleResponse($response) : bool{
		if($this->returned){
			throw new \InvalidStateException("The FormHandler has already been returned");
		}

		// TODO process attachments by FILO order
		$submitted = $this->layout->acceptValue($response);
		// TODO fire event, allow resend without setting $this->returned to true, return true
		$this->returned = true;
		if($submitted){
			($this->onSubmit)($this->layout);
			$this->layout->resetValue();
			// TODO reset attachments too
		}else{
			if($this->onClose !== null){
				($this->onClose)();
			}
		}
		return false;
	}

	public function markSent() : void{
		$this->sentAt = microtime(true);
	}

	public function isSent() : bool{
		return $this->sentAt > 0;
	}

	public function isReturned() : bool{
		return $this->returned;
	}

	/**
	 * @return Player
	 */
	public function getPlayer() : Player{
		return $this->player;
	}

	/**
	 * @return FormLayout
	 */
	public function getLayout() : FormLayout{
		return $this->layout;
	}

	/**
	 * @return int
	 */
	public function getFormId() : int{
		return $this->formId;
	}
}
