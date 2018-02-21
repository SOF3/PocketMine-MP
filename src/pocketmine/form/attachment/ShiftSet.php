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

class ShiftSet{
	public const POSITION_TAIL = -1;

	/** @var int[] */
	private $numbers = [];
	/** @var FormAttachment */
	private $attachment;

	public function __construct(FormAttachment $attachment){
		$this->attachment = $attachment;
	}

	/**
	 * @param int        $number
	 * @param ShiftSet[] $previousSets
	 * @return int
	 */
	public function push(int $number, array $previousSets) : int{
		if($number === -1){
			return -1;
			// we don't even need to store it!
		}
		$number += count($this->numbers);
		foreach($previousSets as $set){
			if($this->attachment->isDependentOn($set->attachment)){
				continue;
			}

			foreach($set->numbers as $modified){
				if($modified < $number){
					++$number;
				}
			}
		}
		$this->numbers[] = $number;
		return $number;
	}

	/**
	 * @return int[]
	 */
	public function getNumbers() : array{
		return $this->numbers;
	}

	/**
	 * @param mixed[]          $elements
	 * @param FormAttachment[] $attachments
	 */
	public static function applyPatches(array &$elements, array $attachments) : void{
		// FIXME: this logic isn't working 100% correctly yet.

		$shiftSets = [];
		foreach($attachments as $att){
			$entries = $att->getEntries();
			$set = new ShiftSet($att);
			/**
			 * @var mixed $element
			 * @var int   $position
			 */
			foreach($entries as [$element, $position]){
				$offset = $set->push($position, $shiftSets);
				if($offset !== -1){
					array_splice($elements, $offset, 0, [$element]);
				}else{
					$elements[] = $element;
				}
			}
			$shiftSets[] = $set;
		}
	}
}
