<?php

declare(strict_types=1);

namespace nhiwentwest\EntityArea\Entities;

use pocketmine\network\mcpe\protocol\types\entity\EntityIds;

class Donkey extends MobsEntity {
	const TYPE_ID = EntityIds::DONKEY;
	const HEIGHT = 1.6;
}
