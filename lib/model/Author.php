<?php

class Author extends BaseAuthor
{

	public function __toString(){
		return $this->getUsername();
	}

	public function getPluginsJoinPluginTag($criteria = null, $con = null, $join_behavior = Criteria::LEFT_JOIN){
		return $this->getPluginsJoinPluginTagRelatedByStableTagId($criteria, $con, $join_behavior);
	}

	public function save(PropelPDO $con = null){
		# Unconfirm email upon change.
		if (!$this->isNew() && in_array(AuthorPeer::EMAIL, $this->modifiedColumns)){
			$this->setConfirmedEmail(false);
		}

		parent::save($con);
	}

	public function hasConfirmedEmail(){
		return $this->getConfirmedEmail();
	}

	public function isAdmin(){
		return $this->getAdmin();
	}

	public function setPasswordPlain($text){
		$this->setPassword(sha1($text));
	}

	public function getFirstName(){
		$parts = explode(' ', $this->getFullName());
		return array_shift($parts);
	}

	public function ownsPlugin($plugin){
		return ($plugin->getAuthorId() == $this->getId());
	}

}
