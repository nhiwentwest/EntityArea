<?php

declare(strict_types=1);

namespace nhiwentwest\EntityArea;

use pmmp\TesterPlugin\TestFailedException;
use pocketmine\player\Player;
use pocketmine\Server;

use pocketmine\entity\Entity;
use pocketmine\event\Listener;

use pocketmine\event\CancellableTrait;
use pocketmine\plugin\PluginBase;
use pocketmine\utils\Config;
use pocketmine\utils\TextFormat;
use pocketmine\event\block\BlockBreakEvent;
use InvalidArgumentException;

use pocketmine\world\World;

use pocketmine\permission\DefaultPermissions;
use pocketmine\nbt\tag\CompoundTag;
use pocketmine\math\Vector3;
use pocketmine\entity\Location;

use pocketmine\item\Item;
use pocketmine\item\VanillaItems;

use pocketmine\command\Command;
use pocketmine\command\CommandSender;
use pocketmine\event\WorldEvent;

use nhiwentwest\EntityArea\Tgwaste\Spawn;
use nhiwentwest\EntityArea\Tgwaste\Attributes;
use nhiwentwest\EntityArea\Tgwaste\Tools;
use nhiwentwest\EntityArea\Tgwaste\Registrations;
use nhiwentwest\EntityArea\Tgwaste\Coords;
use nhiwentwest\EntityArea\Tgwaste\Motion;
use nhiwentwest\EntityArea\Tgwaste\Listen;

class Main extends PluginBase implements Listener {
    
    private $registrationsInstance;
    
    public $myConfig;
    public static $instance;
    private $pos1flag = false;
    private $pos2flag = false;
    private $areaName = null;
    private $pos1co = null;
    private $pos2co = null;
    private $sender = null;
    private $world1;
    private $world2;
    public $classes;
    public $toolsobj;
    public $spawnobj;
    
    public $attrobj;
    public $nospawn;
    public $regainhealth;
    public $coordsobj;
    
	
    public function onEnable(): void {
        self::$instance = $this;
  
        $this->attrobj = (new Attributes);
        $this->toolsobj = (new Tools);
        $this->spawnobj = (new Spawn);
        $this->coordsobj = (new Coords);
        $registrations = new Registrations();
        
        $this->saveDefaultConfig();
        $this->getServer()->getPluginManager()->registerEvents($this, $this);
        $this->getServer()->getPluginManager()->registerEvents(new Listen(), $this);
        
        @mkdir($this->getDataFolder());
        $this->saveResource("config.yml");
        $this->myConfig = new Config($this->getDataFolder() . "config.yml", Config::YAML);
        $this->nospawn = $this->getConfig()->get("nospawn");
        $this->regainhealth = $this->getConfig()->get("regainhealth");
        (new Registrations)->registerEntities();
        

    
        }

    
    public function createArea(string $areaName): void {
   
        $areaName = (string) $this->areaName;

 
        
        
        if ($this->pos1co !== null && $this->pos2co !== null) {
            if ($this->world1 == $this->world2)  {
                
            $world = (string) $this->world1;
                
   
 
            $data = [
                'pos1' => [$this->pos1co->x, $this->pos1co->y, $this->pos1co->z],
                'pos2' => [$this->pos2co->x, $this->pos2co->y, $this->pos2co->z],
                'world' => $world,
                'entity' => 'Zombie',
                'damage' => 4,
                'health' => 20,
                'speed' => 1.00,
                'number' => 3,
                'armor' => ['none', 'none', 'none', 'none']
            ];
           

         
            $config = new Config($this->getDataFolder() . "config.yml", Config::YAML);
            $config->set($areaName, $data);
            $config->save();
            $config->reload();
            
           $this->sender->sendMessage(TextFormat::AQUA . "Customize the area in the config to suit your preferences!");

        }
            else {
                $this->sender->sendMessage(TextFormat::RED . "You must create two pos in the same world!");
            }
   }
        
    }



 
    
    public function onBlockBreak(BlockBreakEvent $event): void {

       if ($event->getPlayer() == $this->sender) {
        $areaName = (string) $this->areaName;
        $player = $event->getPlayer();
        

    
        if ($this->pos1flag) {
        $this->world1 = $player->getWorld()->getFolderName();
            
           $blockPosition = $event->getBlock()->getPosition();
            
       
            
            // Send the coordinates to the player
            $event->getPlayer()->sendMessage("Block position: X=" . $blockPosition->getX() . ", Y=" . $blockPosition->getY() . ", Z=" . $blockPosition->getZ());
        $this->pos1co = $blockPosition;
           
            $this->pos1flag = false;
            $this->createArea($areaName);
            $player->sendMessage(TextFormat::GREEN . "Pos1 has been created successfully! \n");
            $event->cancel();
        
        }
        
        
        if ($this->pos2flag) {
            $this->world2 = $player->getWorld()->getFolderName();
            $areaName = (string) $this->areaName;
           $blockPosition = $event->getBlock()->getPosition();
            
       
            $player = $event->getPlayer();
            // Send the coordinates to the player
            $event->getPlayer()->sendMessage("Block position: X=" . $blockPosition->getX() . ", Y=" . $blockPosition->getY() . ", Z=" . $blockPosition->getZ());
         $this->pos2co = $blockPosition;
           
            $this->pos2flag = false;
            $this->createArea($areaName);
            $player->sendMessage(TextFormat::GREEN . "Pos2 has been created successfully! \n");
            $event->cancel();
        }
      
     }
    }
    
    
    public function onCommand(CommandSender $sender, Command $command, string $label, array $args): bool {
        if ($label === "enti") {
            if (!isset($args[0])) {
                // Handle case when no arguments are provided
                return false;
            }

            if ($args[0] === "create" && isset($args[1])) {
                
                $config = new Config($this->getDataFolder() . "config.yml", Config::YAML);
                  if ($config->exists($args[1])) {
                      $sender->sendMessage(TextFormat::RED . "The area '{$this->areaName}' already exists. You can motify it in config.");
                  
                  }
                
                else {
                
        
                $this->sender = $sender;
              
                $this->areaName = (string) $args[1];
                
                
                $sender->sendMessage("You have created " . $this->areaName . "\n");
                $sender->sendMessage("Please use /enti pos1 or pos2 to continue");
                return true;
                    }
            }

            elseif ($args[0] === "pos1") {
                 
                if ($sender instanceof Player) {
         
                    
                    if ($this->areaName !== null) {
                          
                    $sender->sendMessage("Please break a block");
                    $this->pos1flag = true;
              
                          }
                    else {
                        $sender->sendMessage("You must /enti create <name> first!");
    
                    }
                    return true;
                    }
        
                    $this->getLogger()->info("Please run command as a player.");
                
        

    return false;
                
         

}
 elseif ($args[0] === "pos2") {
     if ($sender instanceof Player) {
     
         if ($this->areaName !== null) {
             
         
         $sender->sendMessage("Please break a block");
         $this->pos2flag = true;
                  }
         
         else {
             $sender->sendMessage(TextFormat::RED . "You must /enti create <name> first!");
         }
         return true;
               }
     
     $this->getLogger()->info("Please run command as a player.");

                return true;
            }   elseif ($args[0] === "remove") {
                if (isset($args[1])) {
                    $areaNameToRemove = strtolower($args[1]);

                    $config = new Config($this->getDataFolder() . "config.yml", Config::YAML);

                    if ($config->exists($areaNameToRemove)) {
                        $config->remove($areaNameToRemove);
                        $config->save();
                        $sender->sendMessage("$areaNameToRemove has been removed sucessfully.");
                    } else {
                        $sender->sendMessage(TextFormat::RED . "$areaNameToRemove has not exist.");
                    }
                    return true;
                } else {
                    $sender->sendMessage(TextFormat::RED . "/enti remove <name>");
                    return false;
                }
            }
		

            
            $this->getLogger()->info("/enti create|remove <name>");
            return true;
        }
     
        
    if ($label === "clearmobs") {

            if ($sender instanceof Player) {

           foreach (Main::$instance->getServer()->getWorldManager()->getWorlds() as $world) {
               foreach ($world->getEntities() as $entity) {


                   if ($entity instanceof Player) {
                       continue;
                   }

                   if ($entity instanceof MobsEntity) {

                       $entity->kill();
                   }
               }
           }
                }
            else {
                Main::$instance->getLogger()->info("Please run command as a player.");
            }

                       return true;
                   }


        return false;
        
    }


    
}


