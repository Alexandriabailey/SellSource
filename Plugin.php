<?php

namespace SellSource;

use pocketmine\Player;
use pocketmine\plugin\PluginBase;
use pocketmine\command\Command;
use pocketmine\command\CommandSender;
use pocketmine\item\Item;
use pocketmine\Server;

class SellPlugin extends PluginBase {

    public function onEnable() {
        $economyPlugin = $this->getEconomyAPIPlugin();

        if ($economyPlugin === null) {
            $this->getLogger()->error("This plugin requires EconomyAPI-PM5_dev-1.");
            $this->getServer()->getPluginManager()->disablePlugin($this);
        }
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
        
        $economy = $this->getEconomyAPIPlugin();

        if ($economy === null) {
            $sender->sendMessage("This plugin requires EconomyAPI-PM5_dev-1.");
            return true;
        }
        
        $amount = (int) $args[0];
        $item = $sender->getInventory()->getItemInHand();
        $itemName = $item->getName();
        $sellPrice = $item->getSellPrice(); // Assuming you have implemented this method to retrieve the item's sell price
        
        if ($sellPrice === 0) {
            $sender->sendMessage("This item cannot be sold.");
            return true;
        }
        
        if ($amount > $item->getCount()) {
            $amount = $item->getCount();
        }
        
        $totalSellPrice = $amount * $sellPrice;
        
        if ($economy->reduceMoney($sender, $totalSellPrice)) {
            // Sell successful
            $item->setCount($item->getCount() - $amount);
            $sender->getInventory()->setItemInHand($item);
            $economy->addMoney($sender, $totalSellPrice);
            
            $sender->sendMessage("Sold " . $amount . " " . $itemName . " for " . $totalSellPrice . " coins.");
        } else {
            // Insufficient funds
            $sender->sendMessage("Insufficient funds.");
        }
        
        return true;
    }

    private function getEconomyAPIPlugin(): ?PluginBase {
        $economyPlugin = $this->getServer()->getPluginManager()->getPlugin("EconomyAPI");
        
        if ($economyPlugin instanceof PluginBase && $economyPlugin->getDescription()->getVersion() === "PM5_dev-1") {
            return $economyPlugin;
        }
        
        return null;
    }
}
