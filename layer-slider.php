<?php 

// Ensure that Layer Slider is installed
if (!defined('LS_PLUGIN_VERSION'))
	return;

$slider = get_post_meta(get_the_ID(), '_franklin_layer_slider', true );

if ($slider) :

	layerslider($slider);

endif;
