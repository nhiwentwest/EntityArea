<?php

namespace nhiwentwest\EntityArea\Custom;

use pocketmine\plugin\PluginBase;
use pocketmine\entity\Entity;
use pocketmine\world\World;
use pocketmine\entity\Living;
use pocketmine\event\entity\EntityDamageEvent;
use pocketmine\event\entity\EntityDamageByEntityEvent;
use pocketmine\event\entity\EntitySpawnEvent;
use pocketmine\math\Vector3;
use pocketmine\math\AxisAlignedBB;
use pocketmine\entity\Location;
use pocketmine\level\Position;
use pocketmine\item\Item;
use pocketmine\item\VanillaItems;
use pocketmine\inventory\Inventory;
use pocketmine\block\inventory\DoubleChestInventory;

use pocketmine\scheduler\Task;
use pocketmine\Server;
use pocketmine\Player;
use pocketmine\event\player\PlayerJoinEvent;


use pocketmine\block\BlockTypeIds;
use nhiwentwest\EntityArea\Main;


use nhiwentwest\EntityArea\Custom\MobsEntity;
use nhiwentwest\EntityArea\Custom\Motion;
use nhiwentwest\EntityArea\Custom\Listen;




class Spawn {



    public static $damage = 4;
    public static $health = 20;
    public static $speed = 1;
    public static $areaname;
    private $count = 0;

    private $processedEntityIds = [];


    public function countEntity() : void {

        $worldManager = Server::getInstance()->getWorldManager();

        $main = Main::$instance;

        // Lặp qua tất cả các $areaname trong cấu hình
        foreach (Main::$instance->myConfig->getAll() as $areaname => $data) {
            // Kiểm tra xem dữ liệu có đúng định dạng không
            if (!isset($data["pos1"]) || !isset($data["pos2"]) || !isset($data["entity"]) || !isset($data["world"]) || !isset($data["damage"]) || !isset($data["health"]) || !isset($data["speed"]) || !isset($data["number"]) || !isset($data["armor"])) {
                
                $main->getLogger()->warning("Error config. Skipped.");

                continue;
            }


            $mobname = $data["entity"];
            $worldname = $data["world"];

            Spawn::$damage = $data["damage"];
            Spawn::$health = $data["health"];
            Spawn::$speed = (float) $data["speed"];
            Spawn::$areaname = $areaname;


            $number = (int) $data["number"];

            $pos1Data = $data["pos1"];
            $pos2Data = $data["pos2"];

            $world = $worldManager->getWorldByName($worldname);
            Server::getInstance()->getWorldManager()->loadWorld($worldname);


            $pos1 = new Vector3($pos1Data[0], $pos1Data[1], $pos1Data[2]);
            $pos2 = new Vector3($pos2Data[0], $pos2Data[1], $pos2Data[2]);


            $x1 = (float) $pos1->getX();
            $y1 = (float) $pos1->getY();
            $z1 = (float) $pos1->getZ();

            $x2 = (float) $pos2->getX();
            $y2 = (float) $pos2->getY();
            $z2 = (float) $pos2->getZ();
            
            $minx = min($x1 + 1, $x2 + 1);
            $maxx = max($x1 - 1, $x2 - 1);
            
            $miny = min($y1, $y2);
            $maxy = min($y1 + 2, $y2 + 2);
            
            $minz = min($z1 + 1, $z2 + 1);
            $maxz = max($z1 - 1, $z2 - 1);
            
            if (($minx >= $maxx) ||  ($miny >= $maxy)  || ($minz >= $maxz))  {
                $main->getLogger()->warning("Your pos in '" . $areaname . "' is inconsontant. Failed to spawn mobs.");
                
                
                break;
            }
            
            


            
            

            $yaw = 0.0;
            $pitch = 0.0;
            
            $armor = $data["armor"];
            
            $helmet = $armor[0];

            $chestplate = $armor[1];

            
            $legs = $armor[2];

            
            $boots = $armor[3];
            

            
            $methods = [];

            if ($helmet !== "none") {
                $methods['setHelmet'] = VanillaItems::$helmet();
            }

            if ($chestplate !== "none") {
                $methods['setChestplate'] = VanillaItems::$chestplate();
            }

            if ($legs !== "none") {
                $methods['setLeggings'] = VanillaItems::$legs();
            }

            if ($boots !== "none") {
                $methods['setBoots'] = VanillaItems::$boots();
            }





            $mobnn = $main->classes[$mobname];
            $count = 0;
            while ($count < $number) {


                $count++;


                


                $x = mt_rand(intval(min($x1 + 1, $x2 + 1)), intval(max($x1 - 1, $x2 - 1)));
                $z = mt_rand(intval(min($z1 + 1, $z2 + 1)), intval(max($z1 - 1, $z2 - 1)));

                $y = mt_rand(intval(min($y1, $y2)), intval(min($y1 + 2, $y2 + 2)));





                $blockAtPosition = $world->getBlockAt($x, $y, $z);







                $x = (float) $x;
                $y = (float) $y;
                $z = (float) $z;





                $pos = new Vector3($x, $y, $z);


                $location = new Location($x, $y, $z, $world, $yaw, $pitch);



                $entity = new $mobnn($location);





                $armorInventory = $entity->getArmorInventory();

                foreach ($methods as $method => $item) {

                    $armorInventory->$method($item);

                }
                

                if ($entity->isInsideOfSolid()) {
                    $main->getLogger()->info("isInsideOfSolid");
                    $entity->kill();
                }



                $entity->spawnToAll();
               

            }
            


        }
    }
    

    public function spawnMob(string $areaname): void {
        $main = Main::$instance;
        $configData = Main::$instance->myConfig->getAll();


        if (!isset($configData[$areaname])) {
            Main::$instance->getLogger()->warning("Area '{$areaname}' not found in config.");
            return;
        }



        $data = $configData[$areaname];


        if (!isset($data["pos1"], $data["pos2"], $data["entity"], $data["world"], $data["damage"], $data["health"], $data["speed"], $data["armor"])) {
            $this->getLogger()->warning("Invalid data format for area '{$areaname}' in config.");
            return;
        }

        
        $mobname = $data["entity"];
        $worldname = $data["world"];
        
        Spawn::$damage = $data["damage"];
        Spawn::$health = $data["health"];
        
        
        $number = (int) $data["number"];





        $pos1Data = $data["pos1"];
        $pos2Data = $data["pos2"];
        $worldManager = Server::getInstance()->getWorldManager();
        $world = $worldManager->getWorldByName($worldname);


        $pos1 = new Vector3($pos1Data[0], $pos1Data[1], $pos1Data[2]);
        $pos2 = new Vector3($pos2Data[0], $pos2Data[1], $pos2Data[2]);


        $x1 = (float) $pos1->getX();
        $y1 = (float) $pos1->getY();
        $z1 = (float) $pos1->getZ();

        $x2 = (float) $pos2->getX();
        $y2 = (float) $pos2->getY();
        $z2 = (float) $pos2->getZ();

        $minx = min($x1 + 1, $x2 + 1);
        $maxx = max($x1 - 1, $x2 - 1);

        $miny = min($y1, $y2);
        $maxy = min($y1 + 2, $y2 + 2);

        $minz = min($z1 + 1, $z2 + 1);
        $maxz = max($z1 - 1, $z2 - 1);





        $yaw = 0.0;
        $pitch = 0.0;

        $armor = $data["armor"];

        $helmet = $armor[0];

        $chestplate = $armor[1];


        $legs = $armor[2];


        $boots = $armor[3];



        $methods = [];

        if ($helmet !== 'none') {
            $methods['setHelmet'] = VanillaItems::$helmet();
        }

        if ($chestplate !== 'none') {
            $methods['setChestplate'] = VanillaItems::$chestplate();
        }

        if ($legs !== 'none') {
            $methods['setLeggings'] = VanillaItems::$legs();
        }

        if ($boots !== 'none') {
            $methods['setBoots'] = VanillaItems::$boots();
        }






        $mobnn = $main->classes[$mobname];
        $count = 0;
        while ($count < $number) {

            
            $count++;


            $x = mt_rand(intval(min($x1 + 1, $x2 + 1)), intval(max($x1 - 1, $x2 - 1)));
            $z = mt_rand(intval(min($z1 + 1, $z2 + 1)), intval(max($z1 - 1, $z2 - 1)));

            $y = mt_rand(intval(min($y1, $y2)), intval(min($y1 + 2, $y2 + 2)));





            $blockAtPosition = $world->getBlockAt($x, $y, $z);

            if ($blockAtPosition->getTypeId() !== BlockTypeIds::AIR) {
                $y++;
            }





            $x = (float) $x;
            $y = (float) $y;
            $z = (float) $z;




            $pos = new Vector3($x, $y, $z);


            $location = new Location($x, $y, $z, $world, $yaw, $pitch);



            $entity = new $mobnn($location);





            $armorInventory = $entity->getArmorInventory();

            foreach ($methods as $method => $item) {
                
                $armorInventory->$method($item);

            }


            if ($entity->isInsideOfSolid()) {
                $main->getLogger()->info("isInsideOfSolid");
                $entity->kill();
            }


            $entity->spawnToAll();


        }



    }

    
    
    public function deSpawnMobs() {
        foreach (Main::$instance->getServer()->getWorldManager()->getWorlds() as $world) {
            foreach ($world->getEntities() as $entity) {
                if (!method_exists($entity, "getName")) {
                    continue;
                }

                if ($entity instanceof Player) {
                    continue;
                }

                if (!$entity instanceof MobsEntity) {
                    continue;
                }

                $near = false;
                $worldname = $world->getFolderName();
                $mobname = $entity->getName();
                $block = $entity->getWorld()->getBlock($entity->getPosition()->subtract(0, 1, 0));
                $swimming = Main::$instance->attrobj->isSwimming($mobname);


                if ($swimming == false and ($block instanceof Water or $entity->isUnderwater())) {
                    # this entity should not be in the water
                    $entity->setNameTag("Not allowed in water");
                    $entity->kill();
                    continue;
                }

                if (count($world->getPlayers()) < 1) {
                    # there are no players in this world
                    $entity->setNameTag("No players in $worldname");
                    $entity->kill();
                    continue;
                }

                foreach ($world->getPlayers() as $p) {
                    foreach ($p->getWorld()->getNearbyEntities($p->getBoundingBox()->expandedCopy(100, 100, 100)) as $e) {
                        if ($e->getId() === $entity->getId()) {
                            $near = true;
                        }
                    }
                }
                if ($near == false) {
                    # no players are near this entity
                    $entity->setNameTag("No nearby players");
                    $entity->kill();
                    continue;
                }
            }
        }
    }
}
