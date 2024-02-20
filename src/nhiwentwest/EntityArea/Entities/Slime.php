<?php

declare(strict_types=1);

namespace nhiwentwest\EntityArea\Entities;

use pocketmine\network\mcpe\protocol\types\entity\EntityIds;

class Slime extends MobsEntity {
	const TYPE_ID = EntityIds::SLIME;
	const HEIGHT = 0.51;
}
