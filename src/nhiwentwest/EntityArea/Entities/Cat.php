<?php

declare(strict_types=1);

namespace nhiwentwest\EntityArea\Entities;

use pocketmine\network\mcpe\protocol\types\entity\EntityIds;

class Cat extends MobsEntity {
	const TYPE_ID = EntityIds::CAT;
	const HEIGHT = 1.0;
}
