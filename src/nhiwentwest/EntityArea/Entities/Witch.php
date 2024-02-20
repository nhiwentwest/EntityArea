<?php

declare(strict_types=1);

namespace nhiwentwest\EntityArea\Entities;

use pocketmine\network\mcpe\protocol\types\entity\EntityIds;

class Witch extends MobsEntity {
	const TYPE_ID = EntityIds::WITCH;
	const HEIGHT = 1.95;
}
