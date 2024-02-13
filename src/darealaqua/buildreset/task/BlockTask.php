<?php

/**
 * Author: DaRealAqua
 * Date: February 13, 2024
 */

namespace darealaqua\buildreset\task;

use darealaqua\buildreset\Main;
use pocketmine\block\Block;
use pocketmine\scheduler\Task;

class BlockTask extends Task {

	/**
	 * @param Main  $main
	 * @param Block $block
	 */
	public function __construct(private Main $main, private Block $block) {
	}

	/**
	 * @return void
	 */
	public function onRun(): void {
		$this->main->removeBlockData($this->block);
	}
}