<?php

/**
 * Author: DaRealAqua
 * Date: February 13, 2024
 */

namespace darealaqua\buildreset;

use darealaqua\buildreset\task\BlockTask;
use pocketmine\event\block\BlockPlaceEvent;
use pocketmine\event\Listener;

class Eventlistener implements Listener
{

    /**
     * @param Main $main
     */
    public function __construct(private Main $main)
    {
    }

    /**
     * @priority NORMAL
     * @param BlockPlaceEvent $event
     * @return void
     */
    public function onBlockPlace(BlockPlaceEvent $event): void
    {
        if ($event->isCancelled()) {
            return;
        }
        $cfg = $this->main->getConfig();
        foreach ($event->getTransaction()->getBlocks() as [$x, $y, $z, $block]) {
            $world = $block->getPosition()->getWorld();
            if (in_array($world->getDisplayName(), $cfg->get("enabled_worlds")) || in_array($world->getFolderName(), $cfg->get("enabled_worlds"))) {
                $this->main->addBlockData($block);
                $this->main->getScheduler()->scheduleDelayedTask(new BlockTask($this->main, $block), 20 * $cfg->get("despawn_countdown"));
            }
        }
    }
}
