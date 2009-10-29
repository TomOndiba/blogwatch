<?
/**
 * form
 * 
 * @package Blogwatch
 * @license http://www.gnu.org/licenses/old-licenses/gpl-2.0.html GNU Public License version 2
 * @author Alistair Young <alistair@codebrane.com>
 * @copyright codeBrane 2009
 * @link http://codebrane.com/blog/
 */

require_once($CONFIG->pluginspath . "blogwatch/lib/blogwatchlib.php");
require_once($CONFIG->pluginspath . "blogwatch/lib/blogwatch_class.php");

global $CONFIG;
global $SESSION;

$blog_watch = null;
$blog_watch_objects = get_entities_from_metadata("watched_guid", get_input("blog_guid"));

if (!$blog_watch_objects) {
	$blog_watch = new BlogWatch();
	$blog_watch->title = "BlogWatch : ".get_input("blog_guid");
	$blog_watch->description = "BlogWatch : ".get_input("blog_guid");
	$blog_watch->watched_guid = get_input("blog_guid");
	$blog_watch->watched_url = get_input("blog_url");
	$blog_watch->save();
}
else {
	$blog_watch = $blog_watch_objects[0];
}

if (get_input("blogwatch_subscribe_button") != "") {
	$blog_watch->add_subscriber($_SESSION['user']->username);
}
if (get_input("blogwatch_unsubscribe_button") != "") {
	$blog_watch->remove_subscriber($_SESSION['user']->username);
}
else if (get_input("show_subscribers_button") != "") {
}

forward(get_input("blog_url"));
?>