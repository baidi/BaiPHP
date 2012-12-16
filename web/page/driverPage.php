	<div class="box">
		<div class="t">事件（event）</div>
		<p>用户的每一次请求，都会产生一个事件，并在其中的event字段中存储事件名，流程依据不同的事件名，找的相应的子流程、工场和服务，从而完成输入验证、数据访问和页面输出等响应。</p>
		<div class="t">大致流程</div>
		<ul>
			<li>用户发出请求</li>
			<li>跟据用户请求解析出事件（event，默认为home）</li>
			<li>根据事件找到相应的分流流程（默认为Issue）并进行分流</li>
			<li>根据事件找到相应的处理流程（默认为Action）并进行处理</li>
			<li>根据事件找到相应的页面流程（默认为Page）并输出页面</li>
		</ul>
	</div>
