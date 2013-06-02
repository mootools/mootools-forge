<?php

class PluginTag extends BasePluginTag
{

	public function addPlugin(Plugin $l){
		return $this->addPluginRelatedByStableTagId($l);
	}

	public function clearPlugins(){
		return $this->clearPluginsRelatedByStableTagId();
	}

	public function countPlugins(Criteria $criteria = null, $distinct = false, PropelPDO $con = null){
		return $this->countPluginsRelatedByStableTagId($criteria, $distinct, $con);
	}

	public function getPlugin(PropelPDO $con = null){
		return $this->getPluginRelatedByPluginId($con);
	}

	public function getPlugins($criteria = null, PropelPDO $con = null){
		return $this->getPluginsRelatedByStableTagId($criteria, $con);
	}

	public function getPluginsJoinAuthor($criteria = null, $con = null, $join_behavior = Criteria::LEFT_JOIN){
		return $this->getPluginsRelatedByStableTagIdJoinAuthor($criteria, $con, $join_behavior);
	}

	public function getPluginsJoinTerm($criteria = null, $con = null, $join_behavior = Criteria::LEFT_JOIN){
		return $this->getPluginsRelatedByStableTagIdJoinTerm($criteria, $con, $join_behavior);
	}

	public function initPlugins($overrideExisting = true){
		return $this->initPluginsRelatedByStableTagId($overrideExisting);
	}

	public function setPlugin(Plugin $v = null){
		return $this->setPluginRelatedByPluginId($v);
	}

	public function setPlugins(PropelCollection $plugins, PropelPDO $con = null){
		return $this->setPluginsRelatedByStableTagId($plugins, $con);
	}

	public function __toString(){
		return $this->getName();
	}

	public function getName($strict = false){
		if ($strict && is_numeric($this->name)) return floatval($this->name);
		return $this->name;
	}

	public function getDownloadLink($type = 'zipball'){
		$plugin = $this->getPlugin();
		return sprintf("http://github.com/%s/%s/%s/%s", $plugin->getGithubuser(), $plugin->getGithubrepo(), $type, $this->getName());
	}

	public function sumDownload($save = true){
		$this->setDownloadsCount($this->getDownloadsCount() + 1);
		if ($save) $this->save();
	}

	public function isCurrent(){
		return $this->getCurrent();
	}

}
