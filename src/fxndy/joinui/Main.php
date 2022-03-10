<?php 

namespace fxndy\joinui;

use pocketmine\plugin\PluginBase;
use pocketmine\player\Player;
use pocketmine\event\Listener;
use pocketmine\event\player\PlayerJoinEvent;

use fxndy\joinui\libs\formapi\SimpleForm;

class Main extends PluginBase implements Listener
{

	private $button = true;

	public function onEnable(): void
	{
		$this->getServer()->getPluginManager()->registerEvents($this, $this);
		$this->saveResource("config.yml");
		$this->button = $this->getConfig()->get("button", true);
	}

	public function onJoin(PlayerJoinEvent $event)
	{
		$player = $event->getPlayer();

		if($this->getConfig()->get("show-form") == true)
		{
			$this->form($player);
		}
	}

	public function form(Player $player)
	{
		$form = new SimpleForm(function(Player $player, $data){
			if($data === null) return;
		});
		$form->setTitle($this->replace($player, $this->getConfig()->get("title-form", "SimpleJoinUI")));
		$form->setContent($this->replace($player, $this->getConfig()->get("content-form")));
		$form->addButton($this->button, (($this->button) ? $this->replace($player, $this->getConfig()->get("button-name", "Ok")) : "-"));
		$form->sendToPlayer($player);
	}

	public function replace(Player $player, string $subject)
	{	
		$a = [
			"{player_name}",
			"{ping}",
			"{online}",
			"{line}"
		];
		$b = [
			$player->getName(),
			$player->getNetworkSession()->getPing(),
			count($this->getServer()->getOnlinePlayers()),
			"\n"
		];
		return str_replace($a, $b, $subject);
	}

}
