<?php

/** --[DRM - Master]--
*
* If You Dead? You will be reduce money to When You do not have Money. 
* But Don't Worry, If Killed one People, You can get Reward is 12000 Money and Let's Go to new life.
* So? You're Boring or Scared? no way, This way will do you feel Amazing or Better than, Trust me!
* Money Reduce-Default: 5000
* Rank Promotions: Vip1-4000, Vip2-3700, Vip3-3300, Vip4-2000, Vip5-700, Other Things. Staff can be Free because See them have do him better?
*/

namespace DRMmarching\SystemORG;

use pocketmine\plugin\PluginBase;
use pocketmine\{Player, Server};
use pocketmine\command\Command;
use pocketmine\command\CommandSender;
use pocketmine\utils\Config;
use pocketmine\event\Listener;
use pocketmine\event\player\PlayerJoinEvent;
use pocketmine\event\player\PlayerDeathEvent;
use pocketmine\event\entity\EntityDamageEvent;
use pocketmine\event\entity\EntityDamageByChildEntityEvent;
use pocketmine\event\entity\EntityDamageEntityEvent;
use pocketmine\level\sound\AnvilFallSound;
use onebone\economyapi\EconomyAPI;

class Main extends PluginBase implements Listener{
	public $tag = "§c•…• §aDRM§e-§bSystem§c •…•";
	public $config;
	
	public function onEnable(){
		$this->getServer()->getLogger()->info($this->tag . "§a§l Enable Plugin...");
		$this->money = new Config($this->getDataFolder() . "Money.yml", Config::YAML, [
		"Vip1" => 4000,
		"Vip2" => 3700,
		"Vip3" => 3300,
		"Vip4" => 2000,
		"Vip5" => 700,
		"Staff" => 0,
		]);
		$this->money->save();
		$this->EconomyAPI = $this->getServer()->getPluginManager()->getPlugin("EconomyAPI");
		$this->eco = EconomyAPI::getInstance();
		$this->pp = $this->getServer()->getPluginManager()->getPlugin("PurePerms");
	}
	
	public function onJoin(PlayerJoinEvent $ev){
		$player = $ev->getPlayer();
		foreach($this->getServer()->getOnlinePlayers() as $players){
			$players->setNameTag("Player");
			$players->sendMessage($this->tag . " §aBạn Nhận được Tag §cPlayer§a!");
		}
		return true;
	}
	
	public function onHit(EntityDamageEvent $ev){
		if($ev instanceof EntityDamageByChildEntityEvent){
			$target = $ev->getEntity();
			$damager = $ev->getDamager();
			$damager->sendTip($this->tag . "§a Bạn Nhận Được §b12 Xu §aTừ việc Đánh §c". $target->getName());
			$damager->getLevel()->addSound(new AnvilFallSound($damager), [$damage]);
			$this->eco->addMoney($damager, 12);
		}
	}
	
	public function onDeath(PlayerDeathEvent $ev){
		$player = $ev->getPlayer();
		$chucvu = $this->pp->getUserDataMgr()->getGroup($player);
		switch($chucvu){
			case "vip1":
			$reduceMoney = 4000;
			break;
			case "vip2":
			$reduceMoney = 3700;
			break;
			case "vip3";
			$reduceMoney = 3300;
			break;
			case "vip4";
			$reduceMoney = 2000;
			break;
			case "vip5";
			$reduceMoney = 700;
			break;
			case "RMP";
			$reduceMoney = 10;
			break;
			default:
			$reduceMoney = 0;
			break;
		}
		$this->eco->reduceMoney($player, $reduceMoney);
	}
	
	public function onDisable(){
		$this->getServer()->getLogger()->notice("§cDisable Plugin!");
	}
}