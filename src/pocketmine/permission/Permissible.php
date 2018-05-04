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

interface Permissible extends ServerOperator{
	/**
	 * Checks the permission of a permissible in this order:
	 *
	 * - Starting from the ParticularPermissionPredicate of the highest priority, each predicate is called. If it returns a non-null value, it is returned without calling predicates of lower priorities.
	 * - If permission attachments to $permission report a non-null value overall, it is returned.
	 * - Starting from the GroupPermissionPredicate of the highest priority to the lowest for $permission, then the GroupPermissionPredicates of the parent permission of $permission, vice versa, each predicate is called. If it returns a non-null value, it is returned without calling predicates of lower priorities and parent permissions' predicates.
	 * - If permission attachments to the parent of $permission report a non-null value overall, it is returned. Otherwise, that for the grandparent of $permission is checked, vice versa.
	 * - The default permission value is used, i.e. "true", "false", "op", "notop".
	 *
	 * @param string|Permission $permission
	 *
	 * @return bool
	 */
	public function hasPermission($permission) : bool;
}
