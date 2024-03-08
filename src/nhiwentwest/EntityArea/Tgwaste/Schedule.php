<?php

declare(strict_types=1);

namespace nhiwentwest\EntityArea\Tgwaste;

use nhiwentwest\EntityArea\Main;
use pocketmine\scheduler\Task;

class Schedule extends Task {
	public function onRun() : void {
        Main::$instance->spawnobj->deSpawnMobs();
	}
}
