<?php
	functions string_to_array($str_ids) {
		$arr_ids = array_map('intval', explode(',', $str_ids));
		return $arr_ids;
	}
	functions array_to_string($arr_ids) {
		$str_ids = implode(",",$arr_ids);
		return $str_ids;
	}
?>