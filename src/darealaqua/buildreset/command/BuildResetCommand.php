<?php

/**
 * Author: DaRealAqua
 * Date: February 13, 2024
 */

namespace darealaqua\buildreset\command;

use darealaqua\buildreset\Main;
use pocketmine\command\Command;
use pocketmine\command\CommandSender;
use pocketmine\plugin\Plugin;
use pocketmine\plugin\PluginOwned;
use pocketmine\utils\TextFormat as C;

class BuildResetCommand extends Command implements PluginOwned
{

    /**
     * @param Main $main
     */
    public function __construct(private Main $main)
    {
        $this->setPermission("buildreset.permission");
        $this->setPermissionMessage(C::RED . "You don't have permisssion to use the command: buildreset");
        $this->setUsage("Usage: /buildreset help");
        $this->setDescription("Edit build reset settings.");
        $this->setAliases(["br"]);
    }

    /**
     * @param CommandSender $sender
     * @param string $commandLabel
     * @param array $args
     * @return void
     */
    public function execute(CommandSender $sender, string $commandLabel, array $args): void
    {
        if (!$sender->hasPermission("buildreset.permission")) {
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
                $message[] = C::GRAY . "Permission: " . C::YELLOW . "'buildreset.permission'";
                $message[] = C::GRAY . "/buildreset worldlist|wl" . C::GRAY . " - Show the list of worlds set for build reset.";
                $message[] = C::GRAY . "/buildreset addworld " . C::GREEN . "<string:name>" . C::GRAY . " - Add a world to the build reset list.";
                $message[] = C::GRAY . "/buildreset removeworld " . C::GREEN . "<string:name>" . C::GRAY . " - Remove a world from the build reset list.";
                $message[] = C::GRAY . "/buildreset setdespawn " . C::GREEN . "<int:seconds>" . C::GRAY . " - Set the time before blocks despawn after placement.";
                $message[] = C::GRAY . "/buildreset setparticle " . C::GREEN . "<bool:true/false>" . C::GRAY . " - Enable or disable particles for block despawn.";


                $sender->sendMessage(implode("\n", $message));
                return;
            case "worldlist":
            case "wl":
                $message[] = C::DARK_GREEN . "Build Reset Worlds:";
                $i = 0;
                foreach ($cfg->get("enabled_worlds") as $world) {
                    $i++;
                    $message[] = C::GREEN . $i . "# " . C::GRAY . $world;
                }
                $sender->sendMessage(implode(C::EOL, $message));
                break;
            case "addworld":
                if (!isset($args[1])) {
                    $sender->sendMessage(C::RED . "Usage: /buildreset addworld " . C::YELLOW . "<string:name>");
                    return;
                }
                if (in_array($args[1], $cfg->get("enabled_worlds"))) {
                    $sender->sendMessage(C::YELLOW . "The world with the name '" . $args[1] . "' is already saved.");
                    return;
                }

                $worlds = $cfg->get("enabled_worlds");
                $worlds[] = $args[1];

                $cfg->set("enabled_worlds", $worlds);
                $cfg->save();

                $sender->sendMessage(C::GRAY . "The world (" . C::YELLOW . $args[1] . C::GRAY . ") has been successfully saved. ");
                break;
            case "removeworld":
                if (!isset($args[1])) {
                    $sender->sendMessage(C::RED . "Usage: /buildreset removeworld " . C::YELLOW . "<string:name>");
                    return;
                }
                if (!in_array($args[1], $cfg->get("enabled_worlds"))) {
                    $sender->sendMessage(C::RED . "The world with the name '" . $args[1] . "' was not found.");
                    return;
                }

                $worlds = $cfg->get("enabled_worlds");
                $world = array_search($args[1], $worlds);
                unset($worlds[$world]);

                $cfg->set("enabled_worlds", array_values($worlds));
                $cfg->save();

                $sender->sendMessage(C::GRAY . "This world (" . C::RED . $args[1] . C::GRAY . ") has been successfully removed.");
                break;
            case "setdespawn":
                if (!isset($args[1])) {
                    $sender->sendMessage(C::RED . "Usage: /buildreset setdespawn " . C::GREEN . "<int:seconds>");
                    return;
                }
                if (!is_numeric($args[1])) {
                    $sender->sendMessage(C::RED . "The build reset despawn countdown can only contain numeric values.");
                    return;
                }

                $cfg->set("despawn_countdown", (int)$args[1]);
                $cfg->save();

                $sender->sendMessage(C::GRAY . "The build reset despawn countdown has been set to " . C::YELLOW . "'" . $args[1] . "'" . C::GRAY . " second(s).");
                break;
            case "setparticle":
                if (!isset($args[1])) {
                    $sender->sendMessage(C::RED . "Usage: /buildreset setparticle " . C::GREEN . "<bool:true/false>");
                    return;
                }
                $value = filter_var($args[1], FILTER_VALIDATE_BOOLEAN, FILTER_NULL_ON_FAILURE);
                if (!is_bool($value)) {
                    $sender->sendMessage(C::RED . "The build reset despawn particle value must be a boolean (true or false).");
                    return;
                }

                $cfg->set("despawn_particle", $value);
                $cfg->save();

                $sender->sendMessage(C::GRAY . "The build reset despawn particle has been set to " . C::YELLOW . "'" . $args[1] . "'" . C::GRAY . ".");
                break;
            default:
                $sender->sendMessage(C::RED . $this->getUsage());
        }
    }

    /**
     * @return Plugin
     */
    public function getOwningPlugin(): Plugin
    {
        return $this->main;
    }
}
