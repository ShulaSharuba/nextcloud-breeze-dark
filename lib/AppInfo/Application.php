<?php
/**
 * Breeze Dark theme for Nextcloud
 * 
 * @copyright Copyright (C) 2020  Magnus Walbeck <mw@mwalbeck.org>
 * 
 * @author Magnus Walbeck <mw@mwalbeck.org>
 * 
 * @license GNU AGPL version 3 or any later version
 * 
 * This program is free software: you can redistribute it and/or modify
 * it under the terms of the GNU Affero General Public License as published by
 * the Free Software Foundation, either version 3 of the License, or
 * (at your option) any later version.
 * 
 * This program is distributed in the hope that it will be useful,
 * but WITHOUT ANY WARRANTY; without even the implied warranty of
 * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
 * GNU Affero General Public License for more details.
 * 
 * You should have received a copy of the GNU Affero General Public License
 * along with this program.  If not, see <http://www.gnu.org/licenses/>.
 * 
 */


namespace OCA\BreezeDark\AppInfo;

use OCP\AppFramework\App;
use OCP\Util;

class Application extends App {

    /** @var string */
    public const APP_NAME = 'breezedark';

    /** @var string */
    protected $appName;

    /** @var IConfig */
    private $config;

    /** @var IUser */
    private $user;

    public function __construct() {
        parent::__construct(self::APP_NAME);
        $this->appName  = self::APP_NAME;
        $this->config   = \OC::$server->getConfig();
        $this->user     = \OC::$server->getUserSession()->getUser();
    }

    /**
     * Check if the theme should be applied
     */
    public function doTheming() {
        $default = $this->config->getAppValue($this->appName, "theme_enabled", "0");
        $loginPage = $this->config->getAppValue($this->appName, "theme_login_page", "1");

        if (!is_null($this->user) AND $this->config->getUserValue($this->user->getUID(), $this->appName, "theme_enabled", $default)) {
            // When shown the 2FA login page you are logged in while also being on a login page, 
            // so a logged in user still needs the guests.css stylesheet
            $this->addStyling($loginPage);
        } else if (is_null($this->user) AND $default) {
            $this->addStyling($loginPage);
        }
    }

    /**
     * Add stylesheet(s) to nextcloud
     * 
     * @param string $loginPage
     */
    public function addStyling($loginPage) {
        Util::addStyle($this->appName, 'server');

        // If the styling for the login page is wanted, load the stylesheet.
        if ($loginPage) {
            Util::addStyle($this->appName, 'guest');
        }
    }
}
