<?php

declare(strict_types=1);

namespace nhiwentwest\EntityArea\Entities;

use pocketmine\network\mcpe\protocol\types\entity\EntityIds;

class Bat extends MobsEntity {
	const TYPE_ID = EntityIds::BAT;
	const HEIGHT = 0.9;
}
