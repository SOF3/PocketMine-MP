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

class MenuOption implements \JsonSerializable{
	/** @var string */
	private $text;
	/** @var FormIcon|null */
	private $image;

	public function __construct(string $text, ?FormIcon $image = null){
		$this->text = $text;
		$this->image = $image;
	}

	public function getText() : string{
		return $this->text;
	}

	public function getImage() : ?FormIcon{
		return $this->image;
	}

	public function setText(string $text) : void{
		$this->text = $text;
	}

	public function setImage(?FormIcon $image) : void{
		$this->image = $image;
	}

	public function jsonSerialize(){
		return $this->image !== null ? [
			"text" => $this->text,
			"image" => $this->image,
		] : [
			"text" => $this->text
		];
	}
}
