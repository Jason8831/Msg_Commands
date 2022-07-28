<?php

namespace Jason8831\msg;

use pocketmine\event\Listener;
use pocketmine\plugin\PluginBase;
use pocketmine\utils\Config;

class Main extends PluginBase implements Listener
{

    public Config $config;

    /**
     * @var Main
     */
    private static $instance;

    public function onEnable(): void
    {
        self::$instance = $this;
        $this->getLogger()->info("§f[§l§4MSG Commande§r§f]: Activée");
        $this->saveResource("config.yml");

        $this->getServer()->getCommandMap()->unregister($this->getServer()->getCommandMap()->getCommand("msg"));
        $this->getServer()->getCommandMap()->registerAll("all", [
            new Command\MSG(name: "msg", description: "permet d'envoyer un message priver a un joueur", usageMessage: "msg")
        ]);
    }

    public static function getInstance(): self{
        return self::$instance;
    }
}