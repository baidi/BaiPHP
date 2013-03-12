<?php
/**
 * Bai事件处理流程：download
 * 下载
 *
 * @author 白晓阳
 */
class DownloadAction extends Action
{
	protected function check($event)
	{
		$pick = cRead('pick');
		### 检验文件是否存在
		if (! $pick || ! file_exists(_ISSUE.'/download/'.$pick))
		{
			$message = Log::logs('filename', 'Event');
			$_REQUEST['error'] = $message;
			return $message;
		}
		return false;
	}

	protected function data($event)
	{
		$pick = cRead('pick');
		$message = $this->log->messagef($event, $pick);
		$this->log->entrust($message, Log::L_DEBUG);

		### 头消息
		$finfo = new finfo(FILEINFO_MIME);
		ob_clean();
		header('Content-Type: '.$finfo->file(_ISSUE.'/download/'.$pick));
		header('Content-Length: '.filesize(_ISSUE.'/download/'.$pick));
		header('Content-Disposition: attachment; filename="'
				.str_replace('+', '%20', urlencode($pick)).'";');
		### 读取文件
		readfile(_ISSUE.'/download/'.$pick);
	}
	
	protected function page($event)
	{
		if (cRead('error'))
		{
			parent::page($event);
		}
	}
}
?>
