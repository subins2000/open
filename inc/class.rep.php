<?php
/* O.V.B.S 29 April 2014 */
class ORep extends Open {
	private $rep = array(
		"cmt" => 5, /* A Comment On Post */
		"pst" => 10, /* Like On Post */
		"cmtl" => 5 /* Like On Comment */
	);
	private $cU;
	public function getRep($user=null){
		if($user != null){
			$this->cU 	= $user;
			$cmtRep		= $this->getCMTRep();
			$pstRep		= $this->getPSTRep();
			$cmtlRep	= $this->getCMTLRep();
			$totalRep	= $cmtRep + $pstRep + $cmtlRep;
			return array(
				"total" => $totalRep,
				"cmt"   => $cmtRep,
				"pst"   => $pstRep,
				"cmtl"  => $cmtlRep,
				"count" => array(
					"cmt"  => $cmtRep/$this->rep['cmt'],
					"pst"  => $pstRep/$this->rep['pst'],
					"cmtl" => $cmtlRep/$this->rep['cmtl']
				)
			);
		}
	}
	
	private function getCMTRep(){
		$sql = $this->dbh->prepare("SELECT COUNT(`pid`) FROM `comments` WHERE `pid` IN (SELECT `id` FROM `posts` WHERE `uid`=?)");
		$sql->execute(array($this->cU));
		$cmtRep = $sql->fetchColumn() * $this->rep['cmt'];
		return $cmtRep;
	}
	
	private function getPSTRep(){
		/* Time For Post Likes */
		$sql = $this->dbh->prepare("SELECT COUNT(`pid`) FROM `likes` WHERE `pid` IN (SELECT `id` FROM `posts` WHERE `uid`=?)");
		$sql->execute(array($this->cU));
		$pstRep = $sql->fetchColumn() * $this->rep['pst'];
		return $pstRep;
	}
	
	private function getCMTLRep(){
		/* Time For Comment Likes */
		$sql = $this->dbh->prepare("SELECT COUNT(`cid`) FROM `commentLikes` WHERE `cid` IN (SELECT `id` FROM `comments` WHERE `uid`=?)");
		$sql->execute(array($this->cU));
		$count = $sql->fetchColumn();
		$cmtlRep = $count * $this->rep['cmtl'];
		return $cmtlRep;
	}
	
	public function getTopPosts(){
		$sql = $this->dbh->prepare("SELECT `id`, `likes` FROM `posts` WHERE `uid`=? ORDER BY `likes` DESC LIMIT 5");
		$sql->execute(array(curUser));
		$r = array();
		foreach($sql->fetchAll(PDO::FETCH_ASSOC) as $k=>$v){
			if($v['likes'] != 0){
				$r[$k]=array(
					"id"  => $v['id'],
					"rep" => $v['likes'] * $this->rep['pst']
				);
			}
		}
		return $r;
	}
	
	public function getTopComments(){
		$sql = $this->dbh->prepare("SELECT `id`, `likes`, `comments`, `pid` FROM `comments` WHERE `uid`=? ORDER BY `likes` DESC LIMIT 5");
		$sql->execute(array(curUser));
		$result = $sql->fetchAll(PDO::FETCH_ASSOC);
		$comments = array();
		foreach($result as $k => $v){
			if($v['likes']!=0){
				$comments[$k] = array(
					"id"  => $v['id'],
					"cmt" => $v['cmt'],
					"pid" => $v['pid'],
					"rep" => $v['likes'] * $this->rep['cmt']
				);
			}
		}
		return $comments;
	}
}
?>