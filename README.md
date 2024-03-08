# EntityArea
The Entity Area is a tool designed to assist users in managing entities within one or multiple areas, focusing specifically on entities with attack attributes.

## Features
1. Entity Management: Easily set the number of a specific type of entity located in a designated area, with the assurance that the specified number of entities is maintained. 
2. Attribute Customization: Adjust various attributes of the entity including:
- Damage
- Health
- Speed
- Armor
3. Entities perform fundamental actions, such as moving and attacking players.
## Commands

1. **/enti**:
   - **Usage**: `/enti create|pos1|pos2`
   - **Description**: Creates an area for a specific entity.
   - **Permission**: `entityarea.enti`

2. **/clearmobs**:
   - **Usage**: `/clearmobs`
   - **Description**: Clears all entities.
   - **Permission**: `entityarea.clear`

## Usage

First, use the following command to create an area:

```php
/enti create <name>
```

Please note that when you name that area, the name will also be set for your entity's name. This is for easier management of entities within the area.

Second, mark the first position (pos1) of the area by using:
```php
/enti pos1
```
After successfully marking pos1, use the following command to mark the second position (pos2) by breaking the block opposite and at diagonal angles to form a rectangle:


```php
/enti pos2
```

Once pos1 and pos2 are set, the area is defined. You can now customize this area in the configuration settings.

## Configuration

After creating an area, the configuration will appear as follows:


```yml

pdf:
 pos1:
 - 21
 - 72
 - -31
 pos2:
 - 25
 - 72
 - -42
 world: world
 entity: Zombie
 damage: 4
 health: 20
 speed: 1.00
 number: 10
 armor:
  - none
  - none
  - none
  - none


```


- `'pdf'` is the designated name of the area you have set.
- By default, the entity in this area is a Zombie. You can customize the entities in the list provided at the end of this page.
- Default values for damage, health, and speed are provided, which you can easily modify.
- `'number'` represents the desired quantity of entities within the area. This number will be maintained within the specified area.
- For the `'armor'` attribute, if you do not want the entity to wear armor, please keep it as `'none'`. Do not leave it blank or use `''` as this may cause errors on your server. To customize the armor of entities, please specify the type of armor desired. For example:


```yml

 armor:
  - CHAINMAIL_HELMET
  - CHAINMAIL_CHESTPLATE
  - CHAINMAIL_LEGGINGS
  - CHAINMAIL_BOOTS


```


### Lists
<details>
<summary>Entities list list</summary>

```cpp

Bat
Blaze
Cat
CaveSpider
Chicken
Cod
Cow
Creeper
Dolphin
Donkey
ElderGuardian
Enderman
Ghast
Guardian
Horse
Husk
IronGolem
Llama
MagmaCube
MobsEntity
Mooshroom
Ocelot
Parrot
Phantom
Pig
PolarBear
PufferFish
Rabbit
Salmon
Sheep
Silverfish
Skeleton
SkeletonHorse
Slime
Spider
Squid
Stray
TropicalFish
Villager
Witch
Wolf
Zombie
ZombieVillager

```
</details>

<details>
<summary>Armor list</summary>

```cpp
CHAINMAIL_HELMET
CHAINMAIL_CHESTPLATE
CHAINMAIL_LEGGINGS
CHAINMAIL_BOOTS
IRON_HELMET
IRON_CHESTPLATE
IRON_LEGGINGS
IRON_BOOTS
GOLD_HELMET
GOLD_CHESTPLATE
GOLD_LEGGINGS
GOLD_BOOTS
DIAMOND_HELMET
DIAMOND_CHESTPLATE
DIAMOND_LEGGINGS
DIAMOND_BOOTS
```
</details>

### Donate
```cpp
http://paypal.com/nhiwentwest
```
