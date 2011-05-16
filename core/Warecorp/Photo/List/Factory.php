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
/**
 * Warecorp FRAMEWORK
 * @package Warecorp_Photo_List
 * @author Artem Sukharev
 * @version 1.0
 */
class Warecorp_Photo_List_Factory
{
	/**
	 * create photo list object
	 * @param Warecorp_Photo_Gallery_Abstract $gallery
	 * @author Artem Sukharev
	 */
	public static function load($gallery)
	{
		if ( $gallery instanceof Warecorp_Photo_Gallery_User ) {
            return new Warecorp_Photo_List_User($gallery->getId());
		} elseif ( $gallery instanceof Warecorp_Photo_Gallery_Simple ) {
			return new Warecorp_Photo_List_Simple($gallery->getId());
		} elseif ( $gallery instanceof Warecorp_Photo_Gallery_Family ) {
            return new Warecorp_Photo_List_Family($gallery->getId());
        } else {
            throw new Zend_Exception('Unknown gallery type');
        }
	}
    
    /**
     * create photo list object
     * @param Warecorp_User|Warecorp_Group_Base $owner
     * @author Artem Sukharev
     */
    public static function loadByOwner($owner)
    {
        if ( $owner instanceof Warecorp_User ) {
            return new Warecorp_Photo_List_User();
        } elseif ( $owner instanceof Warecorp_Group_Simple ) {
            return new Warecorp_Photo_List_Simple();
        } elseif ( $owner instanceof Warecorp_Group_Family ) {
            return new Warecorp_Photo_List_Family();
        } else {
            throw new Zend_Exception('Unknown owner type');
        }
    }
}
