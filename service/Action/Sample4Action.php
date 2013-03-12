<?php
class Sample4Action extends Action {
	protected function data($event) {
		if (! ($data = Data::access()))
			return;
		
		### 读取数据
		$_SESSION[$event] = $data->read('sample');
	}
}
?>