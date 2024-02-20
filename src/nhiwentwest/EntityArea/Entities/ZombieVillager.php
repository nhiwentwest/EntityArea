<?php

declare(strict_types=1);

namespace nhiwentwest\EntityArea\Entities;

use pocketmine\network\mcpe\protocol\types\entity\EntityIds;

class ZombieVillager extends MobsEntity {
	const TYPE_ID = EntityIds::ZOMBIE_VILLAGER;
	const HEIGHT = 1.95;
}
