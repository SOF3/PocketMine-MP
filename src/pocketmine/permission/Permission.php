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
 * Permission related classes
 */

namespace pocketmine\permission;

use pocketmine\plugin\Plugin;
use pocketmine\Server;

/**
 * Represents a permission
 */
final class Permission{
	public const DEFAULT_OP = "op";
	public const DEFAULT_NOT_OP = "notop";
	public const DEFAULT_TRUE = "true";
	public const DEFAULT_FALSE = "false";

	public static $DEFAULT_PERMISSION = self::DEFAULT_OP;

	/**
	 * @param bool|string $value
	 *
	 * @return string
	 */
	public static function getByName($value) : string{
		if(is_bool($value)){
			if($value){
				return "true";
			}else{
				return "false";
			}
		}
		switch(strtolower($value)){
			case "op":
			case "isop":
			case "operator":
			case "isoperator":
			case "admin":
			case "isadmin":
				return self::DEFAULT_OP;

			case "!op":
			case "notop":
			case "!operator":
			case "notoperator":
			case "!admin":
			case "notadmin":
				return self::DEFAULT_NOT_OP;

			case "true":
				return self::DEFAULT_TRUE;

			default:
				return self::DEFAULT_FALSE;
		}
	}

	/** @var string */
	private $name;

	/** @var string */
	private $description;

	/** @var Permission|null */
	private $parent;

	/** @var string */
	private $defaultValue;

	/** @var Plugin|null */
	private $owner;

	/** @var RegisteredParticularPermissionPredicate[] */
	private $particularPredicates = [];
	/** @var RegisteredGroupPermissionPredicate[] */
	private $groupPredicates = [];

	/**
	 * @var Permission[]
	 */
	private $children = [];

	public function __construct(string $name, string $description = null, ?Permission $parent = null, string $defaultValue = null, ?Plugin $owner = null){
		$this->name = $name;
		$this->description = $description ?? "";
		$this->parent = $parent;
		$this->defaultValue = $defaultValue ?? self::$DEFAULT_PERMISSION;
		$this->owner = $owner;

		if($parent !== null){
			$parent->children[$name] = $this;
		}

		Server::getInstance()->getPermissionManager()->registerPermission($this);
	}

	public function getName() : string{
		return $this->name;
	}

	public function getDescription() : string{
		return $this->description;
	}

	public function getParent() : ?Permission{
		return $this->parent;
	}

	public function getDefault() : string{
		return $this->defaultValue;
	}

	public function getOwner() : ?Plugin{
		return $this->owner;
	}

	/**
	 * @return Permission[]
	 */
	public function getChildren() : array{
		return $this->children;
	}

	public function testParticular(Permissible $permissible) : ?bool{
		foreach($this->particularPredicates as $predicate){
			if(($result = $predicate->getPredicate()->test($permissible)) !== null){
				return $result;
			}
		}
		return null;
	}

	public function testGroup(Permissible $permissible) : ?bool{
		foreach($this->groupPredicates as $predicate){
			if(($result = $predicate->getPredicate()->test($permissible, $this)) !== null){
				return $result;
			}
		}
		return $this->parent !== null ? $this->parent->testGroup($permissible) : null;
	}

	public function addParticularPredicate(Plugin $plugin, ParticularPermissionPredicate $predicate, float $priority = 0.0) : void{
		$this->particularPredicates[] = new RegisteredParticularPermissionPredicate($plugin, $predicate, $priority);

		usort($this->particularPredicates, function(RegisteredParticularPermissionPredicate $a, RegisteredParticularPermissionPredicate $b) : int{
			return $b->getPriority() <=> $a->getPriority();
		});
	}

	public function addGroupPredicate(Plugin $plugin, GroupPermissionPredicate $predicate, float $priority = 0.0) : void{
		$this->groupPredicates[] = new RegisteredGroupPermissionPredicate($plugin, $predicate, $priority);

		usort($this->groupPredicates, function(RegisteredGroupPermissionPredicate $a, RegisteredGroupPermissionPredicate $b) : int{
			return $b->getPriority() <=> $a->getPriority();
		});
	}

	public function checkDefault(bool $op) : bool{
		switch($this->defaultValue){
			case self::DEFAULT_TRUE:
				return true;
			case self::DEFAULT_FALSE:
				return false;
			case self::DEFAULT_OP:
				return $op;
			case self::DEFAULT_NOT_OP:
				return !$op;
		}
		throw new \UnexpectedValueException("Unexpected defaultValue $this->defaultValue");
	}

	public function clearPlugin(Plugin $plugin) : void{
		foreach($this->particularPredicates as $key => $predicate){
			if($predicate->getPlugin() === $plugin){
				unset($this->particularPredicates[$key]);
			}
		}

		foreach($this->groupPredicates as $key => $predicate){
			if($predicate->getPlugin() === $plugin){
				unset($this->groupPredicates[$key]);
			}
		}
	}

	/**
	 * @param array  $data
	 * @param string $default
	 *
	 * @return Permission[]
	 */
	public static function loadPermissions(array $data, string $default = self::DEFAULT_OP) : array{
		// TODO
	}

	/**
	 * @param string $name
	 * @param array  $data
	 * @param string $default
	 * @param array  $output
	 *
	 * @return Permission
	 *
	 * @throws \Exception
	 */
	public static function loadPermission(string $name, array $data, string $default = self::DEFAULT_OP, array &$output = []) : Permission{
		// TODO
	}
}
