<?php

/**
 * Author: DaRealAqua
 * Date: April 9, 2023
 */

namespace darealaqua\buildreset\command;

use darealaqua\buildreset\Main;
use pocketmine\command\Command;
use pocketmine\command\CommandSender;
use pocketmine\utils\TextFormat as C;

class BuildResetCommand extends Command {

	/**
	 * @param Main $main
	 */
	public function __construct(private Main $main) {
		$this->setPermission("buildreset.permission");
		$this->setPermissionMessage("You don't have permisssion to use the command: buildreset");
		$this->setUsage("Usage: /buildreset help");
		$this->setDescription("Edit build reset settings.");
		$this->setAliases(["br"]);
	}

	/**
	 * @param CommandSender $sender
	 * @param string        $commandLabel
	 * @param array         $args
	 * @return void
	 */
	public function execute(CommandSender $sender, string $commandLabel, array $args): void {
		if (!$sender->hasPermission($this->getPermission())) {
			$sender->sendMessage(C::RED . $this->getPermissionMessage());
			return;
		}
		if (!isset($args[0])) {
			$sender->sendMessage(C::RED . $this->getUsage());
			return;
		}
		$cfg = $this->main->getConfig();
		switch ($args[0]) {
		case "help":
			$message[] = C::DARK_GREEN . "Build Reset Command Help (1/1)" . C::EOL;
			$message[] = C::GRAY . "Permission: " . C::YELLOW . "'" . $this->getPermission() . "'";
			$message[] = C::GRAY . "/buildreset addworld " . C::GREEN . "<string:name>";
			$message[] = C::GRAY . "/buildreset removeworld " . C::GREEN . "<string:name>";
			$message[] = C::GRAY . "/buildreset setdespawn " . C::GREEN . "<int:seconds>";
			$message[] = C::GRAY . "/buildreset setparticle " . C::GREEN . "<bool:true/false>";
			$sender->sendMessage(implode("\n", $message));
			return;
		case "addworld":
			if (!isset($args[1])) {
				$sender->sendMessage(C::RED . "/buildreset addworld " . C::YELLOW . "<string:name>");
				return;
			}
			if (in_array($args[1], $cfg->get("worlds"))) {
				$sender->sendMessage(C::YELLOW . "World with the name '" . $args[1] . "' is already saved!");
				return;
			}

			$worlds = $cfg->get("worlds");
			$worlds[] = $args[1];

			$cfg->set("worlds", $worlds);
			$cfg->save();

			$sender->sendMessage(C::GRAY . "This world (" . C::YELLOW . $args[1] . C::GRAY . ") has been successfully saved.");
			break;
		case "removeworld":
			if (!isset($args[1])) {
				$sender->sendMessage(C::RED . "/buildreset removeworld " . C::YELLOW . "<string:name>");
				return;
			}
			if (!in_array($args[1], $cfg->get("worlds"))) {
				$sender->sendMessage(C::RED . "World with the name '" . $args[1] . "' doesn't exist!");
				return;
			}

			$worlds = $cfg->get("worlds");
			$world = array_search($args[1], $worlds);
			unset($worlds[$world]);

			$cfg->set("worlds", array_values($worlds));
			$cfg->save();

			$sender->sendMessage(C::GRAY . "This world (" . C::RED . $args[1] . C::GRAY . ") has been successfully removed.");
			break;
		case "setdespawn":
			if (!isset($args[1])) {
				$sender->sendMessage(C::RED . "/buildreset setdespawn " . C::GREEN . "<int:seconds>");
				return;
			}
			if (!is_numeric($args[1])) {
				$sender->sendMessage(C::RED . "Build reset despawn countdown must contain only numbers!");
				return;
			}

			$cfg->set("despawn_countdown", (int) $args[1]);
			$cfg->save();

			$sender->sendMessage(C::GRAY . "Build reset despawn countdown has been set to " . C::YELLOW . "'" . $args[1] . "'" . C::GRAY . " second(s).");
			break;
		case "setparticle":
			if (!isset($args[1])) {
				$sender->sendMessage(C::RED . "/buildreset setparticle " . C::GREEN . "<bool:true/false>");
				return;
			}
			$value = filter_var($args[1], FILTER_VALIDATE_BOOLEAN, FILTER_NULL_ON_FAILURE);
			if (!is_bool($value)) {
				$sender->sendMessage(C::RED . "Build reset despawn particle must be bool e.g " . C::YELLOW . "<true|false>" . C::GRAY . ".");
				return;
			}

			$cfg->set("despawn_particle", $value);
			$cfg->save();

			$sender->sendMessage(C::GRAY . "BuildReset despawn particle has been set to " . C::YELLOW . "'" . $args[1] . "'" . C::GRAY . ".");
			break;
		default:
			$sender->sendMessage(C::RED . $this->getUsage());
		}
	}
}