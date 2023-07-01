<?php

namespace YourPluginNamespace;

use pocketmine\Player;
use pocketmine\plugin\PluginBase;
use pocketmine\command\Command;
use pocketmine\command\CommandSender;
use onebone\economyapi\EconomyAPI;

class SellPlugin extends PluginBase {

    public function onEnable() {
        // You can add any other code you need to run when the plugin is enabled
    }

    public function onCommand(CommandSender $sender, Command $command, string $label, array $args): bool {
        if (!$sender instanceof Player) {
            $sender->sendMessage("This command can only be used in-game.");
            return true;
        }
        
        if (count($args) !== 1) {
            $sender->sendMessage("Usage: /sell <amount>");
            return true;
        }
        
        $amount = (float) $args[0];
        $itemName = "your_item_name"; // Replace with the actual name of the item you want to sell
        $sellPrice = 10; // Replace with the sell price for the item
        
        $economy = EconomyAPI::getInstance();
        
        if ($economy->reduceMoney($sender, $amount * $sellPrice)) {
            // Sell successful
            $sender->sendMessage("Sold " . $amount . " " . $itemName . " for " . ($amount * $sellPrice) . " coins.");
        } else {
            // Insufficient funds
            $sender->sendMessage("Insufficient funds.");
        }
        
        return true;
    }
}
