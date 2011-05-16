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
 * Yahoo.com contact retrieving class
 *
 *
 * @author Alexey Loshkarev
 */

class Warecorp_Import_Webservice_YahooCom extends Warecorp_Import_Webservice_Base
{
    
    // defined by login()
    public $urlBase = "";
    public $cookieFile = "";
    public $curl;
    
    /**
     * Initiate login session. Returns true on login ok, return false otherwise and this->lastError 
     *   consists of login error
     *
     * @return boolean login result
     * 
     * @author Alexey Loshkarev
     */
    public function login()
    {
        // get login page
        $curl = curl_init("https://login.yahoo.com/");
        curl_setopt($curl, CURLOPT_FOLLOWLOCATION, true);
        curl_setopt($curl, CURLOPT_AUTOREFERER, true);
        curl_setopt($curl, CURLOPT_CONNECTTIMEOUT, 10);
        curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($curl, CURLOPT_HEADER, true);
        $this->cookieFile = tempnam("/tmp", "import_cookie_");
        curl_setopt($curl, CURLOPT_COOKIEJAR, $this->cookieFile);
        
        $html = curl_exec($curl);
        
        //dump(htmlspecialchars($html));
        
        preg_match_all('/<input type="hidden".*?name="(.*?)".*?value="(.*?)">/', $html, $matches, PREG_SET_ORDER);
        //dump($matches);
        $params = array();
        foreach ($matches as $param) {
            $params[] = $param[1] . '=' . urlencode($param[2]);
        }
        $params[] = "login=". urlencode($this->login);
        $params[] = "passwd=". urlencode($this->password);
        preg_match('/<form.*?action="(.*?)"/', $html, $matches);
        $action = $matches[1];
        
//         $urlCurrent = curl_getinfo($curl, CURLINFO_EFFECTIVE_URL);
//         $urlArray = parse_url($urlCurrent);
//         $url = sprintf("%s://%s%s/%s", $urlArray['scheme'], $urlArray['host'], dirname($urlArray['path']), $action);
        //dump($url, $action);
        
        curl_setopt($curl, CURLOPT_POST, true);
        curl_setopt($curl, CURLOPT_POSTFIELDS, implode('&',$params));
        curl_setopt($curl, CURLOPT_URL, $action);
        
        
        // post login data
        $html = curl_exec($curl);
        //dump(htmlspecialchars($html));
        $count = preg_match('/Refresh.*my\.yahoo\.com/', $html);
        //dump($count);
        if ($count) {
            //dump($count);
            $urlBase = $matches[1];
            $this->urlBase = $matches[1];
            //curl_close($curl);
        
            //dump('login');
            //dump(htmlspecialchars($html));
        
            $this->curl = &$curl;
            $this->signedIn = true;
            
            return true;
        } else {
        
            return false;
        }
    }
    
    public function __destruct()
    {
        unlink($this->cookieFile);
    }
    
    /**
     * Retreive contacts from service. Retreived data will be ready for save()
     * 
     * @param integer userId - owner of retreived contacts
     * @return array of Warecorp_User_Addressbook
     * 
     * @author Alexey Loshkarev
     */
    public function getContacts($userId)
    {
        
        if (!$this->signedIn) {
            $this->login();
        }
        
        $curl = &$this->curl;
        //dump($this->urlBase . "?v=cl&pnl=a");
        //$curl = curl_init($this->urlBase . "?v=cl&pnl=a");
        curl_setopt($curl, CURLOPT_URL, "http://address.mail.yahoo.com/");
        curl_setopt($curl, CURLOPT_FOLLOWLOCATION, true);
        curl_setopt($curl, CURLOPT_AUTOREFERER, true);
        curl_setopt($curl, CURLOPT_CONNECTTIMEOUT, 10);
        curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($curl, CURLOPT_HEADER, true);
        curl_setopt($curl, CURLOPT_COOKIEJAR, $this->cookieFile);
        //dump(file_get_contents($this->cookieFile));
        
        curl_setopt($curl, CURLOPT_POST, false);
        $html = curl_exec($curl);
        
        //curl_close($curl);
        
        //dump(htmlspecialchars($html));
        
        preg_match('/<span id="showingstatus">.*?(\d+).*?(\d+).*?(\d+).*<\/span>/si', $html, $matches);
        
        //dump($matches);
        $pageSize = $matches[2];
        $pagesCount = ceil($matches[3] / $pageSize);
        $contacts = array();
        
        for($i = 0; $i < $pagesCount; $i++) {
            
            curl_setopt($curl, CURLOPT_URL, "http://address.mail.yahoo.com/?1&clp_s=&clp_g=&clp_c=0&clp_b=$i");
            $html = curl_exec($curl);
            //dump(htmlspecialchars($html));
            
            preg_match_all('/<tr(.*?)span class="contactname"><a(.*?)>(.*?)<\/a><\/span>(.*?)<\/tr>(.*?)<tr(.*?)contactnumbers(.*?)<span>(.*?)<\/span>(.*?)<\/td>/si', $html, $matches, PREG_SET_ORDER);

            array_walk_recursive($matches, 'htmlspecialchars');
            //dump($matches);

            //die();
            foreach($matches as $m) {
                
                if (strpos($m[3], ",") === false) {
                    $firstName = $m[3];
                    $lastName = "";
                } else {
                    @list($lastName, $firstName) = explode(", ", $m[3], 2);
                }
                
                if (trim($m[8])) {
                    preg_match('/<a(.*?)>(.*)<\/a>/i', $m[8], $mm);
                    $email = $mm[2];
                } else {
                    $email = "";
                }
                
                $contactItem = new Warecorp_User_Addressbook_CustomUser();
                $contactItem->setFirstName($firstName);
                $contactItem->setLastName($lastName);
                $contactItem->setEmail($email);
                $contactItem->setContactOwnerId($userId);
            
                $contacts[] = $contactItem;
            }
            
        }
        
        return $contacts;
        
    }
    
    
    
    
    

}
