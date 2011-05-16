<?php
/**
*   Zanby Enterprise Group Family System
*
*    Copyright (C) 2005-2011 Zanby LLC. (http://www.zanby.com)
*
*    This program is free software: you can redistribute it and/or modify
*    it under the terms of the GNU General Public License as published by
*    the Free Software Foundation, either version 3 of the License, or
*    (at your option) any later version.
*
*    This program is distributed in the hope that it will be useful,
*    but WITHOUT ANY WARRANTY; without even the implied warranty of
*    MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
*    GNU General Public License for more details.
*
*    You should have received a copy of the GNU General Public License
*    along with this program.  If not, see <http://www.gnu.org/licenses/>.
*
*    To contact Zanby LLC, send email to info@zanby.com.  Our mailing
*    address is:
*
*            Zanby LLC
*            3611 Farmington Road
*            Minnetonka, MN 55305
*
* @category   Zanby
* @package    Zanby
* @copyright  Copyright (c) 2005-2011 Zanby LLC. (http://www.zanby.com)
* @license    http://zanby.com/license/     GPL License
* @version    <this will be auto generated>
*/

    define('APPLICATION_ENV', (getenv('APPLICATION_ENV') ? getenv('APPLICATION_ENV') : null));

    if (APPLICATION_ENV === null) {
        throw new Exception("APPLICATION_ENV must be set.");
    }

    $implCfgPath = $home.'/configs/cfg.baseImplementation.xml';
    if (is_readable($implCfgPath) && (($xml = simplexml_load_file($implCfgPath)) !== false)) {
        $applicationEnv = APPLICATION_ENV;
        $implementationTag = $xml->$applicationEnv;
        if (!$implementationTag) throw new Exception('No environment '. $applicationEnv. ' in  cfg.baseImplementation.xml');
        $baseImplementationPath = (string)$implementationTag->baseImplementation;
    } else {
        throw new Exception('Error parse cfg.baseImplementation.xml');
    }


    require_once realpath($baseImplementationPath.'/init/Initializing.php');
    error_reporting(E_ALL);
    $application->bootstrap(array('FileCache', 'Defines', 'Databases', 'CssJsImagesPaths', 'Session'));
    error_reporting(E_ALL);
