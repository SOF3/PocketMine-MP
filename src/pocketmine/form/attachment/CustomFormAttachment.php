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

namespace pocketmine\form\attachment;

use pocketmine\form\element\CustomFormElement;

class CustomFormAttachment extends FormAttachment{
	public const POSITION_TAIL = ShiftSet::POSITION_TAIL;

	private $entries = [];

	public function append(CustomFormElement $new) : CustomFormAttachment{
		return $this->insert($new, self::POSITION_TAIL);
	}

	public function prepend(CustomFormElement $new) : CustomFormAttachment{
		return $this->insert($new, 0);
	}

	public function insert(CustomFormElement $new, int $position) : CustomFormAttachment{
		$this->entries[] = [$new, $position];
		return $this;
	}

	/**
	 * @return CustomFormElement[][]|int[][]
	 */
	public function getEntries() : array{
		usort($this->entries, function($a, $b){
			if($a[1] === -1){
				return $b[1] === -1 ? 0 : 1;
			}
			if($b[1] === -1){
				return -1;
			}
			return $a[1] <=> $b[1];
		});
		return $this->entries;
	}
}
