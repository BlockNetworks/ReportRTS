<?php

namespace ProjectInfinity\ReportRTS;

use pocketmine\plugin\PluginBase;

use ProjectInfinity\ReportRTS\command\ReportRTSCommand;
use ProjectInfinity\ReportRTS\command\TicketCommand;
use ProjectInfinity\ReportRTS\listener\RTSListener;

class ReportRTS extends PluginBase {

    public $ticketMax;
    public $ticketDelay;
    public $ticketMinWords;
    public $ticketPerPage;
    public $ticketPreventDuplicates;
    public $ticketNag;
    public $ticketNagHeld;
    public $ticketHideOffline;

    # Array containing all configurable sub-commands.
    public $commands;
    # Array containing all online staff members (users with reportrts.staff).
    public $staff;
    # Array containing all waiting notifications.
    public $notifications;

    public $debug;

    public function onEnable() {
        $this->getLogger()->info("Welcome to the Alpha for ReportRTS. Please report any bugs you may discover to https://github.com/ProjectInfinity/ReportRTS/issues.
        This project is a large Bukkit project that is being ported to PocketMine.");
        $this->reloadSettings();

        # Register commands.
        $this->getCommand("ticket")->setExecutor(new TicketCommand($this));
        $this->getCommand("reportrts")->setExecutor(new ReportRTSCommand($this));

        # Register event listeners.
        $this->getServer()->getPluginManager()->registerEvents(new RTSListener($this), $this);
    }

    public function reloadSettings() {
        $this->saveDefaultConfig();
        $this->reloadConfig();

        # Ticket configuration.
        $this->ticketMax = $this->getConfig()->get("ticket")["max"];
        $this->ticketDelay = $this->getConfig()->get("ticket")["delay"];
        $this->ticketMinWords = $this->getConfig()->get("ticket")["minimumWords"];
        $this->ticketPerPage = $this->getConfig()->get("ticket")["perPage"];
        $this->ticketPreventDuplicates = $this->getConfig()->get("ticket")["preventDuplicates"];
        $this->ticketNag = $this->getConfig()->get("ticket")["nag"];
        $this->ticketNagHeld = $this->getConfig()->get("ticket")["nagHeld"];
        $this->ticketHideOffline= $this->getConfig()->get("ticket")["hideOffline"];

        # Command configuration.
        $this->commands = array();
        $this->commands['readTicket'] = $this->getConfig()->get("command")["readTicket"];
        $this->commands['openTicket'] = $this->getConfig()->get("command")["openTicket"];
        $this->commands['closeTicket'] = $this->getConfig()->get("command")["closeTicket"];
        $this->commands['reopenTicket'] = $this->getConfig()->get("command")["reopenTicket"];
        $this->commands['claimTicket'] = $this->getConfig()->get("command")["claimTicket"];
        $this->commands['assignTicket'] = $this->getConfig()->get("command")["assignTicket"];
        $this->commands['unclaimTicket'] = $this->getConfig()->get("command")["unclaimTicket"];
        $this->commands['teleportToTicket'] = $this->getConfig()->get("command")["teleportToTicket"];
        $this->commands['broadcastToStaff'] = $this->getConfig()->get("command")["broadcastToStaff"];
        $this->commands['listStaff'] = $this->getConfig()->get("command")["listStaff"];

        # Setup notification array.
        $this->notifications = array();

        # Setup staff array.
        $this->staff = array();

        # Shows debug information in the plugin if enabled.
        $this->debug = $this->getConfig()->get("debug");
    }
}
