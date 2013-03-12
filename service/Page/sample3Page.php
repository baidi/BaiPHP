		<h2>案例3</h2>
		<div class="box">
			<div class="t">输入验证</div>
			<div id="sampleCheck">
				<div class="row">
					<label class="required">数值（3-5位）:</label>
					<input type="number" value="<?php echo cRead('sampleInt'); ?>" <?php cInput('sampleCheck', 'sampleInt'); ?> />
				</div>
				<div class="row">
					<label class="required">字母（3-10位）:</label>
					<input type="text" value="<?php echo cRead('sampleLetter'); ?>" <?php cInput('sampleCheck', 'sampleLetter'); ?> />
				</div>
				<div class="row">
					<input type="button" value="验证" onclick="jss('sampleCheck', '#sampleCheck');" />
				</div>
			</div>
		</div>
