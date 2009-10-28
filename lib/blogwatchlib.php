<?
require_once("blogwatch_class.php");

function load_blog($blog_guid) {
	$blog_watch_objects = get_entities_from_metadata("watched_guid", $blog_guid);
	if ($blog_watch_objects) {
		if ($blog_watch_objects[0] instanceof BlogWatch) {
			return $blog_watch_objects[0];
		}
	}
	
	return null;
}

function is_blog_subscriber($blog_guid, $username) {
	if (($blog_watch = load_blog($blog_guid)) != null) {
		return $blog_watch->is_subscriber($username);
	}
	
	return false;
}

function blogwatch_new_comment($event, $object_type, $object) {
	if ($object->getSubtype() == "blog") {
		if (($blog_watch = load_blog($object->getGUID())) != null) {
			$blog_watch->new_comment();
		}
	}
	
	return true;
}

function blog_has_subscribers($blog_guid) {
	if (($blog_watch = load_blog($blog_guid)) != null) {
		return true;
	}
	return false;
}

function get_subscribers($blog_guid) {
	if (($blog_watch = load_blog($blog_guid)) != null) {
		$usernames = explode(",", $blog_watch->get_subscribers());
		$subscribers;
		foreach ($usernames as $username) {
			$subscribers[] = get_user_by_username($username);
		}
		
		return $subscribers;
	}
	
	return null;
}

function blogwatch_cron($hook, $entity_type, $returnvalue, $params) {
	test("blogwatch_cron");
	global $CONFIG;
	$resulttext = elgg_echo("blogwatch:notifier");
	
	// Default is 5 minutes
	$interval = (5 * 60);
	
	$now = (int)strtotime("now");
	
	$i = $now - $interval;

	test("SELECT * from {$CONFIG->dbprefix}blogwatch where updated < {$i}");
	
	$rows = get_data("SELECT * from {$CONFIG->dbprefix}blogwatch where updated < {$i}");
	foreach ($rows as $row) {
		$objarray = (array)$row;
		$user = get_user_by_username($objarray['username']);
		test("this needs emailed : ".$user->email);
	}
	
	return $returnvalue . $resulttext;
}

function test($m) {
	$fd = fopen("/tmp/test", "a+");
	fwrite($fd, "{$m}\n");
	fclose($fd);
}
?>