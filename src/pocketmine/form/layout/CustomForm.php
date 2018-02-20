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

namespace pocketmine\form\layout;

use pocketmine\form\attachment\CustomFormAttachment;
use pocketmine\form\attachment\FormAttachment;
use pocketmine\form\element\CustomFormElement;

class CustomForm extends FormLayout{
	/** @var string */
	protected $title;
	/** @var CustomFormElement[] */
	protected $elements = [];

	public function __construct(string $title){
		$this->title = $title;
	}

	public function add(CustomFormElement ...$element) : CustomFormElement{
		foreach($element as $e){
			$this->elements[] = $e;
		}
		return $element[count($element) - 1];
	}

	public function removeAllElements(){
		$this->elements = [];
	}

	/**
	 * @return string
	 */
	public function getTitle() : string{
		return $this->title;
	}

	/**
	 * @param string $title
	 */
	public function setTitle(string $title) : void{
		$this->title = $title;
	}

	public final function isCloseable() : bool{
		return true;
	}

	public function acceptValue($data) : bool{
		if($data === null){
			return false;
		}
		if(!is_array($data)){
			throw new \UnexpectedValueException("Expected array or NULL, got " . gettype($data));
		}
		for($element = reset($this->elements), $value = reset($data);
		    $element instanceof CustomFormElement && $value !== false;
		    $element = next($this->elements), $value = next($data)){
			$element->setValue($value);
		}

		return true;
	}

	public function resetValue() : void{
		foreach($this->elements as $element){
			$element->resetValue();
		}
	}

	public function jsonSerialize(array $attachments) : array{
		$elements = $this->elements;
		// TODO apply attachments
		return [
			"type" => "custom_form",
			"title" => $this->title,
			"content" => $elements
		];
	}

	public function isAttachmentApplicable(FormAttachment $attachment) : bool{
		return $attachment instanceof CustomFormAttachment;
	}
}
