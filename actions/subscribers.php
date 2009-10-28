<?
require_once($CONFIG->pluginspath."blogwatch/lib/blogwatchlib.php");

global $CONFIG;

$subscribers = get_subscribers(get_input("blog_guid"));
if ($subscribers != null) {
	?><div class="contentWrapper singleview"><?
	foreach ($subscribers as $user) {
		echo "<div style=\"float: left; padding: 5px;\">";
		echo "<a href=\"{$CONFIG->wwwroot}/pg/profile/{$user->username}\"><img src=\"{$user->getIcon("small")}\" title=\"{$user->username}\"/></a><br />";
		echo "{$user->username}";
		echo "</div>";
	}
	?></div><?
}
?>