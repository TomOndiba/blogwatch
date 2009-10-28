<?
 /**
  * Blogwatch
  * 
  * @package Blogwatch
  * @license http://www.gnu.org/licenses/old-licenses/gpl-2.0.html GNU Public License version 2
  * @author Alistair Young <alistair@codebrane.com>
  * @copyright codeBrane 2009
  * @link http://codebrane.com/blog/
  */

require_once($CONFIG->pluginspath . "blogwatch/lib/blogwatchlib.php");

global $CONFIG;

function blogwatch_init() {
	global $CONFIG;
	extend_view("object/blog", "blogwatch/blogwatch");
	extend_view("forum/viewposts", "blogwatch/blogwatch", 1);
	extend_view('metatags', 'blogwatch/metatags');
}

function blogwatch_inspector($event, $object_type, $object) {
//      if ($object->subtype == 4) {
                $fd = fopen("/tmp/e", "a+");
                fwrite($fd, "event = ".$event."\n");
                fwrite($fd, "object_type = ".$object_type."\n");
                fwrite($fd, "object = ".$object."\n");
                fwrite($fd, "subtype = ".$object->subtype."\n");
                fwrite($fd, "getSubtype = ".$object->getSubtype()."\n");
                fwrite($fd, "title = ".$object->title."\n");
                fwrite($fd, "tags = ".$object->tags."\n");
                fwrite($fd, "description = ".$object->description."\n\n");
                fclose($fd);
//      }

        return true;
}

register_elgg_event_handler("init", "system", "blogwatch_init");
register_elgg_event_handler('annotate', 'object', 'blogwatch_new_comment');
//register_elgg_event_handler('all', 'object', 'blogwatch_inspector');

register_plugin_hook('cron', "fiveminute", "blogwatch_cron");

register_action("blogwatch/form", false, $CONFIG->pluginspath . "blogwatch/actions/form.php");
register_action("blogwatch/subscribers", false, $CONFIG->pluginspath . "blogwatch/actions/subscribers.php");

register_entity_type('object', 'blogwatch');
add_subtype('object', 'blogwatch', 'BlogWatch');

?>
