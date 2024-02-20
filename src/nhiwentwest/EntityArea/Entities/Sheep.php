<?php

declare(strict_types=1);

namespace nhiwentwest\EntityArea\Entities;

use pocketmine\network\mcpe\protocol\types\entity\EntityIds;

class Sheep extends MobsEntity {
	const TYPE_ID = EntityIds::SHEEP;
	const HEIGHT = 1.3;
}
