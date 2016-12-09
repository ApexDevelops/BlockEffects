<?php

namespace iiInfinityHD\BlockEffects;

use pocketmine\plugin\PluginBase;
use pocketmine\event\Listener;
use pocketmine\utils\Config;
use pocketmine\utils\TextFormat;
use pocketmine\event\player\PlayerMoveEvent;
use pocketmine\entity\Effect;
use pocketmine\block\Block;
use pocketmine\math\Vector3;
use pocketmine\entity\InstantEffect;

use pocketmine\level\particle\BubbleParticle;
use pocketmine\level\particle\CriticalParticle;
use pocketmine\level\particle\EnchantParticle;
use pocketmine\level\particle\FlameParticle;
use pocketmine\level\particle\HeartParticle;
use pocketmine\level\particle\InkParticle;
use pocketmine\level\particle\LavaDripParticle;
use pocketmine\level\particle\PortalParticle;
use pocketmine\level\particle\SmokeParticle;
use pocketmine\level\particle\WaterDripParticle;

class BlockEffects extends PluginBase implements Listener{
    
    public function onEnable(){
        $this->getServer()->getPluginManager()->registerEvents($this, $this);
        $this->getLogger()->info(TextFormat::DARK_GREEN . "✔ BlockEffects Enabled");
        $this->path = $this->getDataFolder();
        @mkdir($this->path);
        if(!file_exists($this->path . "config.yml")) {
            $this->config = new Config($this->path . "config.yml", Config::YAML,array(
            "133:0" => array(
                array(
                    "effect" => 1,
	            "amplifier" => 10,
                    "duration" => 10,
                    "visible" => true,
                    "particle" => "heart",
                    ),
                array(
                    "effect" => 8,
	            "amplifier" => 5,
                    "duration" => 10,
                    "visible" => false,
                    "particle" => "heart",
                    ),
                ),
            ));
        } else {
            $this->saveConfig();
        }
    }
    
    public function onMove(PlayerMoveEvent $event) {
        $bubble = new BubbleParticle(new Vector3($event->getPlayer()->getX(), $event->getPlayer()->getY() + 0.5, $event->getPlayer()->getZ()));
        $critical = new CriticalParticle(new Vector3($event->getPlayer()->getX(), $event->getPlayer()->getY() + 0.5, $event->getPlayer()->getZ()));
        $enchant = new EnchantParticle(new Vector3($event->getPlayer()->getX(), $event->getPlayer()->getY() + 0.5, $event->getPlayer()->getZ()));
        $flame = new FlameParticle(new Vector3($event->getPlayer()->getX(), $event->getPlayer()->getY() + 0.5, $event->getPlayer()->getZ()));
        $heart = new HeartParticle(new Vector3($event->getPlayer()->getX(), $event->getPlayer()->getY() + 0.5, $event->getPlayer()->getZ()));
        $ink = new InkParticle(new Vector3($event->getPlayer()->getX(), $event->getPlayer()->getY() + 0.5, $event->getPlayer()->getZ()));
        $lava = new LavaDripParticle(new Vector3($event->getPlayer()->getX(), $event->getPlayer()->getY() + 0.5, $event->getPlayer()->getZ()));
        $portal = new PortalParticle(new Vector3($event->getPlayer()->getX(), $event->getPlayer()->getY() + 0.5, $event->getPlayer()->getZ()));
        $smoke = new SmokeParticle(new Vector3($event->getPlayer()->getX(), $event->getPlayer()->getY() + 0.5, $event->getPlayer()->getZ()));
        $water = new WaterDripParticle(new Vector3($event->getPlayer()->getX(), $event->getPlayer()->getY() + 0.5, $event->getPlayer()->getZ()));
        
        $player = $event->getPlayer();
        $cfg = $this->getConfig();
        $block = $event->getPlayer()->getLevel()->getBlock($event->getPlayer()->floor()->subtract(0, 1));
        if($block instanceof Block) {
            $id = $block->getId();
            $meta = $block->getDamage();
            if($cfg->exists($id . ":" . $meta)) {
                $effects = $cfg->get($id . ":" . $meta);
                foreach($effects as $effect) {
                    $player->addEffect(Effect::getEffect((int)$effect["effect"])->setAmplifier((int)$effect["amplifier"])->setDuration((int)$effect["duration"] * 20)->setVisible((int)$effect["visible"]));
                    
                    if($effect["particle"] === "none") {
                        //
                    } elseif($effect["particle"] === "bubble") {
                        $player->getLevel()->addParticle($bubble);
                    } elseif($effect["particle"] === "critical") {
                        $player->getLevel()->addParticle($critical);
                    } elseif($effect["particle"] === "enchant") {
                        $player->getLevel()->addParticle($enchant);
                    } elseif($effect["particle"] === "flame") {
                        $player->getLevel()->addParticle($flame);
                    } elseif($effect["particle"] === "heart") {
                        $player->getLevel()->addParticle($heart);
                    } elseif($effect["particle"] === "ink") {
                        $player->getLevel()->addParticle($ink);
                    } elseif($effect["particle"] === "lava") {
                        $player->getLevel()->addParticle($lava);
                    } elseif($effect["particle"] === "portal") {
                        $player->getLevel()->addParticle($portal);
                    } elseif($effect["particle"] === "smoke") {
                        $player->getLevel()->addParticle($smoke);
                    } elseif($effect["particle"] === "water") {
                        $player->getLevel()->addParticle($water);
                    }
                }
            }
        }
    }
    
    public function onDisable(){
        $this->saveDefaultConfig();
        $this->getLogger()->info(TextFormat::DARK_RED . "✖ BlockEffects Disabled");
    }
}