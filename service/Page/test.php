<?php
$test = $this->target['TestAction'];
$file  = $this->pick(Test::FILE,  $test);
$cases = $this->pick(Test::CASES, $test);
$codes = $this->pick(Test::CODES, $test);
$caseCount = array_count_values($cases);
$codeCount = array_count_values($codes);
echo '<h3>', '测试场景：', $caseCount[$this->config(Test::TEST, 'success')], '/', count($cases), '</h3><div>';
foreach ($cases as $case => $result)
{
	echo $result;
}
echo '</div>';
echo '<h3>', '测试代码：', $codeCount[1], '/', $codeCount[1] + $codeCount[-1], '</h3><div>';
$lines = file($file, true);
echo '<pre style="border:1px solid #f0f0f0;padding-left:20px;"><ol>';
$i = 1;
foreach ($lines as $line)
{
	$result = $this->pick($i++, $codes);
	if ($result > 0)
	{
		echo '<li style="background-color:#f0fff0;">', htmlspecialchars($line), '</li>';
		continue;
	}
	if ($result == -1)
	{
		echo '<li style="background-color:#fff0f0;">', htmlspecialchars($line), '</li>';
		continue;
	}
	if ($result == -2)
	{
		echo '<li style="background-color:#f0f0f0;">', htmlspecialchars($line), '</li>';
		continue;
	}
	echo '<li>', htmlspecialchars($line), '</li>';
}
echo '</ol></pre>';
