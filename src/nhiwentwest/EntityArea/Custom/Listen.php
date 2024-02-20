<?php

declare(strict_types=1);

namespace nhiwentwest\EntityArea\Custom;

use pocketmine\entity\Entity;
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

use nhiwentwest\EntityArea\Custom\Spawn;
use nhiwentwest\EntityArea\Main;

class Listen implements Listener {
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
        $entity = $event->getEntity();
        $entityPosition = $entity->getPosition();
        $entityWorld = $entity->getWorld();

        foreach (Main::$instance->myConfig->getAll() as $areaname => $data) {
            // Kiểm tra xem dữ liệu có đúng định dạng không
            if (!isset($data["pos1"]) || !isset($data["pos2"]) || !isset($data["entity"]) || !isset($data["world"]) || !isset($data["damage"]) || !isset($data["health"]) || !isset($data["speed"]) || !isset($data["armor"])) {
                $main->getLogger()->warning("Error config. Skipped.");
                continue; // Bỏ qua dữ liệu không hợp lệ và tiếp tục với dữ liệu tiếp theo
            }

            // Kiểm tra xem toạ độ của entity đã chết có nằm trong boundbox của area không
            $pos1 = new Vector3($data["pos1"][0], $data["pos1"][1], $data["pos1"][2]);
            $pos2 = new Vector3($data["pos2"][0], $data["pos2"][1], $data["pos2"][2]);
            $boundBoxContains = $this->isPositionInBoundBox($entityPosition, $pos1, $pos2, $entity);

            if ($boundBoxContains && $entityWorld->getFolderName() === $data["world"]) {
              $spawn = new Spawn();
              $spawn->spawnMob($areaname);
            }
        }
    }

    // Hàm kiểm tra xem một toạ độ có nằm trong một boundbox cho trước không
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
