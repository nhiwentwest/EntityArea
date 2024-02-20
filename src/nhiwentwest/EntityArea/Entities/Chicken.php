<?php

declare(strict_types=1);

namespace nhiwentwest\EntityArea\Entities;

use pocketmine\network\mcpe\protocol\types\entity\EntityIds;

class Chicken extends MobsEntity {
	const TYPE_ID = EntityIds::CHICKEN;
	const HEIGHT = 0.7;
}
