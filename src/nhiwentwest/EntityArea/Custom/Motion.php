<?php

declare(strict_types=1);

namespace nhiwentwest\EntityArea\Custom;

use pocketmine\event\entity\EntityDamageByEntityEvent;
use pocketmine\event\entity\EntityDamageEvent;
use pocketmine\math\Vector3;
use pocketmine\player\Player;
use nhiwentwest\EntityArea\Entities\MobsEntity;

use pocketmine\world\World;
use pocketmine\world\Position;
use pocketmine\block\Block;
use pocketmine\block\BlockTypeIds;
use pocketmine\entity\Living;
use nhiwentwest\EntityArea\Custom\Coords;
use nhiwentwest\EntityArea\Custom\Listen;
use nhiwentwest\EntityArea\Custom\Registrations;
use nhiwentwest\EntityArea\Custom\Attributes;



use nhiwentwest\EntityArea\Custom\Tools;



use nhiwentwest\EntityArea\Main;


class Motion {
    public function tick(MobsEntity $entity) {

        $timer = $entity->getTimer() - 1;
        $flying = $entity->isFlying();

        $entity->setTimer($timer);

        if ($timer > 0) {
            return $this->wait($entity);
        }

        if ($timer == 0 and $flying == false and mt_rand(0, 1) == 1 and $entity->getTargetEntity() === null) {
            return $entity->setTimer(mt_rand(100, 600));
        }

        $pos = $entity->getDestination();

        if (!$pos->x and !$pos->y and !$pos->z) {
          $entity->setDestination(Main::$instance->coordsobj->getRandomDestination($entity));
        }

        if ($entity->getTimer() > 0) {
            return;
        }

        $this->move($entity);
    }

    public function move(MobsEntity $entity) {
        $motion = $entity->getMotion();
        $location = $entity->getLocation();
     
        $swimming = $entity->isSwimming();
        $flying = $entity->isFlying();

  
        
        if (!$entity->onGround and $motion->y < 0 and $flying == false and $swimming == false) {
            $motion->y *= 0.6;
        }

        else {
            if (mt_rand(0, 500) == 1 or ($entity->isCollided == true and $swimming == true)) {
                # random chance of getting a new destination
              $entity->setDestination(Main::$instance->coordsobj->getRandomDestination($entity));
            }
            $targetpos = $this->calculateMotion($entity);
            $motion->x = $targetpos->x;
            $motion->y = $targetpos->y;
            $motion->z = $targetpos->z;
            
            
        }

        if ($entity->getTimer() > 0) {
            return;
        }
  
        
 

   
            
           $currentPosition = $entity->getPosition();

                        // Lấy motion được thiết lập
                        $newMotion = clone $motion; // Clone motion để không làm thay đổi motion ban đầu nếu cần sử dụng lại

                        // Thiết lập motion mới (ví dụ: motion->y = 1)
                        $newMotion->x = 0;
                        $newMotion->y = 0;
                        $newMotion->z = 0;

                        // Tính toạ độ mới dựa trên motion mới và toạ độ hiện tại
                        $newX = $currentPosition->getX() + $newMotion->x;
                        $newY = $currentPosition->getY() + $newMotion->y;
                        $newZ = $currentPosition->getZ() + $newMotion->z;

                  
                        $newPosition = new Vector3($newX, $newY, $newZ);
                        
                       $x1 = (int) $newPosition->getX();
                       $y1 = (int) $newPosition->getY();
                        $z1 = (int) $newPosition->getZ();
            $block1 = $entity->getWorld()->getBlockAt($x1 + 1, $y1 + 1, $z1)->getTypeId();
            $block2 = $entity->getWorld()->getBlockAt($x1 + 1, $y1 + 2, $z1)->getTypeId();
            $block3 = $entity->getWorld()->getBlockAt($x1 - 1, $y1 + 1, $z1)->getTypeId();
     
        if ($entity->isCollidedHorizontally == true and $swimming == false) {
                if ($block1 === BlockTypeIds::AIR && $block2 === BlockTypeIds::AIR && $block3 === BlockTypeIds::AIR) {
       
                    $motion->y = 0.42;
                  
                }
         
                
           
          }
            
        
        
          
     
            

        if ($entity->isJumping() == true and $entity->onGround) {
            $motion->y = 1;
        }

   

        $vec = new Vector3($motion->x, $motion->y, $motion->z);
        $look = new Vector3($location->x+$motion->x, $location->y+$motion->y+$entity->getEyeHeight(), $location->z+$motion->z);

        $entity->setDefaultLook($look);

        $entity->lookAt($look);
        $entity->setMotion($vec);
   //     $this->attackEntity($entity, 4);
    }

    public function wait(MobsEntity $entity) {
        $location = $entity->getLocation();

        if ($entity->lastUpdate % 100 == 0) {
            if ($entity->getHealth() < $entity->getMaxHealth()) {
                $entity->setHealth($entity->getHealth() + Main::$instance->regainhealth);
            }
            $entity->damageTag();
        }

        if ($entity->isFlying() == true) {
            return;
        }

        if ($entity->isSwimming() == true) {
            if (!$entity->isUnderwater()) {
                $entity->setTimer(-1);
            }
            return;
        }

        if ($entity->catchesFire() == true and Main::$instance->toolsobj->isDayTime($entity->getWorld())) {
            $entity->setOnFire(120);
            $entity->setTargetEntity($entity);
        }

        if ($entity->isOnFire()) {
            $this->attackEntity($entity, 4);
        }

        if (mt_rand(1, 200) == 1) {
            $entity->lookAt($entity->getDefaultLook());
            return;
        }

        if (mt_rand(1, 200) == 1) {
            $x = $location->x + mt_rand(-1, 1);
            $y = $location->y + mt_rand(-1, 1);
            $z = $location->z + mt_rand(-1, 1);
            $entity->lookAt(new Vector3($x, $y, $z));
        }
    }

    public function calculateMotion(MobsEntity $entity) : Vector3 {
        $dest = $entity->getDestination();
        $epos = $entity->getPosition();
        $motion = $entity->getMotion();
        $speed = $entity->getMovementSpeed();
        $speed = 1.0;
        $flying = $entity->isFlying();

        $x = $dest->x - $epos->x;
        $y = $dest->y - $epos->y;
        $z = $dest->z - $epos->z;

        if ($x ** 2 + $z ** 2 < 0.7) {
            if ($entity->getTargetEntity() === null) {
                $motion->y = 0;
                $entity->setTimer($flying == true ? 100 : 200);
                $entity->setDestination(new Vector3(0, 0, 0));
            }
        } else {
        $diff = abs($x) + abs($z);

            $randomFactorX = $x / $diff + (rand(-5, 5)/10); 
            $randomFactorZ = $z / $diff + (rand(-1, 1));

            $motion->x = $speed * 0.15 + $randomFactorX;
          $motion->y = 0;
            $motion->z = $speed * 0.15 * $randomFactorZ;



            if ($entity->isSwimming()) {
                $motion->y = $speed * 0.15 * ($y / $diff);
            }
        }

        return new Vector3($motion->x, $motion->y, $motion->z);
    }

    public function attackEntity(MobsEntity $entity, int $damage) {
        $target = $entity->getTargetEntity();

        if ($target === null) {
            return;
        }

        $dist = $entity->getPosition()->distanceSquared($target->getPosition());

        if (!$target->isAlive() or $dist >= 200 or ($target instanceof Player and $target->isCreative() == true)) {
            $entity->setMovementSpeed(1.00);
            $entity->setTargetEntity(null);
            return;
        }

        if ($entity->getAttackDelay() > 20 && $dist < 2) {
            $entity->setAttackDelay(0);
            $ev = new EntityDamageByEntityEvent($entity, $target, EntityDamageEvent::CAUSE_ENTITY_ATTACK, $damage);
            $target->attack($ev);
        }

        $entity->setAttackDelay($entity->getAttackDelay() + 1);

        $pos = $target->getPosition();
        $entity->setDestination(new Vector3($pos->x, 0, $pos->z));
  }
}
