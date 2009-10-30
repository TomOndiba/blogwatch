<?
/**
 * blogwatch
 * 
 * @package Blogwatch
 * @license http://www.gnu.org/licenses/old-licenses/gpl-2.0.html GNU Public License version 2
 * @author Alistair Young <alistair@codebrane.com>
 * @copyright codeBrane 2009
 * @link http://codebrane.com/blog/
 */

require_once($CONFIG->pluginspath."blogwatch/lib/blogwatchlib.php");

// elgg_echo doesn't work inline
$subscribe_button_text = elgg_echo("blogwatch:view:blogwatch:subscribe:button");
$unsubscribe_button_text = elgg_echo("blogwatch:view:blogwatch:unsubscribe:button");
$subscribers_button_text = elgg_echo("blogwatch:view:blogwatch:subscribers:button");
$subscribers_pop_title = elgg_echo("blogwatch:view:blogwatch:subscribers:popup:title");
?>

<?
if (isloggedin() && ((stristr($_SERVER['REQUEST_URI'], "read")) || (stristr($_SERVER['REQUEST_URI'], "topicposts")))) {
	$subscribed = "no";
	if (is_blog_subscriber($vars['entity']->getGUID(), $vars['user']->username)) {
		$subscribed = "yes";
	}
?>

<script type="text/javascript">
	function subscribe() {
		if (document.blogwatch_form.blogwatch_subscribe.checked) {
			window.alert("subscribe!");
		}
		else {
			window.alert("unsubscribe!");
		}
	}
</script>

<div class="contentWrapper singleview">
	<form name="blogwatch_form" method="post" action="<? echo $vars['url']."action/blogwatch/form" ?>">
		<? if ($subscribed == "no") { ?>
			<input name="blogwatch_subscribe_button" type="submit" class="submit_button" value="<? echo $subscribe_button_text ?>" />
		<? } else { ?>
			<input name="blogwatch_unsubscribe_button" type="submit" class="submit_button" value="<? echo $unsubscribe_button_text ?>" />
		<? } ?>
		<? if (blog_has_subscribers($vars['entity']->getGUID())) { ?>
			<input alt="<? echo $vars['url']."action/blogwatch/subscribers" ?>?blog_guid=<? echo $vars['entity']->getGUID() ?>&height=300&width=800" title="<? echo $subscribers_pop_title ?> <? echo $vars['entity']->title ?>" class="thickbox" type="button" value="<? echo $subscribers_button_text ?>" />
		<? } ?>
		<input type="hidden" name="blog_guid" value="<? echo $vars['entity']->getGUID() ?>"/>
		<input type="hidden" name="blog_url" value="<? echo $vars['entity']->getURL() ?>"/>
	</form>
</div>
<?
} // if (isloggedin())
?>
