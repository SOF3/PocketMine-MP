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

/**
 * API for Minecraft: Bedrock custom UI (forms)
 */
namespace pocketmine\form;

use pocketmine\Player;

/**
 * Base class for a custom form. Forms are serialized to JSON data to be sent to clients.
 */
abstract class Form implements \JsonSerializable{

	const TYPE_MODAL = "modal";
	const TYPE_MENU = "form";
	const TYPE_CUSTOM_FORM = "custom_form";

	/** @var string */
	protected $title = "";
	/** @var bool */
	private $queued = false;
	/** @var FormSubmitHandler|null */
	private $submitHandler;
	/** @var FormCloseHandler|null */
	private $closeHandler;

	public function __construct(string $title, ?FormSubmitHandler $submitHandler = null, ?FormCloseHandler $closeHandler = null){
		$this->title = $title;
		$this->submitHandler = $submitHandler;
		$this->closeHandler = $closeHandler;
	}

	/**
	 * Returns the type used to show this form to clients
	 * @return string
	 */
	abstract public function getType() : string;

	/**
	 * Returns the text shown on the form title-bar.
	 * @return string
	 */
	public function getTitle() : string{
		return $this->title;
	}

	/**
	 * Handles a form response from a player. Plugins should not override this method, override {@link onSubmit}
	 * instead.
	 *
	 * @param Player $player
	 * @param mixed  $data
	 *
	 * @return Form|null a form which will be opened immediately (before queued forms) as a response to this form, or null if not applicable.
	 */
	abstract public function handleResponse(Player $player, $data) : ?Form;

	/**
	 * Called when a player submits this form. Each form type usually has its own methods for getting relevant data from
	 * them.
	 *
	 * Plugins should either override this method or pass a {@link FormHandler} in the <code>$submitHandler</code>
	 * argument of the {@link Form::__construct constructor} to handle form submission. If this method is not overridden
	 * and null is passed, nothing will happen and null is returned.
	 *
	 * @param Player $player
	 * @return Form|null a form which will be opened immediately (before queued forms) as a response to this form,
	 * or null if not applicable.
	 */
	protected function onSubmit(Player $player) : ?Form{
		if($this->submitHandler !== null){
			return $this->submitHandler->onSubmit($this, $player);
		}
		return null;
	}

	/**
	 * Called when a player clicks the close button on this form without submitting it.
	 *
	 * Plugins should either override this method or pass a {@link FormHandler} in the <code>$closeHandler</code>
	 * argument of the form constructor to handle form submission. If this method is not overridden and null is passed,
	 * nothing will happen and null is returned.
	 *
	 * This method <strong>does not</strong> close the form for a player.
	 *
	 * @param Player $player
	 * @return Form|null a form which will be opened immediately (before queued forms) as a response to this form, or null if not applicable.
	 */
	protected function onClose(Player $player) : ?Form{
		assert($this->isCloseable(), get_class($this) . " cannot be closed");
		if($this->closeHandler !== null){
			return $this->closeHandler->onClose($this, $player);
		}
		return null;
	}

	abstract public function isCloseable() : bool;

	/**
	 * Returns whether the form has already been sent to a player or not. Note that you cannot send the form again if
	 * this is true.
	 *
	 * @return bool
	 */
	public function hasBeenQueued() : bool{
		return $this->queued;
	}

	/**
	 * Called to flag the form as having been sent to prevent it being used again, to avoid concurrency issues.
	 */
	public function setHasBeenQueued() : void{
		$this->queued = true;
	}

	/**
	 * Clears response data from a form, useful if you want to reuse the same form object several times.
	 */
	public function clearResponseData() : void{

	}

	/**
	 * Serializes the form to JSON for sending to clients.
	 *
	 * @return array
	 */
	final public function jsonSerialize() : array{
		$jsonBase = [
			"type" => $this->getType(),
			"title" => $this->getTitle()
		];

		return array_merge($jsonBase, $this->serializeFormData());
	}

	/**
	 * Serializes additional data needed to show this form to clients.
	 * @return array
	 */
	abstract protected function serializeFormData() : array;

}
