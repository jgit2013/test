<?php
//echo '<pre>'; print_r($shd_array);

$unser_shd_array = unserialize($redis->hget('mytables', 'table1'));

echo '<pre>'; print_r($unser_shd_array);
