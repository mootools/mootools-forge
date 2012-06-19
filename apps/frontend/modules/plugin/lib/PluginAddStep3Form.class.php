<?php

/**
 * Plugin adding step 3
 *
 * @package forge
 * @subpackage form
 * @author Guillermo Rauch
 **/
class PluginAddStep3Form extends PluginAddStepForm
{	
	
	protected $gitTrees = array();
	protected $gitFileList = array();
	
	public function configure(){
		$this->setWidgets(array(
			'user' => new sfWidgetFormInput,
			'repository' => new sfWidgetFormInput
		));
	
		$this->setValidators(array(
			'user' => new sfValidatorString,
			'repository' => new sfValidatorString
		));
		
		$c = new sfValidatorCallback(array('callback' => array($this, 'doValidate')));
		$c->addOption('execute-if-passed', true);
		$this->validatorSchema->setPostValidator($c);
	}

	public function doValidate($validator, $values){
		$files = array(
			'package.yml' => 'file',
			'README.md' => 'file',
			'Source' => 'dir'
		);
		
		// the validator just checks if files are in the repos. Because github changed
		// everything with version 3 this needs a complete rewrite.
		if (substr_count($values['repository'], '.git') > 0)
		{
			$values['repository'] = substr($values['repository'], 0, strrpos($values['repository'], '.'));
		}

		$contentList = $this->fetch(sprintf('https://api.github.com/repos/%s/%s/contents/%s', $values['user'], $values['repository'], $file));

		if (($contentList = @json_decode($contentList)) !== false)
		{
			
			foreach ($files as $filename => $filetype)
			{
				foreach ((array) $contentList as $contentItem)
				{
					if (isset($files[$contentItem->name]) && $files[$contentItem->name]['type'] != $contentItem->type)
					{
						$this->gitFileList[$contentItem->name] = $contentItem;
					}
				}

			}

			if (count($this->gitFileList) < count($this->gitFileList))
			{
				throw new sfValidatorError($validator, sprintf('Could not find one of necessary files (%s) <a href="http://github.com/%s/%s/blob/master/">%s / %s</a> not found in repository root.', implode(', ', array_keys($files)), $values['user'], $values['repository'], $values['user'], $values['repository']));
			}
		}		
	
		return $values;
	}
	
	/**
	 * Returns the tree hashes for the latest commit of each required file.
	 *
	 * @return array Git trees
	 * @author Guillermo Rauch
	 */
	public function getGitTrees(){
		return $this->gitTrees;
	}
	
} // END class PluginAddStep2Form extends ForgeForm