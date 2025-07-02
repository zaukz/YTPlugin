<?php

namespace zaukz\YTPlugin;

use pocketmine\plugin\PluginBase;
use pocketmine\command\Command;
use pocketmine\command\CommandSender;
use pocketmine\player\Player;
use pocketmine\utils\TextFormat;

class Main extends PluginBase {
    public function onEnable(): void {
        $this->getLogger()->info("[YTPlugin] Plugin enabled!");
    }
    public function onDisable(): void {
        $this->getLogger()->info("[YTPlugin] Plugin disabled!");
    }

    public function onCommand(CommandSender $sender, Command $command, string $label, array $args): bool {
        if ($command->getName() === "testcommand") {
            if ($sender instanceof Player) {
                $sender->sendMessage(TextFormat::AQUA . 'Hello, this is the test command!');
            } else {
                $sender->sendMessage('You must be in-game to use this command!');
            }
            return true;
        }
        return false;
    }
}
