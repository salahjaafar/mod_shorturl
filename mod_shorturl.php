<?php
/**
 * Short URL! Module Entry Point
 * Autor : Salah JAAFAR
 * @package    Joomla.Tutorials
 * @subpackage Modules
 * @license    GNU/GPL, see LICENSE.php
 * mod_shorturl is free software. This version may have been modified pursuant
 * to the GNU General Public License, and as distributed it includes or
 * is derivative of works licensed under the GNU General Public License or
 * other free or open source software licenses.
 */

// No direct access
defined('_JEXEC') or die();
$joomlaApp = JFactory::getApplication()->input;
$option = $joomlaApp->getCmd('option');
$view = $joomlaApp->getCmd('view');
$layout = $joomlaApp->getCmd('layout');
$class = '';
$db = JFactory::getDBO();
if ($option == 'com_content' && $view == 'article') {

    // The following should retrieve the date down to your desired resolution.
    // If you want a daily code, retrieve only the date-specific parts
    // For hourly resolution, retrieve the date and hour, but no minute parts
    $today = date('Y-m-d H:i:s'); // e.g. "03.10.01"
    
    $hashurl = uniqid(); // Hash it
    $comp = 1;
    while ($comp > 0){
    $query = "SELECT `comment` FROM `#__redirect_links` WHERE `comment`= '$hashurl' ORDER BY `id` DESC ";
    // echo $query;
    $db->setQuery($query);
    $row = $db->loadRowList();
    $comp = count($row);
    }
    $uri = JURI::getInstance();
    $path = JURI::base();
    $shurl = JURI::base() . $hashurl;

    $query = "SELECT `new_url` FROM `#__redirect_links` WHERE `new_url` = '$uri'";
    // echo $query;
    $db->setQuery($query);
    $row = $db->loadRowList();
    $num = count($row);
    // echo $num;
    if ($num == 0) {
        $query = "INSERT INTO `#__redirect_links` (`id`, `old_url`, `new_url`, `referer`, `comment`, `hits`, `published`, `created_date`, `modified_date`, `header`) VALUES(NULL, '$shurl', '$uri', '', '$hashurl', '0', '1', '$today', '0000-00-00 00:00:00', '301');";
        $db->setQuery($query);
        $db->execute();
        echo $shurl;
    }
    if ($num > 0) {
        $query = "SELECT `comment` FROM `#__redirect_links` WHERE `new_url`  = '$uri'";
        // echo $query;
        $db->setQuery($query);
        $row = $db->loadRowList();
        echo $path.$row[0][0];
    }
    
}
?>

