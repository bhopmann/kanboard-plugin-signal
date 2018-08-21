<?php

namespace Kanboard\Plugin\Signal;

use Kanboard\Core\Translator;
use Kanboard\Core\Plugin\Base;

/*
 * Signal Plugin
 *
 * @package  Kanboard\Plugin\Signal
 * @author   Benedikt Hopmann
 */
class Plugin extends Base
{
    public function initialize()
    {
        $this->template->hook->attach('template:config:integrations', 'signal:config/integration');
        $this->template->hook->attach('template:project:integrations', 'signal:project/integration');
        $this->template->hook->attach('template:user:integrations', 'signal:user/integration');

        $this->userNotificationTypeModel->setType('signal', t('Signal'), '\Kanboard\Plugin\Signal\Notification\Signal');
        $this->projectNotificationTypeModel->setType('signal', t('Signal'), '\Kanboard\Plugin\Signal\Notification\Signal');
    }

    public function onStartup()
    {
        Translator::load($this->languageModel->getCurrentLanguage(), __DIR__.'/Locale');
    }

    public function getPluginDescription()
    {
        return t('Receive notifications on Signal (via signal-cli)');
    }

    public function getPluginAuthor()
    {
        return 'Benedikt Hopmann';
    }

    public function getPluginVersion()
    {
        return '0.0.1';
    }

    public function getPluginHomepage()
    {
        return 'https://github.com/stratmaster/kanboard-plugin-signal';
    }

    public function getCompatibleVersion()
    {
        return '>=1.0.37';
    }
}