<?php

class PluginTagPeer extends BasePluginTagPeer
{

	public static function doCountJoinPlugin(Criteria $criteria, $distinct = false, PropelPDO $con = null, $join_behavior = Criteria::LEFT_JOIN){
		return self::doCountJoinPluginRelatedByPluginId($criteria, $distinct, $con, $join_behavior);
	}

	public static function doSelectJoinPlugin(Criteria $criteria, $con = null, $join_behavior = Criteria::LEFT_JOIN){
		return self::doSelectJoinPluginRelatedByPluginId($criteria, $con, $join_behavior);
	}

	public function retrieveByName($name, $criteria = null){
		$criteria = is_null($criteria) ? new Criteria : clone $criteria;
		$criteria->add(self::NAME, $name);
		return self::doSelectOne($criteria);
	}

}
