<?php

declare(strict_types=1);

namespace nhiwentwest\EntityArea\Custom;

use nhiwentwest\EntityArea\Main;
use pocketmine\scheduler\Task;

class Schedule extends Task {
	public function onRun() : void {
        Main::$instance->spawnobj->deSpawnMobs();
	}
}
