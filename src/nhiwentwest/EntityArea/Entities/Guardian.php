<?php

declare(strict_types=1);

namespace nhiwentwest\EntityArea\Entities;

use pocketmine\network\mcpe\protocol\types\entity\EntityIds;

class Guardian extends MobsEntity {
	const TYPE_ID = EntityIds::GUARDIAN;
	const HEIGHT = 0.85;
}
