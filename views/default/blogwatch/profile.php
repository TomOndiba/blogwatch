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
?>
<?
if (user_has_subscriptions($vars['user']->username)) {
?>

	<input alt="<? echo $vars['url']."action/blogwatch/subscriptions" ?>?username=<? echo $vars['user']->username ?>&height=150&width=400" title="My Subscriptions" class="thickbox" type="button" value="Show my subscriptions" />

<? } ?>
