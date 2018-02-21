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
	public const POSITION_TAIL = -1;

	public function append(CustomFormElement $new) : CustomFormAttachment{
		return $this->insert($new, self::POSITION_TAIL);
	}

	public function prepend(CustomFormElement $new) : CustomFormAttachment{
		return $this->insert($new, 0);
	}

	public function insert(CustomFormElement $new, int $position) : CustomFormAttachment{
		// TODO implement
		return $this;
	}
}