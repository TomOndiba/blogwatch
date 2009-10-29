<?
 /**
  * subscriptions
  * 
  * @package Blogwatch
  * @license http://www.gnu.org/licenses/old-licenses/gpl-2.0.html GNU Public License version 2
  * @author Alistair Young <alistair@codebrane.com>
  * @copyright codeBrane 2009
  * @link http://codebrane.com/blog/
  */

require_once($CONFIG->pluginspath."blogwatch/lib/blogwatchlib.php");

global $CONFIG;

$subscriptions = get_user_subscriptions(get_input("username"));
foreach ($subscriptions as $blog_guid => $blog_url) {
	$blog = get_entity($blog_guid);
?>
	<div style="padding: 2px;"><a href="<? echo $blog_url ?>"><? echo $blog->title ?></a></div>
<?
}

?>
