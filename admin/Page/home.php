<div class="box">
	<div class="t">
		<span>结构</span>
	</div>
	<div>
		<p>化简PHP（BaiPHP）由数个流域组成，每个流域包含一组相关的流程，每个流程又由一步一步的处理构成，从而自上而下地实现目标的调度、委托和处理，最终交付结果。</p>
		<div class="basin w300">
			<div class="b"><?php Lang::cut('basin'); ?></div>
			<div class="flow">
				<div class="b"><?php Lang::cut('flow'); ?></div>
				<div>
					<span class="process"><?php Lang::cut('process'); ?></span>
					<span class="process"><?php Lang::cut('process'); ?></span>
					<span class="process"><?php Lang::cut('process'); ?></span>
					<span class="process">……</span>
				</div>
			</div>
			<div class="flow">
				<span class="b"><?php Lang::cut('flow'); ?></span>
				<div>
					<span class="process"><?php Lang::cut('process'); ?></span>
					<span class="process"><?php Lang::cut('process'); ?></span>
					<span class="process"><?php Lang::cut('process'); ?></span>
					<span class="process">……</span>
				</div>
			</div>
		</div>
	</div>
</div>
<div class="box">
	<div class="t">
		<a href="<?php echo $this->url('basin', $this->target['basin']); ?>"><?php Lang::cut('basin'); ?></a>
	</div>
	<div>
		<p>流域是系统的基本组成单元，主要用于分流目标和组织文件。它由一组流程及其相关文件组成，仅允许该流域的目标进入。</p>
		<p>流域主要包含php、css、img、js、Action、Page、Lang等目录。</p>
		<p>化简PHP（BaiPHP）初始包含bai、basin、admin、test等流域。</p>
	</div>
</div>
<div class="box">
	<div class="t">
		<span><?php Lang::cut('flow'); ?></span>
	</div>
	<div>
		<p>流程是对某一目标的响应，由一系列处理构成，初始流程主要包含控制流程（Control）、处理流程（Action）、页面流程（Page）及相应页面。</p>
		<p>一般由处理流程（Action）提供逻辑，由相应页面提供显示内容。</p>
	</div>
	<span class="q p">
		<span class="tg b">::</span>
		<span>流程隶属于流域，只能在流域下查看。</span>
	</span>
</div>
<div class="box">
	<div class="t">
		<span><?php Lang::cut('process'); ?></span>
	</div>
	<div>
		<p>处理是实现目标的具体步骤，可以是流程及其方法，也可以是工场。</p>
		<p>处理的具体实现由流程决定。</p>
	</div>
	<span class="q p">
		<span class="tg b">::</span>
		<span>处理隶属于流程，只能在流程下查看。</span>
	</span>
</div>