<?php
foreach ($event->Test as $testee => $test)
{
	echo '<div>', '执行测试：', $testee, '</div>';
	echo '<div>', Test::SUCCESS, ' : ', count($test[Test::SUCCESS]), ', ',
			Test::FAILURE, ' : ', count($test[Test::FAILURE]),  ', ',
			Test::ERROR, ' : ', count($test[Test::ERROR]), '</div>';
	$lines = file(_SYSTEM.$testee.'.php', true);
	echo '<pre>';
	$i = 1;
	foreach ($lines as $line)
	{
		if (empty($test[Test::COVERAGE][$i++]))
		{
			echo htmlspecialchars($line);
			continue;
		}
		echo '<div class="bg">', htmlspecialchars($line, true), '</div>';
	}
	echo '</pre>';
}
?>