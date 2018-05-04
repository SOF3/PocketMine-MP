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

namespace pocketmine\permission;

use pocketmine\plugin\Plugin;

trait PermissibleBase{
	/** @var PermissionManager */
	private $manager;

	/** @var PermissionAttachment[] permission name => lowest priority attachment */
	private $attachments = [];

	abstract public function isClosed() : bool;

	protected function createPermissible(PermissionManager $manager, $args = null) : void{
		$this->manager = $manager;
		if(!($this instanceof Permissible)){
			throw new \LogicException("PermissibleBase users must implement Permissible");
		}
		$manager->addPermissible($this);

		// TODO fire event

		$this->onPermissibleCreated($args);
	}

	protected function onPermissibleCreated($args) : void{
	}

	protected function closePermissible() : void{
		$this->manager->removePermissible($this);
		unset($this->attachments);
	}

	/**
	 * @param Permission|string $permission
	 * @return bool
	 */
	public function hasPermission($permission) : bool{
		assert($this instanceof Permissible);

		if(!$this->isClosed()){
			throw new \InvalidStateException("Trying to get permissions of closed permissible");
		}

		if(is_string($permission)){
			$permission = $this->manager->getPermission($permission);
			if($permission === null){
				throw new \InvalidArgumentException("Attempt to check permissible for an unregistered permission");
			}
		}
		$permName = $permission->getName();

		if(($result = $permission->testParticular($this)) !== null){
			return $result;
		}

		if(isset($this->attachments[$permName])){
			$result = $this->attachments[$permName]->result;
			if($result !== null){
				return $result;
			}
		}

		if(($result = $permission->testGroup($this)) !== null){
			return $result;
		}

		for($parent = $permission->getParent(); $parent !== null; $parent = $parent->getParent()){
			if(isset($this->attachments[$permName])){
				$result = $this->attachments[$permName]->result;
				if($result !== null){
					return $result;
				}
			}
		}

		return $permission->checkDefault($this->isOp());
	}

	public function addAttachment(Plugin $plugin, string $permName, float $priority, bool $inherit, ?bool $ternary) : PermissionAttachment{
		$permission = $this->manager->getPermission($permName);
		if($permission === null){
			throw new \InvalidArgumentException("Attempt to add attachment for an unregistered permission");
		}

		$attachment = new PermissionAttachment($permission, $plugin, $priority, $inherit, $ternary);
		if(!isset($this->attachments[$permName])){
			$this->attachments[$permName] = [$attachment, $attachment];
			$attachment->recalculateValue();
			return $attachment;
		}

		$attachment->higher = $this->attachments[$permName];
		while($attachment->higher !== null && $attachment->getPriority() > $attachment->higher->getPriority()){
			$attachment->lower = $attachment->higher;
			$attachment->higher = $attachment->higher->higher;
		}

		if($attachment->higher !== null){
			$attachment->higher->lower = $attachment;
		}
		if($attachment->lower !== null){
			$attachment->lower->higher = $attachment;
		}

		$attachment->recalculateValue();
		return $attachment;
	}

	public function removeAttachment(PermissionAttachment $attachment) : void{
		if($attachment->higher !== null){
			$attachment->higher->lower = $attachment->lower;
		}

		if($attachment->lower !== null){
			$attachment->lower->higher = $attachment->higher;
			$attachment->lower->recalculateValue();
		}else{
			if($attachment->higher !== null){
				$this->attachments[$attachment->getPermission()->getName()] = $attachment->higher;
			}else{
				unset($this->attachments[$attachment->getPermission()->getName()]);
			}
		}
	}

	public function clearPlugin(Plugin $plugin) : void{
		foreach($this->attachments as $link){
			for(; $link !== null; $link = $link->higher){
				if($link->getPlugin() === $plugin){
					$this->removeAttachment($link);
				}
			}
		}
	}
}
