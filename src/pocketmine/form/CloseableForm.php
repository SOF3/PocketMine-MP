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

abstract class CloseableForm extends Form implements FormCloseHandler{
	/** @var FormCloseHandler */
	private $closeHandler;

	/**
	 * {@inheritdoc}
	 *
	 * @param null|FormCloseHandler $closeHandler The handler when this form is closed. If null is passed, {@link Form::onClose() this form} will be used as the handler.
	 */
	public function __construct(string $title, ?FormSubmitHandler $submitHandler = null, ?FormCloseHandler $closeHandler = null){
		parent::__construct($title, $submitHandler);
		$this->closeHandler = $closeHandler ?? $this;
	}

	/**
	 * @return FormCloseHandler
	 */
	public function getCloseHandler() : FormCloseHandler{
		return $this->closeHandler;
	}

	/**
	 * @param FormCloseHandler|null $closeHandler
	 */
	public function setCloseHandler(?FormCloseHandler $closeHandler) : void{
		$this->closeHandler = $closeHandler ?? $this;
	}
}
