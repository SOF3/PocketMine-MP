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

namespace pocketmine\form;

use pocketmine\event\player\form\PlayerFormReceiveEvent;
use pocketmine\form\attachment\FormAttachment;
use pocketmine\form\layout\FormLayout;
use pocketmine\Player;
use pocketmine\utils\Utils;

final class FormHandler implements \JsonSerializable{
	public static $nextFormId = 1;

	/** @var Player */
	private $player;
	/** @var FormLayout */
	private $layout;
	/** @var callable */
	private $onSubmit, $onClose;

	/** @var float */
	private $sentAt = 0.0;
	private $returned = false;

	/** @var FormAttachment[] */
	private $attachments = [];

	public function __construct(Player $player, FormLayout $layout, callable $onSubmit, ?callable $onClose = null){
		$this->player = $player;
		$this->layout = $layout;
		$this->setOnSubmit($onSubmit);
		$this->setOnClose($onClose);

		$this->layout->addLock();
	}

	/**
	 * @param callable $onSubmit
	 */
	public function setOnSubmit(callable $onSubmit) : void{
		assert($this->acceptsCorrectLayout($onSubmit), get_class($this->layout) . " is not assignable to \$onSubmit");
		$this->onSubmit = $onSubmit;
	}

	/**
	 * @param callable $onClose
	 */
	public function setOnClose(?callable $onClose) : void{
		if($onClose !== null){
			assert($this->layout->isCloseable(), "\$onClose is not applicable for a non-closeable form layout");
			assert(Utils::reflectCallable($onClose)->getNumberOfRequiredParameters() === 0, "\$onClose must not accept required parameters");
			$this->onClose = $onClose;
		}else{
			$this->onClose = null;
		}
	}

	private function acceptsCorrectLayout(callable $callable) : bool{
		$ref = Utils::reflectCallable($callable);
		$params = $ref->getParameters();
		if(isset($params[0])){
			$type = $params[0]->getType();
			if($type !== null && !is_a($this->layout, $type->getName())){
				return false;
			}
		}
		return true;
	}

	public function handleResponse($response) : bool{
		if($this->returned){
			throw new \InvalidStateException("The FormHandler has already been returned");
		}

		// TODO process attachments by FILO order
		$submitted = $this->layout->acceptValue($response);

		$this->player->getServer()->getPluginManager()->callEvent($ev = new PlayerFormReceiveEvent($this));
		if($ev->shouldResend()){
			return true;
		}

		$this->returned = true;
		$this->layout->removeLock();

		if($submitted){
			($this->onSubmit)($this->layout);
			$this->layout->resetValue();
			// no need to reset attachments, because they are used once only
		}else{
			if($this->onClose !== null){
				($this->onClose)();
			}
		}
		return false;
	}

	public function markSent() : void{
		$this->sentAt = microtime(true);
	}

	public function isSent() : bool{
		return $this->sentAt > 0;
	}

	public function getSendTime() : ?float{
		return $this->sentAt === 0.0 ? null : $this->sentAt;
	}

	public function isReturned() : bool{
		return $this->returned;
	}

	/**
	 * @return Player
	 */
	public function getPlayer() : Player{
		return $this->player;
	}

	/**
	 * @return FormLayout
	 */
	public function getLayout() : FormLayout{
		return $this->layout;
	}

	public function createAttachment() : FormAttachment{
		$this->attachments[] = $att = $this->layout->createAttachment();
		return $att;
	}

	public function sortAttachments() : void{
		// https://en.wikipedia.org/wiki/Topological_sorting#Kahn's_algorithm

		$nodes = $this->attachments;

		$s = [];
		/** @var FormAttachment[][] $outEdges */
		$outEdges = []; // for each i -> j, $outEdges[i] = [j]
		/** @var FormAttachment[][] $inEdges */
		$inEdges = []; // for each i -> j, $inEdges[j] = [i]

		foreach($nodes as $i => $nodeI){
			for($nodeJ = $nodes[$j = $i + 1], $jMax = count($nodes); $j < $jMax; $nodeJ = $nodes[++$j]){
				$ij = $nodeJ->isDependentOn($nodeI); // edge(nodes[i] -> nodes[j])
				$ji = $nodeI->isDependentOn($nodeJ); // edge(nodes[j] -> nodes[i])
				if($ij && $ji){
					throw new \LogicException("Circular dependency between {$nodeI->getTag()} and {$nodeJ->getTag()}");
				}
				if($ij){
					$outEdges[spl_object_hash($nodeI)][spl_object_hash($nodeJ)] = $nodeJ;
					$inEdges[spl_object_hash($nodeJ)][spl_object_hash($nodeI)] = $nodeI;
				}
				if($ji){
					$outEdges[spl_object_hash($nodeJ)][spl_object_hash($nodeI)] = $nodeI;
					$inEdges[spl_object_hash($nodeI)][spl_object_hash($nodeJ)] = $nodeJ;
				}
			}
		}

		$l = [];

		while(!empty($s)){
			$n = array_shift($s);
			$nHash = spl_object_hash($n);
			$l[] = $n;
			foreach($outEdges[$nHash] as $mHash => $m){ // all $m where $n -> $m
				unset($outEdges[$nHash][$mHash], $inEdges[$mHash][$nHash]);
				// if there is no $inEdges[$x][$y] where $y === $m
				if(empty($inEdges[$mHash])){
					$s[] = $m;
				}
			}
		}
		if(array_sum(array_map("count", $outEdges)) > 0){
			throw new \LogicException("Circular dependency detected");
		}
		$this->attachments = $l;
	}


	public function jsonSerialize(){
		return $this->layout->jsonSerialize($this->attachments);
	}
}
