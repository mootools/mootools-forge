<?php

class Term extends BaseTerm
{

	public function __toString(){
		return $this->getTitle();
	}

	public function getPluginsJoinPluginTag($criteria = null, $con = null, $join_behavior = Criteria::LEFT_JOIN){
		return $this->getPluginsJoinPluginTagRelatedByStableTagId($criteria, $con, $join_behavior);
	}

}

$columns_map = array('from' => TermPeer::TITLE, 'to' => TermPeer::SLUG);

sfPropelBehavior::add('Term', array(
	'sfPropelActAsSluggableBehavior' => array('columns'=>$columns_map, 'separator'=>'_', 'permanent'=>true)
));
