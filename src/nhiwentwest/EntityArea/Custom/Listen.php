<?php

declare(strict_types=1);

namespace nhiwentwest\EntityArea\Tgwaste;




use pocketmine\entity\Entity;
use pocketmine\nbt\tag\CompoundTag;
use pocketmine\event\Listener;
use pocketmine\Server;
use pocketmine\math\Vector3;
use pocketmine\math\AxisAlignedBB;
use pocketmine\entity\Location;
use pocketmine\world\World;
use pocketmine\event\entity\EntityDamageByEntityEvent;
use pocketmine\event\entity\EntityDamageEvent;
use pocketmine\event\entity\EntityDespawnEvent;
use pocketmine\event\entity\EntitySpawnEvent;
use pocketmine\event\entity\EntityDeathEvent;
use pocketmine\player\Player;

use nhiwentwest\EntityArea\Entities\MobsEntity;

use nhiwentwest\EntityArea\Tgwaste\Spawn;
use nhiwentwest\EntityArea\Main;

class Listen implements Listener {
    
    public bool $isthisfirst = true;

    public function onPlayerJoin(PlayerJoinEvent $event): void {
     if ($this->isthisfirst) {
         Main::$instance->spawnobj->countEntity();
         $this->isthisfirst = false;
         }
      }
	
	public function onEntityDamageByEntityEvent(EntityDamageByEntityEvent $event) {
		$entity = $event->getEntity();
	}

	public function onEntityDamageEvent(EntityDamageEvent $event) {
		$entity = $event->getEntity();
	}


	public function onEntitySpawnEvent(EntitySpawnEvent $event) {
        $main = Main::$instance;
		$entity = $event->getEntity();
	}
    

    public function onEntityDeath(EntityDeathEvent $event): void {

        $killedEntity = $event->getEntity();
        $cause = $killedEntity->getLastDamageCause();

           if ($cause instanceof EntityDamageByEntityEvent) {
               $damager = $cause->getDamager();


               if ($damager instanceof Entity) {




                   $areaname = $killedEntity->getNameTag();
                   $spawn = new Spawn();
                   $spawn->spawnMob($areaname);
}
               }
    }

  
    private function isPositionInBoundBox(Vector3 $position, Vector3 $pos1, Vector3 $pos2, Entity $entity): bool {
        $minX = min($pos1->x, $pos2->x);
        $maxX = max($pos1->x, $pos2->x);
        $minY = min($pos1->y, $pos2->y);
        $maxY = max($pos1->y, $pos2->y);
        $minZ = min($pos1->z, $pos2->z);
        $maxZ = max($pos1->z, $pos2->z);

        $boundingBox = new AxisAlignedBB($minX, $minY, $minZ, $maxX, $maxY, $maxZ);
        
   
        
        if ($entity->getBoundingBox()->intersectsWith($boundingBox)){
           
            return true;
            }
        

        return false;
    }    
}
