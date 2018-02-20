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

abstract class FormLayout {
	/** @var int */
	private $immutableLock = 0;

	/**
	 * Determines whether a close handler is applicable for this layout
	 * @return bool
	 */
	public abstract function isCloseable() : bool;

	/**
	 * Sets this form's values according to the response
	 * @param mixed $data
	 * @return bool whether the values have been set successfully. false indicates that the client did not submit the form correctly.
	 *
	 * @internal only to be called from FormHandler with the raw response from client
	 */
	public abstract function acceptValue($data) : bool;

	/**
	 * Clears the form's values
	 *
	 * @internal only to be called from FormHandler after acceptValue() returns true.
	 */
	public abstract function resetValue() : void;

	/**
	 * @param FormAttachment[] $attachments the attachments to be applied
	 * @return array the array to be JSON-encoded
	 */
	public abstract function jsonSerialize(array $attachments) : array;

	/**
	 * Checks whether the attachment is applicable to this form layout
	 * @param FormAttachment $attachment
	 * @return bool
	 */
	public abstract function isAttachmentApplicable(FormAttachment $attachment)  : bool; // no generics :(


	public function addLock() : void{
		++$this->immutableLock;
	}

	public function removeLock() : void{
		assert($this->immutableLock > 0, "The immutable lock is being removed more times than being added");
		--$this->immutableLock;
	}

	protected function validateMutable() : void{
		if($this->immutableLock > 0){
			throw new \InvalidStateException("Attempt to modify the return fields of a form that has been sent but not yet returned. To make changes from other plugins, use the FormAttachment API instead.");
		}
	}
}
