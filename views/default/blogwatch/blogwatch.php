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

require_once($CONFIG->pluginspath."blogwatch/lib/blogwatchlib.php");
?>
<script type="text/javascript" src="jquery-latest.pack.js"></script>
<script type="text/javascript" src="thickbox.js"></script>
<style type="text/css" media="all">
@import "thickbox.css";
</style>

<?
if (isloggedin() && (stristr($_SERVER['REQUEST_URI'], "read"))) {
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
			<input name="blogwatch_subscribe_button" type="submit" class="submit_button" value="Subscribe to this post" />
		<? } else { ?>
			<input name="blogwatch_unsubscribe_button" type="submit" class="submit_button" value="Unsubscribe from this post" />
		<? } ?>
		<? if (blog_has_subscribers($vars['entity']->getGUID())) { ?>
			<input alt="<? echo $vars['url']."action/blogwatch/subscribers" ?>?blog_guid=<? echo $vars['entity']->getGUID() ?>&height=150&width=400" title="People who are subscribed to this post" class="thickbox" type="button" value="Show subscribers" />
			<!--
			<input name="show_subscribers_button" type="submit" class="submit_button" value="Show subscribers" />
			-->
		<? } ?>
		<input type="hidden" name="blog_guid" value="<? echo $vars['entity']->getGUID() ?>"/>
		<input type="hidden" name="blog_url" value="<? echo $vars['entity']->getURL() ?>"/>
	</form>
</div>
<?
} // if (isloggedin())
?>
