<?php

declare(strict_types=1);

namespace nhiwentwest\EntityArea\Entities;

use pocketmine\network\mcpe\protocol\types\entity\EntityIds;

class Stray extends MobsEntity {
	const TYPE_ID = EntityIds::STRAY;
	const HEIGHT = 1.99;
}
