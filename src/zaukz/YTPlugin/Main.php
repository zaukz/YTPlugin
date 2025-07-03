<?php

namespace zaukz\YTPlugin;

use pocketmine\plugin\PluginBase;
use pocketmine\command\Command;
use pocketmine\command\CommandSender;
use pocketmine\player\Player;
use pocketmine\utils\TextFormat;
use pocketmine\Server;

class Main extends PluginBase {

    private array $cooldowns = [];

    public function onEnable(): void {
        $this->getLogger()->info("[YTPlugin] Plugin enabled!");
    }
    public function onDisable(): void {
        $this->getLogger()->info("[YTPlugin] Plugin disabled!");
    }

    public function onCommand(CommandSender $sender, Command $command, string $label, array $args): bool {
        switch ($command->getName()) {
            case "testcommand":
                if ($sender instanceof Player) {
                    $sender->sendMessage(TextFormat::AQUA . 'Hello, this is the test command!');
                } else {
                    $sender->sendMessage('You must be in-game to use this command!');
                }
                return true;
            
            case "track":
                if (!$sender instanceof Player) {
                    $sender->sendMessage('You must be in-game to use this command!');
                    return true;
                }
                if (count($args) !== 1) {
                    $sender->sendMessage('You must provide a player to track!'); // /track <player> - player = 2nd arg, track cmd = 1st arg!
                    return true;
                }
                $target = Server::getInstance()->getPlayerByPrefix($args[0]); // index 0 = 1, index 1 = 2!
                if (!$target || !$target->isOnline()) {
                    $sender->sendMessage('Please check the player is online!');
                    return true;
                }

                $name = strtolower($sender->getName()); // always puts player name in lowercase!
                $now = time(); // time of cmd run!

                // Add cooldown to commands!
                if (isset($this->cooldowns[$name]) && $this->cooldowns[$name] > $now) {
                    $remaining = $this->cooldowns[$name] - $now;
                    $sender->sendMessage('This command is on cooldown for: ' . $remaining . ' seconds!');
                    return true;
                }

                // Now that all the checks have been made, let's make the command functionality!
                $pos = $target->getPosition();
                $sender->sendMessage(TextFormat::AQUA . 'The player you are tracking is: ' . TextFormat::GREEN . $target->getName() . TextFormat::AQUA . '!');
                $sender->sendMessage(TextFormat::AQUA . 'The player is in the world: ' . TextFormat::GREEN . $pos->getWorld()->getDisplayName());
                $sender->sendMessage(TextFormat::AQUA . 'The player coordinates are: ' . TextFormat::GREEN . 'X:' . round($pos->getX()) . 'Y:' . round($pos->getY()) . 'Z:' . round($pos->getZ()));

                // to make a cooldown activate when a command runs successfully, you must add it after!
                $this->cooldowns[$name] = $now + 10; // sets the cooldown to 10 seconds!
                return true;
        }
        return false;
    }
}
