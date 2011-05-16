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
* @package
* @copyright  Copyright (c) 2005-2011 Zanby LLC. (http://www.zanby.com)
* @license    http://zanby.com/license/     GPL License
* @version    <this will be auto generated>
*/

    class Warecorp_Common_DBConfig {

        private $_db;

        public function __construct() {
            $this->_db = Zend_Registry::get('DB');
        }

        public function get($key){
            $val = $this->_db->fetchRow("SELECT `val` FROM `zanby_config` WHERE `key`='".$key."'");
            return $val['val'];
        }

        public function set($key, $val){
            if ($this->_db->query("UPDATE `zanby_config` SET `val`='".$val."' WHERE `key`='".$key."'")) return true;
            else return false;
        }

    }
?>
