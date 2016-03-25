<?php
/**
 * O.V.B.S 29 April 2014
 */

class ORep extends \Open {
  
  private $rep = array(
    "cmt" => 5, /* A Comment On Post */
    "pst" => 10, /* Like On Post */
    "cmtl" => 5 /* Like On Comment */
  );
  private $cU;
  
  public function __construct($user = null){
    if($user != null){
      $this->cU = $user;
    }
  }
  
  public function getRep(){
    $cmtRep = $this->getCommentRep();
    $pstRep = $this->getPostRep();
    $cmtlRep = $this->getCommentLikeRep();
    $totalRep = $cmtRep + $pstRep + $cmtlRep;
    return array(
      "total" => $totalRep,
      "cmt" => $cmtRep,
      "pst" => $pstRep,
      "cmtl" => $cmtlRep,
      "count" => array(
        "cmt" => $cmtRep/$this->rep['cmt'],
        "pst" => $pstRep/$this->rep['pst'],
        "cmtl" => $cmtlRep/$this->rep['cmtl']
      )
    );
  }
  
  public function getCommentRep(){
    $sql = $GLOBALS['OP']->dbh->prepare("SELECT COUNT(`pid`) FROM `comments` WHERE `pid` IN (SELECT `id` FROM `posts` WHERE `uid`=?)");
    $sql->execute(array($this->cU));
    $cmtRep = $sql->fetchColumn() * $this->rep['cmt'];
    return $cmtRep;
  }
  
  public function getPostRep(){
    /* Time For Post Likes */
    $sql = $GLOBALS['OP']->dbh->prepare("SELECT COUNT(`pid`) FROM `likes` WHERE `pid` IN (SELECT `id` FROM `posts` WHERE `uid`=?)");
    $sql->execute(array($this->cU));
    $pstRep = $sql->fetchColumn() * $this->rep['pst'];
    return $pstRep;
  }
  
  public function getCommentLikeRep(){
    /**
     * Comment Likes
     */
    $sql = $GLOBALS['OP']->dbh->prepare("SELECT COUNT(`cid`) FROM `commentLikes` WHERE `cid` IN (SELECT `id` FROM `comments` WHERE `uid`=?)");
    $sql->execute(array($this->cU));
    $count = $sql->fetchColumn();
    $cmtlRep = $count * $this->rep['cmtl'];
    return $cmtlRep;
  }
  
  public function getTopPosts(){
    $sql = $GLOBALS['OP']->dbh->prepare("SELECT `id`, `likes` FROM `posts` WHERE `uid`=? ORDER BY `likes` DESC LIMIT 5");
    $sql->execute(array($this->cU));
    $r = array();
    foreach($sql->fetchAll(\PDO::FETCH_ASSOC) as $k=>$v){
      if($v['likes'] != 0){
        $r[$k]=array(
          "id" => $v['id'],
          "rep" => $v['likes'] * $this->rep['pst']
        );
      }
    }
    return $r;
  }
  
  public function getTopComments(){
    $sql = $GLOBALS['OP']->dbh->prepare("SELECT `id`, `likes`, `comment`, `pid` FROM `comments` WHERE `uid` = ? ORDER BY `likes` DESC LIMIT 5");
    $sql->execute(array($this->cU));
    $result = $sql->fetchAll(\PDO::FETCH_ASSOC);
    $comments = array();
    foreach($result as $k => $v){
      if($v['likes'] != 0){
        $comments[$k] = array(
          "id" => $v['id'],
          "cmt" => $v['comment'],
          "pid" => $v['pid'],
          "rep" => $v['likes'] * $this->rep['cmt']
        );
      }
    }
    return $comments;
  }
}
?>
