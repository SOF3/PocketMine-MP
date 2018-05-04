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

abstract class DefaultPermissions{
	public const ROOT = "pocketmine";

	public static function registerCorePermissions() : void{
		$parent = new Permission(self::ROOT, "Allows using all PocketMine commands and utilities");

		$broadcasts = new Permission(self::ROOT . ".broadcast", "Allows the user to receive all broadcast messages", $parent);
		new Permission(self::ROOT . ".broadcast.admin", "Allows the user to receive administrative broadcasts", $broadcasts, Permission::DEFAULT_OP);
		new Permission(self::ROOT . ".broadcast.user", "Allows the user to receive user broadcasts", $broadcasts, Permission::DEFAULT_TRUE);

		new Permission(self::ROOT . ".spawnprotect.bypass", "Allows the user to edit blocks within the protected spawn radius", $parent, Permission::DEFAULT_OP);

		$commands = new Permission(self::ROOT . ".command", "Allows using all PocketMine commands", $parent);

		$whitelist = new Permission(self::ROOT . ".command.whitelist", "Allows the user to modify the server whitelist", $commands, Permission::DEFAULT_OP);
		new Permission(self::ROOT . ".command.whitelist.add", "Allows the user to add a player to the server whitelist", $whitelist);
		new Permission(self::ROOT . ".command.whitelist.remove", "Allows the user to remove a player to the server whitelist", $whitelist);
		new Permission(self::ROOT . ".command.whitelist.reload", "Allows the user to reload the server whitelist", $whitelist);
		new Permission(self::ROOT . ".command.whitelist.enable", "Allows the user to enable the server whitelist", $whitelist);
		new Permission(self::ROOT . ".command.whitelist.disable", "Allows the user to disable the server whitelist", $whitelist);
		new Permission(self::ROOT . ".command.whitelist.list", "Allows the user to list all the players on the server whitelist", $whitelist);

		$ban = new Permission(self::ROOT . ".command.ban", "Allows the user to ban people", $commands, Permission::DEFAULT_OP);
		new Permission(self::ROOT . ".command.ban.player", "Allows the user to ban players", $ban);
		new Permission(self::ROOT . ".command.ban.ip", "Allows the user to ban IP addresses", $ban);

		$unban = new Permission(self::ROOT . ".command.unban", "Allows the user to unban people", $commands, Permission::DEFAULT_OP);
		new Permission(self::ROOT . ".command.unban.player", "Allows the user to unban players", $unban);
		new Permission(self::ROOT . ".command.unban.ip", "Allows the user to unban IP addresses", $unban);

		$op = new Permission(self::ROOT . ".command.op", "Allows the user to change operators", $commands, Permission::DEFAULT_OP);
		new Permission(self::ROOT . ".command.op.give", "Allows the user to give a player operator status", $op);
		new Permission(self::ROOT . ".command.op.take", "Allows the user to take a players operator status", $op);

		$save = new Permission(self::ROOT . ".command.save", "Allows the user to save the worlds", $commands, Permission::DEFAULT_OP);
		new Permission(self::ROOT . ".command.save.enable", "Allows the user to enable automatic saving", $save);
		new Permission(self::ROOT . ".command.save.disable", "Allows the user to disable automatic saving", $save);
		new Permission(self::ROOT . ".command.save.perform", "Allows the user to perform a manual save", $save);

		$time = new Permission(self::ROOT . ".command.time", "Allows the user to alter the time", $commands, Permission::DEFAULT_OP);
		new Permission(self::ROOT . ".command.time.add", "Allows the user to fast-forward time", $time);
		new Permission(self::ROOT . ".command.time.set", "Allows the user to change the time", $time);
		new Permission(self::ROOT . ".command.time.start", "Allows the user to restart the time", $time);
		new Permission(self::ROOT . ".command.time.stop", "Allows the user to stop the time", $time);
		new Permission(self::ROOT . ".command.time.query", "Allows the user query the time", $time);

		$kill = new Permission(self::ROOT . ".command.kill", "Allows the user to kill players", $commands, Permission::DEFAULT_OP);
		new Permission(self::ROOT . ".command.kill.self", "Allows the user to commit suicide", $kill, Permission::DEFAULT_TRUE);
		new Permission(self::ROOT . ".command.kill.other", "Allows the user to kill other players", $kill);

		new Permission(self::ROOT . ".command.me", "Allows the user to perform a chat action", $commands, Permission::DEFAULT_TRUE);
		new Permission(self::ROOT . ".command.tell", "Allows the user to privately message another player", $commands, Permission::DEFAULT_TRUE);
		new Permission(self::ROOT . ".command.say", "Allows the user to talk as the console", $commands, Permission::DEFAULT_OP);
		new Permission(self::ROOT . ".command.give", "Allows the user to give items to players", $commands, Permission::DEFAULT_OP);
		new Permission(self::ROOT . ".command.effect", "Allows the user to give/take potion effects", $commands, Permission::DEFAULT_OP);
		new Permission(self::ROOT . ".command.enchant", "Allows the user to enchant items", $commands, Permission::DEFAULT_OP);
		new Permission(self::ROOT . ".command.particle", "Allows the user to create particle effects", $commands, Permission::DEFAULT_OP);
		new Permission(self::ROOT . ".command.teleport", "Allows the user to teleport players", $commands, Permission::DEFAULT_OP);
		new Permission(self::ROOT . ".command.kick", "Allows the user to kick players", $commands, Permission::DEFAULT_OP);
		new Permission(self::ROOT . ".command.stop", "Allows the user to stop the server", $commands, Permission::DEFAULT_OP);
		new Permission(self::ROOT . ".command.list", "Allows the user to list all online players", $commands, Permission::DEFAULT_OP);
		new Permission(self::ROOT . ".command.help", "Allows the user to view the help menu", $commands, Permission::DEFAULT_TRUE);
		new Permission(self::ROOT . ".command.plugins", "Allows the user to view the list of plugins", $commands, Permission::DEFAULT_OP);
		new Permission(self::ROOT . ".command.reload", "Allows the user to reload the server settings", $commands, Permission::DEFAULT_OP);
		new Permission(self::ROOT . ".command.version", "Allows the user to view the version of the server", $commands, Permission::DEFAULT_TRUE);
		new Permission(self::ROOT . ".command.gamemode", "Allows the user to change the gamemode of players", $commands, Permission::DEFAULT_OP);
		new Permission(self::ROOT . ".command.defaultgamemode", "Allows the user to change the default gamemode", $commands, Permission::DEFAULT_OP);
		new Permission(self::ROOT . ".command.seed", "Allows the user to view the seed of the world", $commands, Permission::DEFAULT_OP);
		new Permission(self::ROOT . ".command.status", "Allows the user to view the server performance", $commands, Permission::DEFAULT_OP);
		new Permission(self::ROOT . ".command.gc", "Allows the user to fire garbage collection tasks", $commands, Permission::DEFAULT_OP);
		new Permission(self::ROOT . ".command.dumpmemory", "Allows the user to dump memory contents", $commands, Permission::DEFAULT_OP);
		new Permission(self::ROOT . ".command.timings", "Allows the user to records timings for all plugin events", $commands, Permission::DEFAULT_OP);
		new Permission(self::ROOT . ".command.spawnpoint", "Allows the user to change player's spawnpoint", $commands, Permission::DEFAULT_OP);
		new Permission(self::ROOT . ".command.setworldspawn", "Allows the user to change the world spawn", $commands, Permission::DEFAULT_OP);
		new Permission(self::ROOT . ".command.transferserver", "Allows the user to transfer self to another server", $commands, Permission::DEFAULT_OP);
		new Permission(self::ROOT . ".command.title", "Allows the user to send a title to the specified player", $commands, Permission::DEFAULT_OP);
	}
}
