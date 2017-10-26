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
use pocketmine\network\mcpe\protocol\ServerSettingsResponsePacket;
use pocketmine\Player;

/**
 * Represents a custom form which can be shown in the Settings menu on the client. This is exactly the same as a regular
 * CustomForm, except that this type can also have an icon which can be shown on the settings section button.
 */
class ServerSettingsForm implements \JsonSerializable{
	/** @var string */
	private $title;

	/**
	 * @var FormIcon|null
	 */
	private $icon = null;

	/** @var ServerSettingsAttachment[] */
	private $attachments = [];

	/** @var CustomFormElement[] */
	private $elementIndex = [];

	public function __construct(string $title){
		$this->title = $title;
	}

	public function getTitle() : string{
		return $this->title;
	}

	public function setTitle(string $title) : void{
		$this->title = $title;
	}

	public function addAttachment(ServerSettingsAttachment $attachment){
		$this->attachments[spl_object_hash($attachment)] = $attachment;
	}

	public function getAttachments() : array{
		return $this->attachments;
	}

	public function removeAttachment(ServerSettingsAttachment $attachment){
		$key = spl_object_hash($attachment);
		if(!isset($this->attachments[$key])){
			throw new \InvalidArgumentException("The provided ServerSettingsAttachment has not been added to this form");
		}
		unset($this->attachments[$key]);
	}

	public function hasIcon() : bool{
		return $this->icon !== null;
	}

	public function getIcon() : ?FormIcon{
		return $this->icon;
	}

	public function setIcon(?FormIcon $icon) : void{
		$this->icon = $icon;
	}

	public function jsonSerialize() : array{
		usort($this->attachments, function(ServerSettingsAttachment $a, ServerSettingsAttachment $b){
			return $b->getPriority() <=> $a->getPriority(); // note that this is ($b <=> $a) not ($a <=> $b)
		});
		$content = [];
		foreach($this->attachments as $attachment){
			foreach($attachment->getElements() as $element){
				$content[] = $element;
			}
		}

		$data = [
			"type" => Form::TYPE_CUSTOM_FORM,
			"title" => $this->title,
			"content" => $content
		];

		if($this->hasIcon()){
			$data["icon"] = $this->icon;
		}

		return $data;
	}

	public function onSubmit($data) : void{
		if(is_array($data)){

		}
	}
}
