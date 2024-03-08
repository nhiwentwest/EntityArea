<?php

declare(strict_types=1);

namespace nhiwentwest\EntityArea\Custom;

use pocketmine\data\bedrock\LegacyEntityIdToStringIdMap;
use pocketmine\entity\EntityDataHelper;
use pocketmine\entity\EntityFactory;
use pocketmine\nbt\tag\CompoundTag;
use pocketmine\world\World;
use nhiwentwest\EntityArea\Entities\Bat;
use nhiwentwest\EntityArea\Entities\Blaze;
use nhiwentwest\EntityArea\Entities\Cat;
use nhiwentwest\EntityArea\Entities\CaveSpider;
use nhiwentwest\EntityArea\Entities\Chicken;
use nhiwentwest\EntityArea\Entities\Cod;
use nhiwentwest\EntityArea\Entities\Cow;
use nhiwentwest\EntityArea\Entities\Creeper;
use nhiwentwest\EntityArea\Entities\Dolphin;
use nhiwentwest\EntityArea\Entities\Donkey;
use nhiwentwest\EntityArea\Entities\ElderGuardian;
use nhiwentwest\EntityArea\Entities\Enderman;
use nhiwentwest\EntityArea\Entities\Ghast;
use nhiwentwest\EntityArea\Entities\Guardian;
use nhiwentwest\EntityArea\Entities\Horse;
use nhiwentwest\EntityArea\Entities\Husk;
use nhiwentwest\EntityArea\Entities\IronGolem;
use nhiwentwest\EntityArea\Entities\Llama;
use nhiwentwest\EntityArea\Entities\MagmaCube;
use nhiwentwest\EntityArea\Entities\MobsEntity;
use nhiwentwest\EntityArea\Entities\Mooshroom;
use nhiwentwest\EntityArea\Entities\Ocelot;
use nhiwentwest\EntityArea\Entities\Parrot;
use nhiwentwest\EntityArea\Entities\Phantom;
use nhiwentwest\EntityArea\Entities\Pig;
use nhiwentwest\EntityArea\Entities\PolarBear;
use nhiwentwest\EntityArea\Entities\PufferFish;
use nhiwentwest\EntityArea\Entities\Rabbit;
use nhiwentwest\EntityArea\Entities\Salmon;
use nhiwentwest\EntityArea\Entities\Sheep;
use nhiwentwest\EntityArea\Entities\Silverfish;
use nhiwentwest\EntityArea\Entities\Skeleton;
use nhiwentwest\EntityArea\Entities\SkeletonHorse;
use nhiwentwest\EntityArea\Entities\Slime;
use nhiwentwest\EntityArea\Entities\Spider;
use nhiwentwest\EntityArea\Entities\Squid;
use nhiwentwest\EntityArea\Entities\Stray;
use nhiwentwest\EntityArea\Entities\TropicalFish;
use nhiwentwest\EntityArea\Entities\Villager;
use nhiwentwest\EntityArea\Entities\Witch;
use nhiwentwest\EntityArea\Entities\Wolf;
use nhiwentwest\EntityArea\Entities\Zombie;
use nhiwentwest\EntityArea\Entities\ZombieVillager;


use nhiwentwest\EntityArea\Main;
class Registrations {
	public function registerEntities() {
		Main::$instance->classes = $this->getClasses();
		$entityFactory = EntityFactory::getInstance();
		foreach (Main::$instance->classes as $entityName => $typeClass) {
			$entityFactory->register($typeClass,
				static function(World $world, CompoundTag $nbt) use($typeClass): MobsEntity {
					return new $typeClass(EntityDataHelper::parseLocation($nbt, $world), $nbt);
				},
			[$entityName]);
		}
	}

	public function getClasses() : array {
		return [
			"Bat" => Bat::class,
			"Blaze" => Blaze::class,
			"Cat" => Cat::class,
			"CaveSpider" => CaveSpider::class,
			"Chicken" => Chicken::class,
			"Cod" => Cod::class,
			"Cow" => Cow::class,
			"Creeper" => Creeper::class,
			"Dolphin" => Dolphin::class,
			"Donkey" => Donkey::class,
			"ElderGuardian" => ElderGuardian::class,
			"Enderman" => Enderman::class,
			"Ghast" => Ghast::class,
			"Guardian" => Guardian::class,
			"Horse" => Horse::class,
			"Husk" => Husk::class,
			"IronGolem" => IronGolem::class,
			"Llama" => Llama::class,
			"MagmaCube" => MagmaCube::class,
			"Mooshroom" => Mooshroom::class,
			"Ocelot" => Ocelot::class,
			"Parrot" => Parrot::class,
			"Phantom" => Phantom::class,
			"Pig" => Pig::class,
			"PolarBear" => PolarBear::class,
			"PufferFish" => PufferFish::class,
			"Rabbit" => Rabbit::class,
			"Salmon" => Salmon::class,
			"Sheep" => Sheep::class,
			"Silverfish" => Silverfish::class,
			"Skeleton" => Skeleton::class,
			"SkeletonHorse" => SkeletonHorse::class,
			"Slime" => Slime::class,
			"Spider" => Spider::class,
			"Squid" => Squid::class,
			"Stray" => Stray::class,
			"TropicalFish" => TropicalFish::class,
			"Villager" => Villager::class,
			"Witch" => Witch::class,
			"Wolf" => Wolf::class,
			"Zombie" => Zombie::class,
			"ZombieVillager" => ZombieVillager::class
		];
	}
}
