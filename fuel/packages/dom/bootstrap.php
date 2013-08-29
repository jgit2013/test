<?php

Autoloader::add_core_namespace('Dom');

Autoloader::add_classes(array(
	'Dom\\Dom'          => __DIR__.'/classes/dom.php',
	'Dom\\simple_html_dom'  => __DIR__.'/classes/simple_html_dom.php',
	'Dom\\simple_html_dom_node'  => __DIR__.'/classes/simple_html_dom_node.php',
));
/* End of file bootstrap.php */
