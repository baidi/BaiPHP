<div class="box">
	<div class="t">
		<span><?php Lang::cut('admin'); ?></span>
	</div>
	<div>
		<p>化简PHP（BaiPHP）由数个流域组成，每个流域包含一组相关的流程，每个流程又由一步一步的处理完成，从而自上而下地实现目标的调度、委托和处理，最终交付结果。</p>
	</div>
</div>
<div class="box">
	<div class="t">
		<a href="<?php echo $this->url('basin', $this->target['basin']); ?>"><?php Lang::cut('basin'); ?></a>
	</div>
	<div>
		<p>流域是系统的基本组成单元，主要用于分流目标和组织文件。它包含一组流程的相关文件，仅允许该流域的目标进入。</p>
		<p>流域主要包含以下目录：</p>
		<ul>
			<li>php</li>
			<li>css</li>
			<li>img</li>
			<li>js</li>
			<li>Action</li>
			<li>Page</li>
			<li>Lang</li>
		</ul>
		<p>初始系统包含bai、basin、admin、test等流域。</p>
	</div>
</div>