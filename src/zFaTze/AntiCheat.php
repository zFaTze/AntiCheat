<?php

namespace zFaTze;

// Plugin
use pocketmine\plugin\PluginBase;

// Events

use pocketmine\event\Listener;

use pocketmine\event\player\PlayerJoinEvent;
use pocketmine\event\player\PlayerMoveEvent;
use pocketmine\event\player\PlayerToggleFlightEvent;
use pocketmine\event\player\PlayerGameModeChangeEvent;

use pocketmine\event\server\DataPacketReceiveEvent;

// Default

use pocketmine\Server;
use pocketmine\Player;

// Command

use pocketmine\command\Command;
use pocketmine\command\CommandSender;

// Utils

use pocketmine\utils\Config;
use pocketmine\utils\TextFormat as Color;

class AntiCheat extends PluginBase implements Listener {
	
	const FUNCTION_ERROR = "§cError §7|§r ";
	const CLIENT_PREFIX = "§bAnti§6Cheat §7|§r ";
	
	
	
	public function onEnable(){

    $this->getLogger()->info("SimpleCheat started");
    
    $this->getServer()->getPluginManager()->registerEvents($this, $this);
   
     
    }
    
    public function getPrefix(){
      return self::CLIENT_PREFIX;
    }
    
     
     // Check if use normal Original MCBE or come from a Proxy Server or use Mods
		public function onReceive(DataPacketReceiveEvent $event) {
         $player = $event->getPlayer();
        $packet = $event->getPacket();
        if ($packet instanceof LoginPacket) {
            if ($packet->serverAddress === "mcpeproxy.tk") {
                $player->kick(Color::RED . "Du wurdest vom AntiCheat gekickt!\n" . Color::YELLOW . "Grund: " . Color::WHITE . "Proxy\n", false);
            }
            if ($packet->clientId === 0) {
                $player->kick(Color::RED . "Du wurdest vom AntiCheat gekickt!\n" . Color::YELLOW . "Grund: " . Color::WHITE . "ModClient\n", false);
            }
          
        }
       }
    
    // Check if player has Permission  to fly
   public function onFlying(PlayerToggleFlightEvent $event) {
    
      $player = $event->getPlayer();
   
       if ($event->isFlying()) {
     	
       	if ($player->hasPermission("anticheat.bypass.fly")) {
     	    return;
         } else {
           $player->kick(Color::RED . "Du wurdest vom AntiCheat gekickt!\n" . Color::YELLOW . "Grund: " . Color::WHITE . "Fly\n", false);
           
           
         
         }
     }
   }
   
   // Check Player have a high Ping
   
   public function onMove(PlayerMoveEvent $event) {
   
     $player = $event->getPlayer();

        if ($player->getPing() >= 150) {
                $event->setCancelled(true);
                $player->sendMessage($this->getPrefix() . Color::RED . "You Ping is to high!");
    }
  
  }
  
   public function onGameModeChange(PlayerGameModeChangeEvent $event) {
    	
       	$player = $event->getPlayer();
        $gamemode = $event->getNewGameMode();
        if ($player->hasPermission("anticheat.bypass.gamemode")) {
        	
        } else {
        	
         if ($gamemode === 1) {
        	    
        	    $player->kick(
                Color::RED . "You have been kicked!\n" .
                Color::YELLOW . "Reason: " . Color::WHITE . "Force Gamemode\n", false
                );
                
            }
        	
        }
    	
    }

   }
