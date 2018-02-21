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

class ModalForm extends FormLayout{
	/** @var string */
	private $title;
	/** @var string */
	private $content;
	/** @var string */
	private $yesButtonText;
	/** @var string */
	private $noButtonText;

	/** @var bool|null */
	private $yesButtonClicked;

	public function __construct(string $title, string $content, string $yesButtonText = "gui.yes", string $noButtonText = "gui.no"){
		$this->title = $title;
		$this->content = $content;
		$this->yesButtonText = $yesButtonText;
		$this->noButtonText = $noButtonText;
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

	/**
	 * @return string
	 */
	public function getYesButtonText() : string{
		return $this->yesButtonText;
	}

	/**
	 * @param string $yesButtonText
	 */
	public function setYesButtonText(string $yesButtonText) : void{
		$this->yesButtonText = $yesButtonText;
	}

	/**
	 * @return string
	 */
	public function getNoButtonText() : string{
		return $this->noButtonText;
	}

	/**
	 * @param string $noButtonText
	 */
	public function setNoButtonText(string $noButtonText) : void{
		$this->noButtonText = $noButtonText;
	}

	public function isCloseable() : bool{
		return false;
	}

	/**
	 * Sets this form's values according to the response
	 * @param mixed $data
	 * @return bool whether the values have been set successfully. false indicates that the client did not submit the form correctly.
	 */
	public function acceptValue($data) : bool{
		if(!is_bool($data)){
			throw new \UnexpectedValueException("Expected bool, got " . gettype($data));
		}

		$this->yesButtonClicked = $data;
		return true;
	}

	public function isYesButtonClicked() : bool{
		if(!isset($this->yesButtonClicked)){
			throw new \InvalidStateException("Form values can only be read while \$onSubmit is called");
		}

		return $this->yesButtonClicked;
	}

	public function isNoButtonClicked() : bool{
		if(!isset($this->yesButtonClicked)){
			throw new \InvalidStateException("Form values can only be read while \$onSubmit is called");
		}

		return !$this->yesButtonClicked;
	}

	public function resetValue() : void{
		unset($this->yesButtonClicked);
	}

	public function jsonSerialize(array $attachments) : array{
		return [
			"type" => "modal",
			"title" => $this->title,
			"content" => $this->content,
			"button1" => $this->yesButtonText,
			"button2" => $this->noButtonText,
		];
	}

	public function isAttachmentApplicable(FormAttachment $attachment) : bool{
		return false;
	}
}
