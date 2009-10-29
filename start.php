<?
/**
 * start
 * 
 * @package Blogwatch
 * @license http://www.gnu.org/licenses/old-licenses/gpl-2.0.html GNU Public License version 2
 * @author Alistair Young <alistair@codebrane.com>
 * @copyright codeBrane 2009
 * @link http://codebrane.com/blog/
 */

require_once($CONFIG->pluginspath . "blogwatch/lib/blogwatchlib.php");

global $CONFIG;

/**
 * Initialises the plugin
 */
function blogwatch_init() {
	global $CONFIG;
	
	extend_view("object/blog", "blogwatch/blogwatch");
	extend_view("forum/viewposts", "blogwatch/blogwatch", 1);
	extend_view('metatags', 'blogwatch/metatags');
	if(isloggedin()) {
		extend_view('profile/status', 'blogwatch/profile');
	}
}

// Register our event handlers
register_elgg_event_handler("init", "system", "blogwatch_init");
register_elgg_event_handler('annotate', 'object', 'blogwatch_new_comment');

// Register our cron handler
register_plugin_hook('cron', "fiveminute", "blogwatch_cron");

// Register our actions
register_action("blogwatch/form", false, $CONFIG->pluginspath . "blogwatch/actions/form.php");
register_action("blogwatch/subscribers", false, $CONFIG->pluginspath . "blogwatch/actions/subscribers.php");
register_action("blogwatch/subscriptions", false, $CONFIG->pluginspath . "blogwatch/actions/subscriptions.php");

// Register the BlogWatch entity
register_entity_type('object', 'blogwatch');
add_subtype('object', 'blogwatch', 'BlogWatch');

?>
