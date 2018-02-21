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

use pocketmine\form\attachment\FormAttachment;
use pocketmine\form\attachment\MenuFormAttachment;
use pocketmine\form\attachment\ShiftSet;

class MenuForm extends FormLayout{
	/** @var string */
	protected $title;
	/** @var string */
	protected $content;
	/** @var MenuOption[] */
	protected $options;

	/** @var int|null */
	protected $selectedIndex;

	public function __construct(string $title, string $content){
		$this->title = $title;
		$this->content = $content;
	}

	public function add(MenuOption ...$option) : MenuOption{
		$this->validateMutable();

		foreach($option as $o){
			$this->options[] = $o;
		}

		return $option[count($option) - 1];
	}

	public function remove(MenuOption ...$option) : void{
		$this->validateMutable();

		foreach($option as $i => $o){
			$index = array_search($o, $this->options, true);
			if($index === false){
				throw new \InvalidArgumentException("Argument " . ($i + 1) . " is not in this MenuForm");
			}
			unset($this->options[$i]);
		}
		$this->options = array_values($this->options); // repack to linear array after the transaction has completed
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

	/**
	 * @return string
	 */
	public function getContent() : string{
		return $this->content;
	}

	/**
	 * @param string $content
	 */
	public function setContent(string $content) : void{
		$this->content = $content;
	}

	public function getSelectedIndex() : int{
		if(!isset($this->selectedIndex)){
			throw new \InvalidStateException("Form values can only be read while \$onSubmit is called");
		}
		return $this->selectedIndex;
	}

	public function getSelectedOption() : MenuOption{
		if(!isset($this->selectedIndex)){
			throw new \InvalidStateException("Form values can only be read while \$onSubmit is called");
		}
		return $this->options[$this->selectedIndex];
	}

	public final function isCloseable() : bool{
		return true;
	}

	public function acceptValue($data) : bool{
		if($data === null){
			return false;
		}

		if(!is_int($data)){
			throw new \UnexpectedValueException("Expected int or NULL, got " . gettype($data));
		}

		$this->selectedIndex = $data;
		return true;
	}

	public function resetValue() : void{
		unset($this->selectedIndex);
	}

	public function jsonSerialize(array $attachments) : array{
		$options = $this->options;

		ShiftSet::applyPatches($options, $attachments);

		return [
			"type" => "form",
			"title" => $this->title,
			"content" => $this->content,
			"buttons" => $options
		];
	}

	public function createAttachment() : FormAttachment{
		return new MenuFormAttachment();
	}
}
