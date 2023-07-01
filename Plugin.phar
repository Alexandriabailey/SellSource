// Rename plugin config file Sellsource
// Rename the plugin sellsource // SaleSorcerer Plugin by alexbailey

// Import necessary modules
const ui = require('ui');
const items = require('items');
const fs = require('fs');
const path = require('path');

// Define the path to the plugin data folder
const dataFolderPath = path.join(__dirname, 'data');

// Create the plugin data folder if it doesn't exist
if (!fs.existsSync(dataFolderPath)) {
  fs.mkdirSync(dataFolderPath);
}

// Define the path to the config file
const configPath = path.join(dataFolderPath, 'sellsource.json');

// Check if the config file exists, otherwise create it with default values
if (!fs.existsSync(configPath)) {
  const defaultConfig = {
    diamond_block: 500,
    emerald_block: 400,
    gold_block: 300,
    iron_block: 200
  };

  fs.writeFileSync(configPath, JSON.stringify(defaultConfig, null, 2), 'utf8');
}

// Read the config file and parse its contents
const config = JSON.parse(fs.readFileSync(configPath, 'utf8'));

// Hook into the /sell command
command('sell', (player, args) => {
  // Check if the player has a Sell Wand (stick)
  if (player.getInventory().getItemInHand().getType() === items.stick()) {
    // Get the clicked block
    const block = player.getTargetBlock();

    // Get the block type
    const blockType = block.getType();

    // Check if the block has a sell price defined in the config
    if (config[blockType]) {
      const sellPrice = config[blockType];

      // Take the block from the player's inventory
      player.getInventory().removeItem(block.getType());

      // Show a UI popup to the player confirming the sale
      const popup = new ui.Popup();
      popup.setTitle('SaleSorcerer');
      popup.setContent(`You sold ${block.getType()} for $${sellPrice}`);
      popup.open(player);
    } else {
      player.sendMessage('This item cannot be sold.');
    }
  } else {
    player.sendMessage('You need a Sell Wand (stick) to use this command!');
  }
});
