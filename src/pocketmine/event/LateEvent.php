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

namespace pocketmine\event;

use pocketmine\plugin\LateRegisteredListener;
use pocketmine\Server;

/**
 * LateEvent is an event that does not expect to retrieve a result immediately. This is useful for plugins that want to
 * wait for reply from another party before completing the event, e.g. cURL replies. Handlers may also specify the
 * &#64;timeout tag to change the allowed timeout (default 5s), e.g. to wait for user input. However, this should never
 * exceed one minute.
 */
abstract class LateEvent extends Event{
	/** @var LateRegisteredListener[] */
	private $callQueue;
	/** @var float|null */
	private $timeout;
	private $completed = false;
	/** @var callable|null */
	private $onComplete;

	public function __construct(?callable $onComplete){
		$this->onComplete = $onComplete;
	}

	public function continue() : void{
		if(next($this->callQueue) !== false){
			$this->callCurrentEvent();
		}else{
			$this->completed = true;
		}
	}

	/**
	 * This is an INTERNAL method. Do not call this from plugins. Changes to this method will not be documented.
	 *
	 * @param LateRegisteredListener[] $listeners
	 */
	public function setCallQueue(array $listeners) : void{
		$this->callQueue = $listeners;
		if(count($listeners) === 0){
			$this->completed = true;
			return;
		}
		reset($this->callQueue);
		$this->callCurrentEvent();
	}

	private function callCurrentEvent() : void{
		/** @var LateRegisteredListener $current */
		$current = current($this->callQueue);
		$this->timeout = microtime(true) + $current->getTimeout();
		$current->callEvent($this);
	}

	public function checkTimeout() : void{
		if(!$this->completed && microtime(true) > $this->timeout){
			/** @var LateRegisteredListener $timeout */
			$timeout = current($this->callQueue);
			Server::getInstance()->getLogger()->error(
				Server::getInstance()->getLanguage()->translateString("pocketmine.plugin.eventTimeout", [
					$this->getEventName(),
					$timeout->getPlugin()->getDescription()->getFullName(),
					$timeout->getTimeout(),
					get_class($timeout->getListener())
				]));
			$this->continue();
		}
	}

	/**
	 * @return bool
	 */
	public function isCompleted() : bool{
		return $this->completed;
	}

	public function onCompletion() : void{
		if($this->onComplete !== null){
			($this->onComplete)();
		}
		unset($this->callQueue, $this->onComplete);
		$this->completed = false;
	}
}
