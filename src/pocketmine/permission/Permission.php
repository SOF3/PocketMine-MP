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

use pocketmine\Server;

/**
 * Represents a permission
 */
class Permission{
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

	/**
	 * @var bool[]
	 */
	private $children;

	/** @var string */
	private $defaultValue;

	/**
	 * Creates a new Permission object to be attached to Permissible objects
	 *
	 * @param string $name
	 * @param string $description
	 * @param string $defaultValue
	 * @param bool[] $children
	 */
	public function __construct(string $name, string $description = null, string $defaultValue = null){
		$this->name = $name;
		$this->description = $description ?? "";
		$this->defaultValue = $defaultValue ?? self::$DEFAULT_PERMISSION;
	}

	/**
	 * @return string
	 */
	public function getName() : string{
		return $this->name;
	}

	/**
	 * @return bool[]
	 */
	public function getChildren() : array{
		return $this->children;
	}

	/**
	 * @return string
	 */
	public function getDefault() : string{
		return $this->defaultValue;
	}

	/**
	 * @param string $value
	 */
	public function setDefault(string $value) : void{
		if($value !== $this->defaultValue){
			$this->defaultValue = $value;
			$this->recalculatePermissibles();
		}
	}

	/**
	 * @return string
	 */
	public function getDescription() : string{
		return $this->description;
	}

	/**
	 * @param string $value
	 */
	public function setDescription(string $value) : void{
		$this->description = $value;
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
