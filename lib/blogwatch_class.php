<?
class BlogWatch extends ElggEntity {
	public $subscribers;
	
	public function __construct($guid = null) {
		
		$this->initialise_attributes();
		if ($guid instanceof stdClass) {
			$this->load($guid->guid);
		}
		else if (is_string($guid)) {
		}
		else if ($guid instanceof BlogWatch) {
		}
		else if ($guid instanceof ElggEntity) {
		}
		else if (is_numeric($guid)) {
		}
	}
	
	protected function initialise_attributes() {
		parent::initialise_attributes();
		
		// Metadata instance variables
		$this->type = "object";
		$this->subtype = "blogwatch";
		$this->access_id = 2;
	}

	public function save() {
		global $CONFIG;
		
		if (!parent::save()) {
//			return false;
		}
		
		if (count($this->subscribers) == 0) {
			return true;
		}
		
		foreach ($this->subscribers as $key => $username) {
			// Add any new subscribers to the database
			if (!get_data_row("SELECT blog_guid from {$CONFIG->dbprefix}blogwatch where blog_guid = {$this->watched_guid} and username = '{$username}'")) {
				$result = insert_data("INSERT into {$CONFIG->dbprefix}blogwatch (blog_guid, blog_url, username) values ('{$this->watched_guid}', '{$this->watched_url}', '{$username}')");
			}
		}
		
		return true;
	}
	
	public function load($guid) {
		global $CONFIG;
		
		if (!parent::load($guid)) 
			return false;
			
		/* ElggAnnotation::clear_annotations calls get_entity() multiple times which means we'll get loaded with no metadata
		 * during a delete.
		 */
		if ($this->watched_guid != "") {
			$rows = get_data("SELECT * from {$CONFIG->dbprefix}blogwatch where blog_guid={$this->watched_guid}");
			foreach ($rows as $row) {
				$objarray = (array)$row;
				$this->subscribers[] = $objarray['username'];
			}
		}
		
		return true;
	}
	
	public function delete() {
		global $CONFIG;
		delete_data("DELETE from {$CONFIG->dbprefix}blogwatch where blog_guid={$this->watched_guid}");
		return parent::delete();
	}
	
	public function add_subscriber($username) {
		$this->subscribers[] = $username;
		$this->save();
	}
	
	public function remove_subscriber($username) {
		global $CONFIG;
		foreach ($this->subscribers as $key => $value) {
			if ($value == $username) {
				unset($this->subscribers[$key]);
				delete_data("DELETE from {$CONFIG->dbprefix}blogwatch where blog_guid={$this->watched_guid} and username = '$username'");
			}
		}
		
		if (!$this->has_subscribers()) {
			$this->delete();
		}
	}
	
	public function has_subscribers() {
		$this->debug("HERE1");
		if (count($this->subscribers) > 0) return true;
		return false;
	}
	
	public function is_subscriber($username) {
		foreach ($this->subscribers as $key => $value) {
			if ($value == $username) {
				return true;
			}
		}
		
		return false;
	}
	
	public function new_comment() {
		global $CONFIG;
		$now = (int)strtotime("now");
		$result = update_data("UPDATE {$CONFIG->dbprefix}blogwatch set updated='{$now}' where blog_guid = {$this->watched_guid}");
	}
	
	public function get_subscribers() {
		return implode(",", array_values($this->subscribers));
	}
	
	public function dump() {
		$fd = fopen("/tmp/bw", "w");
		foreach ($this->subscribers as $key => $username) {
			fwrite($fd, "subscriber : $key = $username\n");
		}
		fclose($fd);
	}
	
	public function debug($m) {
		$fd = fopen("/tmp/debug", "a+");
		fwrite($fd, $m."\n");
		fclose($fd);
	}
}
?>