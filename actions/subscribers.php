<?
/**
 * subscribers
 * 
 * @package Blogwatch
 * @license http://www.gnu.org/licenses/old-licenses/gpl-2.0.html GNU Public License version 2
 * @author Alistair Young <alistair@codebrane.com>
 * @copyright codeBrane 2009
 * @link http://codebrane.com/blog/
 */

require_once($CONFIG->pluginspath."blogwatch/lib/blogwatchlib.php");

global $CONFIG;

$subscribers = get_subscribers(get_input("blog_guid"));
if ($subscribers != null) {
	$icon_size = "medium";
	if (count($subscribers) > 10) {
		$icon_size = "small";
	}
	
	?><div class="contentWrapper singleview"><?
	foreach ($subscribers as $user) {
		echo "<div style=\"float: left; padding: 5px;\">";
		echo "<a href=\"{$CONFIG->wwwroot}/pg/profile/{$user->username}\"><img src=\"{$user->getIcon("".$icon_size."")}\" title=\"{$user->username}\"/></a><br />";
		echo "{$user->username}";
		echo "</div>";
	}
	?></div><?
}
?>