<?php

declare(strict_types=1);

namespace nhiwentwest\EntityArea\Entities;

use pocketmine\network\mcpe\protocol\types\entity\EntityIds;

class Horse extends MobsEntity {
	const TYPE_ID = EntityIds::HORSE;
	const HEIGHT = 1.0;
}
