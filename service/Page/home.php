<?php
$extensions = array(
	'pdo' => 'Data',
	'pdo_mysql' => 'Data',
	'mbstring' => 'Check',
	'xdebug' => 'Test',
	'gd' => 'Check',
);
?>
<div class="box">
<button >button</button>
<q>1234</q>
<progress value="22" max="100"></progress>
<textarea rows="" cols="">123456</textarea>
<hr>
</div>
<div class="box">
	<div class="t">依赖模块</div>
	<ul>
	<?php
	foreach ($extensions as $item => $value) {
		if (! extension_loaded($item) && ! dl($item)) {
			echo '<li>', $item, ': <span class="error">', $value, '(尚未安装或尚未启动&#10004)</span></li>';
			continue;
		}
		echo '<li>', $item, ': ', $value, '(&#10003已启用)</li>';
	}
	?>
	</ul>
</div>
<div class="box">
	<div class="t">面向目标</div>
	<p>目标是主体期望的成果，也是主体行为的导向。</p>
	<p>面向目标，把目光从关注程序的功能和实现转移到流程上来，而对功能的实现进行委托和交付，即从怎么做到做什么，从而有效地降低系统的耦合性和复杂度，极大的增加系统的灵活性。</p>
	<div class="m b">面向目标具有以下显著特点：</div>
	<ol>
		<li>对外负责：内部知道怎么做，外部只关注结果。</li>
		<li>内向影响：外部通过委托目标对内部施加影响，内部根据目标的不同进行调整，内部适应外部需求。</li>
		<li>人 性 化：更接近用户行为习惯。</li>
	</ol>
</div>
<div class="box">
	<div class="t">服务-流程-工场 模式</div>
	<p>该模式以面向目标位基础，将程序划分为服务、流程和工场三个部分。</p>
	<ul>
		<li>工场：用于实现程序的功能，用于接受委托并交付处理结果，是程序的基础。</li>
		<li>流程：用于组织工场和其他流程，用于对目标进行分析和分解并进行委托，是程序的核心。</li>
		<li>服务：用于接收和组织外部信息，用于组织目标并进行委托，是程序的界面。</li>
	</ul>
</div>
<div class="box">
	<div class="t">处理流程</div>
	<p>用户的每一次请求，都会触发一个目标，用于存储用户请求的事件与数据，流程依据不同的目标名，匹配相应的子流程、工场和服务，从而完成输入验证、数据访问和页面输出等响应。</p>
	<ol>
		<li>用户发出请求。</li>
		<li>根据用户请求触发目标（Target，默认为home）</li>
		<li>目标将自身向内委托给流程（默认为Control）</li>
		<li>流程对目标进行分析和分解，并委托相应的工场进行处理</li>
		<li>流程继续向内委托目标（默认为Action &#187 Page）</li>
		<li>目标处理完毕，逐级对外交付</li>
		<li>响应完成</li>
	</ol>
</div>