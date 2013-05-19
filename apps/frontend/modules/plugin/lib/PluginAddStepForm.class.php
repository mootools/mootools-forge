<?php

/**
 * Add a plugin form
 *
 * @package forge
 * @subpackage form
 * @author Guillermo Rauch
 **/
class PluginAddStepForm extends ForgeForm
{

	public function __construct($defaults = array(), $options = array(), $CSRFSecret = null){
		return parent::__construct($defaults, $options, false);
	}

} // END class PluginAddStepForm extends ForgeForm
