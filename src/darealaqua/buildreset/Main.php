<?php

/**
 * Author: DaRealAqua
 * Date: April 9, 2023
 */

namespace darealaqua\buildreset;

use darealaqua\buildreset\command\BuildResetCommand;
use pocketmine\block\Block;
use pocketmine\block\VanillaBlocks;
use pocketmine\plugin\PluginBase;
use pocketmine\utils\TextFormat as C;
use pocketmine\world\particle\BlockBreakParticle;

class Main extends PluginBase {

	/** @var array */
	private array $blocks = [];

	/**
	 * @return void
	 */
	public function onEnable(): void {
		$this->saveDefaultConfig();
		$this->getServer()->getPluginManager()->registerEvents(new Eventlistener($this), $this);
		$this->getServer()->getCommandMap()->register($this->getDescription()->getName(), new BuildResetCommand($this), "buildreset");
	}

	/**
	 * @return void
	 */
	public function onDisable(): void {
		if (!empty($this->blocks)) {
			//$this->getLogger()->info(C::YELLOW . count($this->blocks) . C::GRAY . " block(s) has been removed!");
			$this->removeAllBlocksData();
		}
	}

	/**
	 * @return array
	 */
	public function getBlocks(): array{
		return $this->blocks;
	}

	/**
	 * @param Block $block
	 * @return void
	 */
	public function addBlockData(Block $block): void {
		if (isset($this->blocks[$this->getBlockDataFormat($block)])) {
			return;
		}
		$this->blocks[$this->getBlockDataFormat($block)] = $block;
	}

	/**
	 * @param Block $block
	 * @return void
	 */
	public function removeBlockData(Block $block): void {
		if (!isset($this->blocks[$this->getBlockDataFormat($block)])) {
			return;
		}
		$position = $block->getPosition();
		$world = $position->getWorld();
		$cfg = $this->getConfig();
		$world->setBlock($position, VanillaBlocks::AIR());
		if ($cfg->get("despawn_particle") !== false) {
			$world->addParticle($position, new BlockBreakParticle($block));
		}
		unset($this->blocks[$this->getBlockDataFormat($block)]);
	}

	/**
	 * @return void
	 */
	public function removeAllBlocksData(): void {
		foreach ($this->getBlocks() as $block) {
			$this->removeBlockData($block);
		}
	}

	/**
	 * @param Block $block
	 * @return string
	 */
	public function getBlockDataFormat(Block $block): string {
		$pos = $block->getPosition();
		return "$pos->x:$pos->y:$pos->z:{$pos->world->getFolderName()}";
	}
}
