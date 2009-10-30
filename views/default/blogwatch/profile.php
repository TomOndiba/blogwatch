<?
/**
 * profile
 * 
 * @package Blogwatch
 * @license http://www.gnu.org/licenses/old-licenses/gpl-2.0.html GNU Public License version 2
 * @author Alistair Young <alistair@codebrane.com>
 * @copyright codeBrane 2009
 * @link http://codebrane.com/blog/
 */

require_once($CONFIG->pluginspath."blogwatch/lib/blogwatchlib.php");

// elgg_echo doesn't work inline
$subscriptions_button_text = elgg_echo("blogwatch:view:profile:status:subscriptions:button");
$subscriptions_popup_title = elgg_echo("blogwatch:view:profile:subscriptions:popup:title");
?>
<?
if (user_has_subscriptions($vars['user']->username)) {
?>

	<input alt="<? echo $vars['url']."action/blogwatch/subscriptions" ?>?username=<? echo $vars['user']->username ?>&height=150&width=400" title="<? echo $subscriptions_popup_title ?>" class="thickbox" type="button" value="<? echo $subscriptions_button_text ?>" />

<? } ?>
