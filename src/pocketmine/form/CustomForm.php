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

use pocketmine\form\element\CustomFormElement;
use pocketmine\Player;
use pocketmine\utils\Utils;

abstract class CustomForm extends Form{

	/** @var CustomFormElement[] */
	private $elements;

	/**
	 * @param string                 $title
	 * @param CustomFormElement[]    $elements
	 * @param null|FormSubmitHandler $submitHandler
	 * @param null|FormCloseHandler  $closeHandler
	 */
	public function __construct(string $title, array $elements, ?FormSubmitHandler $submitHandler = null, ?FormCloseHandler $closeHandler = null){
		assert(Utils::validateObjectArray($elements, CustomFormElement::class));
		parent::__construct($title, $submitHandler, $closeHandler);
		$this->elements = $elements;
	}

	/**
	 * @return string
	 */
	public function getType() : string{
		return Form::TYPE_CUSTOM_FORM;
	}

	/**
	 * @param int $index
	 *
	 * @return CustomFormElement|null
	 */
	public function getElement(int $index) : ?CustomFormElement{
		return $this->elements[$index] ?? null;
	}

	/**
	 * @return CustomFormElement[]
	 */
	public function getAllElements() : array{
		return $this->elements;
	}

	public function handleResponse(Player $player, $data) : ?Form{
		if($data === null){
			return $this->onClose($player);
		}

		if(is_array($data)){
			/** @var array $data */
			foreach($data as $index => $value){
				$this->elements[$index]->setValue($value);
			}

			return $this->onSubmit($player);
		}

		throw new \UnexpectedValueException("Expected array or NULL, got " . gettype($data));
	}

	final public function isCloseable() : bool{
		return true;
	}

	public function serializeFormData() : array{
		return [
			"content" => $this->elements
		];
	}
}
