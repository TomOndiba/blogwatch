<?
/**
 * BlogWatch utility library
 *
 * This file contains convenience functions that view can use to
 * interact with the underlying BlogWatch instances which 
 * represent blog post and topic notification subscribers.
 *
 * @author Alistair Young <alistair@codebrane.com>
 * @package BlogWatch
 */

require_once("blogwatch_class.php");

/**
 * Loads the BlogWatch class associated with the given entity id
 * @param $blog_guid the GUID of the blog post that is being watched
 * @return BlogWatch instance representing the subscribers of the post
 *         or null if it can't find one
 */
function load_blog($blog_guid) {
	$blog_watch_objects = get_entities_from_metadata("watched_guid", $blog_guid);
	if ($blog_watch_objects) {
		if ($blog_watch_objects[0] instanceof BlogWatch) {
			return $blog_watch_objects[0];
		}
	}
	
	return null;
}

/**
 * Determines whether a user is subscribed to a post or topic
 * @param $blog_guid the GUID of the blog post that is being watched
 * @param $username the username of the user
 * @return true if they are subscribed, otherwise false
 */
function is_blog_subscriber($blog_guid, $username) {
	if (($blog_watch = load_blog($blog_guid)) != null) {
		return $blog_watch->is_subscriber($username);
	}
	
	return false;
}

/**
 * Marks a BlogWatch as having a new comment or reply.
 * This function is an Elgg event handler for annotations.
 * @param $event the event type
 * @param $object_type The type of object (eg "user", "object")
 * @param $object The object itself or null 
 * @return true
 */
function blogwatch_new_comment($event, $object_type, $object) {
	if (($object->getSubtype() == "blog") || ($object->getSubtype() == "groupforumtopic")) {
		if (($blog_watch = load_blog($object->getGUID())) != null) {
			$blog_watch->new_comment();
		}
	}
	
	return true;
}

/**
 * Deletes all subscriptions for a watched entity
 * This function is an Elgg event handler for annotations.
 * @param $event the event type
 * @param $object_type The type of object (eg "user", "object")
 * @param $object The object itself or null 
 * @return true
 */
function blogwatch_delete_subscriptions($event, $object_type, $object) {
	if (($object->getSubtype() == "blog") || ($object->getSubtype() == "groupforumtopic")) {
		if (($blog_watch = load_blog($object->getGUID())) != null) {
			$blog_watch->delete_all_subscribers();
			$blog_watch->delete();
		}
	}
	
	return true;
}

/**
 * Determines whether a post or topic has subscribers
 * @param $blog_guid the GUID of the blog post that is being watched
 * @return true if there are subscribers, otherwise false
 */
function blog_has_subscribers($blog_guid) {
	if (($blog_watch = load_blog($blog_guid)) != null) {
		return true;
	}
	return false;
}

/**
 * Gets all the subscribers of a post or topic
 * @param $blog_guid the GUID of the blog post that is being watched
 * @return array of Elgg User objects representing the subscribers
 *         or null if there are no subscribers
 */
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

/**
 * Determines whether a user has any subscriptions
 * @param $username the username of the user
 * @return true if they have subscriptions, otherwise false
 */
function user_has_subscriptions($username) {
	global $CONFIG;
	$rows = get_data("SELECT * from {$CONFIG->dbprefix}blogwatch where username = '{$username}'");
	if ($rows) {
		return true;
	}
	else {
		return false;
	}
}

/**
 * Gets all the subscriptions for a user
 * @param $username the username of the user
 * @return array representing the user's subscriptions, of the form:
 *         foreach ($subscriptions as $blog_guid => $blog_url)
 */
function get_user_subscriptions($username) {
	global $CONFIG;
	$rows = get_data("SELECT * from {$CONFIG->dbprefix}blogwatch where username = '{$username}'");
	if ($rows) {
		foreach ($rows as $row) {
			$objarray = (array)$row;
			$subscriptions[$objarray['blog_guid']] = $objarray['blog_url'];
		}
		return $subscriptions;
	}
	
	return null;
}

/**
 * Determines whether our database schema is ready
 * @return true if the schema is installed, otherwise false
 */
function is_blogwatch_schema_installed() {
	global $CONFIG;
	try {
		$last_run_row = get_data_row("SELECT * from {$CONFIG->dbprefix}blogwatch_cron");
		return true;
	}
	catch(DatabaseException $de) {
		return false;
	}
}

/**
 * Initialises our database schema
 */
function init_blogwatch_schema() {
	global $CONFIG;
	run_sql_script($CONFIG->path."mod/blogwatch/lib/blogwatch.sql");
	$last_run = (int)strtotime("now");
	insert_data("INSERT into {$CONFIG->dbprefix}blogwatch_cron (last_run) values ('{$last_run}')");
}

/**
 * Notifies users if their subscriptions have been updated with
 * comments or replies. This is an Elgg Plugin Hook.
 * @param $hook The hook being called
 * @param $entity_type The type of entity you're being called on
 * @param $returnvalue The return value. IMPORTANT: Unless you are
 *                     adding to or otherwise changing the return value
 *                     DO NOT RETURN ANYTHING
 * @param $params An array of parameters
 * @return $returnvalue plus a notification message
 */
function blogwatch_cron($hook, $entity_type, $returnvalue, $params) {
	global $CONFIG;
	
	// Sort out when we last ran
	$last_run;
	$last_run_row = get_data_row("SELECT * from {$CONFIG->dbprefix}blogwatch_cron");
	if ($last_run_row) {
		$objarray = (array)$last_run_row;
		$last_run = (int)$objarray['last_run'];
		$now = (int)strtotime("now");
		update_data("UPDATE {$CONFIG->dbprefix}blogwatch_cron set last_run ='{$now}'");
	}
	else {
		$last_run = (int)strtotime("now");
		insert_data("INSERT into {$CONFIG->dbprefix}blogwatch_cron (last_run) values ('{$last_run}')");
	}
	
	$rows = get_data("SELECT * from {$CONFIG->dbprefix}blogwatch where updated > {$last_run}");
	if ($rows) {
		$resulttext = elgg_echo("blogwatch:cron:return:string");
		foreach ($rows as $row) {
			$objarray = (array)$row;
			$user = get_user_by_username($objarray['username']);
			$entity = get_entity($objarray['blog_guid']);
			notify_user($user->guid, $CONFIG->site->guid,
				          $CONFIG->site->name." ".elgg_echo("blogwatch:notify:subject"),
				          elgg_echo('blogwatch:notify:body::header').":\n\n".
									$entity->title."\n".
									$objarray['blog_url'].
				 					"\n\n".
									elgg_echo("blogwatch:notify:body:footer")."\n",
				          null, "");
		}
		
		return $returnvalue . elgg_echo("blogwatch:cron:return:string");
	}
}

function test($m) {
	$fd = fopen("/tmp/test", "a+");
	fwrite($fd, "{$m}\n");
	fclose($fd);
}
?>