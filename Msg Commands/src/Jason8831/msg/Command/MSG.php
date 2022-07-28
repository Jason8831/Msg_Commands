<?php

namespace Jason8831\msg\Command;

use Jason8831\msg\Main;
use pocketmine\command\Command;
use pocketmine\command\CommandSender;
use pocketmine\lang\Translatable;
use pocketmine\player\Player;
use pocketmine\Server;
use pocketmine\utils\Config;

class MSG extends Command
{


    public function __construct(string $name, Translatable|string $description = "", Translatable|string|null $usageMessage = null, array $aliases = [])
    {
        parent::__construct($name, $description, $usageMessage, $aliases);
    }

    public function execute(CommandSender $sender, string $commandLabel, array $args)
    {

        $config = new Config(Main::getInstance()->getDataFolder() . "config.yml", Config::YAML);

        if($sender instanceof Player){
            if(!isset($args[0])){
                $sender->sendMessage($config->get("Usage"));
            }else{
                $target = Server::getInstance()->getPlayerByPrefix($args[0]);
                if($target instanceof Player) {
                    if (!$target->isOnline()) {
                        $sender->sendMessage($config->get("NoOnlinePlayer"));
                    } else {
                        if (!isset($args[1])) {
                            $sender->sendMessage("§f[§l§4ERROR§r§f]: vous devez mettre un message");
                        } else  {
                            unset($args[0]);
                            $msg = implode(" ", $args);
                            $messagetarget = str_replace(["{player}", "{target}", "{message}"], [$sender->getName(), $target->getName(), $msg], $config->get("MessageFormatTarget"));
                            $target->sendMessage($messagetarget);
                            $messagesender = str_replace("{target}",  $target->getName(), $config->get("ConfirmeSend"));
                            $sender->sendMessage($messagesender);
                            assert($sender instanceof Player);
                            if($sender->hasPermission("staff.viewmsg")){
                                $messageStaff = str_replace(["{target}", "{sender}", "{message}"], [$target->getName(), $sender->getName(), $msg], $config->get("ViewMessageStaff"));
                                $sender->sendMessage($messageStaff);
                            }
                        }
                    }
                }
            }
        }
    }
}