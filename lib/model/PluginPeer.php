<?php

class PluginPeer extends BasePluginPeer
{

	public static function doCountJoinAllExceptPluginTag(Criteria $criteria, $distinct = false, PropelPDO $con = null, $join_behavior = Criteria::LEFT_JOIN){
		return self::doCountJoinAllExceptPluginTagRelatedByStableTagId($criteria, $distinct, $con, $join_behavior);
	}

	public static function doCountJoinPluginTag(Criteria $criteria, $distinct = false, PropelPDO $con = null, $join_behavior = Criteria::LEFT_JOIN){
		return self::doCountJoinPluginTagRelatedByStableTagId($criteria, $distinct, $con, $join_behavior);
	}

	public static function doSelectJoinAllExceptPluginTag($criteria = null, $con = null, $join_behavior = Criteria::LEFT_JOIN){
		return self::doSelectJoinAllExceptPluginTagRelatedByStableTagId($criteria, $con, $join_behavior);
	}

	public static function doSelectJoinPluginTag($criteria = null, $con = null, $join_behavior = Criteria::LEFT_JOIN){
		return self::doSelectJoinPluginTagRelatedByStableTagId($criteria, $con, $join_behavior);
	}

	public static function retrieveBySlug($slug, $criteria = null){
		$c = is_null($criteria) ? new Criteria() : clone $criteria;
		$c->add(self::SLUG, $slug);
		return self::doSelectOne($c);
	}

	public static function retrieveByGit($user, $repo, $criteria = null){
		$c = is_null($criteria) ? new Criteria() : clone $criteria;
		$c->add(self::GITHUBUSER, $user)->add(self::GITHUBREPO, $repo);
		return self::doSelect($c);
	}

}
