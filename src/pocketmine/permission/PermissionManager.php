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

final class PermissionManager{
	/** @var Permission[] */
	private $permissions = [];
	/** @var Permissible[] */
	private $permissibles = [];

	/**
	 * Permission predicates are evaluated when hasPermission() is called and other predicates of higher priority are
	 *
	 * Warning: Always prefer using permission attachments if possible. Permission attachments will make hasPermission() evaluate slower and does not affect the command list sent to clients.
	 *
	 * @param string                        $permission
	 * @param Plugin                        $plugin
	 * @param ParticularPermissionPredicate $predicate
	 */
	public function registerParticularPredicate(string $permission, Plugin $plugin, ParticularPermissionPredicate $predicate) : void{
		if(!isset($this->permissions[$permission])){
			throw new \InvalidArgumentException("The permission $permission was not registered");
		}
	}

	public function registerGroupPredicate(string $permission, Plugin $plugin, GroupPermissionPredicate $predicate) : void{
		if(!isset($this->permissions[$permission])){
			throw new \InvalidArgumentException("The permission $permission was not registered");
		}
	}


	public function registerPermission(Permission $permission) : void{
		if(isset($this->permissions[$permission->getName()])){
			throw new \InvalidArgumentException("{$permission->getName()} was already registered. Permissions are only automatically unregistered when the owner plugin is disabled");
		}

		$this->permissions[$permission->getName()] = $permission;
	}

	public function unregisterPermission(Permission $permission) : void{
		unset($this->permissions[$permission->getName()]);
	}

	public function getPermission(string $permission) : ?Permission{
		return $this->permissions[$permission] ?? null;
	}

	public function addPermissible(Permissible $permissible) : void{
		if(isset($this->permissibles[spl_object_hash($permissible)])){
			throw new \InvalidStateException("Permissible is created twice");
		}
		$this->permissibles[spl_object_hash($permissible)] = $permissible;
	}

	public function removePermissible(Permissible $permissible) : void{
		if(!isset($this->permissibles[spl_object_hash($permissible)])){
			throw new \InvalidStateException("Permissible was not created or already closed");
		}
		unset($this->permissibles[spl_object_hash($permissible)]);
	}

	/**
	 * @return Permission[]
	 */
	public function getPermissions() : array{
		return $this->permissions;
	}

	/**
	 * @return Permissible[]
	 */
	public function getPermissibles() : array{
		return $this->permissibles;
	}

	/**
	 * @param Permission|string $permission
	 * @return Permissible[]
	 */
	public function getPermissiblesWith($permission) : array{
		return array_filter($this->permissibles, function(Permissible $permissible) use ($permission): bool{
			return $permissible->hasPermission($permission);
		});
	}


	public function clearPlugin(Plugin $plugin) : void{
		foreach($this->permissions as $permission){
			if($permission->getOwner() === $plugin){
				$this->unregisterPermission($permission);
			}else{
				$permission->clearPlugin($plugin);
			}
		}
	}
}
