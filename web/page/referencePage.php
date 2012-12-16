	<div class="box">
		<div class="t">文件结构</div>
		<p><span class="info">/bai/php/system/</span>：系统文件目录，包含系统核心类和配置文件</p>
		<p><span class="info">/bai/php/extend/</span>：扩展文件目录，包含扩展文件</p>
		<p><span class="info">/bai/css/</span>：系统样式目录，包含系统样式文件</p>
		<p><span class="info">/bai/js/</span>：系统脚本目录，包含系统JavaScript文件</p>
		<p><span class="info">/cache/</span>：缓存目录</p>
		<p><span class="info">/log/</span>：日志目录</p>
		<p><span class="info">/web/action/</span>：响应目录，包含扩展的Action文件</p>
		<p><span class="info">/web/style/</span>：资源目录，包含自定义的css、js和图片等文件</p>
		<p><span class="info">/web/page/</span>：页面目录，包含自定义的响应页面文件</p>
	</div>
	<div class="box">
		<div class="t">核心类说明</div>
		<p><span class="info">Flow</span>：流程类共有基类</p>
		<p><span class="info">Work</span>：工场类共有基类</p>
		<p><span class="info">Log</span>：日志工场，生成日志信息并输出日志</p>
		<p><span class="info">Data</span>：数据工场，连接数据库并访问数据</p>
		<p><span class="info">Check</span>：检验工场，检验输入内容并返回检验结果</p>
		<p><span class="info">Cache</span>：缓存工场，更新文件缓存或提取文件缓存</p>
		<p><span class="info">Issue</span>：分派流程，响应事件并分派至相应处理</p>
		<p><span class="info">Action</span>：处理流程，检验输入、访问数据并分派至相应页面</p>
		<p><span class="info">Page</span>：页面流程，格式化信息并输出最终页面</p>
	</div>
	<div class="box">
		<div class="t">系统函数列表</div>
		<p><span class="info">cFlow($event, $source = 'Issue')</span>：请求响应文件</p>
		<p><span class="info">cLoad($pagename, $_param = null, $_css = '', $print = true)</span>：导入页面文件</p>
		<p><span class="info">cRead($item, $array = null)</span>：读取项目值</p>
		<p><span class="info">cWrite($item, $array = null)</span>：输出项目值</p>
		<p><span class="info">cWriteEach()</span>：输出一列项目值</p>
		<p><span class="info">cInput($event, $item, $print = true)</span>：根据检验内容生成提示信息</p>
	</div>
