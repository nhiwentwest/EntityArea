<?php

declare(strict_types=1);

namespace nhiwentwest\EntityArea\Entities;

use pocketmine\network\mcpe\protocol\types\entity\EntityIds;

class Ghast extends MobsEntity {
	const TYPE_ID = EntityIds::GHAST;
	const HEIGHT = 4.0;
}
