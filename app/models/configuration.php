<?php
class configuration extends models{
	public function getOption($extra=NULL) {
		$sql = "SELECT * FROM configurations WHERE name='$extra'";
		$valid = $this->findBySql($sql);
        if(empty($valid) == false)
            return $valid;
        return 0;
	}
	public function validateLogin($data) {
		$valid = $this->findBySql("SELECT password FROM configurations WHERE password='".md5($data["password"])."'");
		if(empty($valid) == false)
			return true;
		return false;
	} 
}
?>
