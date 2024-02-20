<?php

declare(strict_types=1);

namespace nhiwentwest\EntityArea\Entities;

use pocketmine\network\mcpe\protocol\types\entity\EntityIds;

class Blaze extends MobsEntity {
	const TYPE_ID = EntityIds::BLAZE;
	const HEIGHT = 1.8;
}
