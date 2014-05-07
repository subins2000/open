<?include("../inc/config.php");?>
<!DOCTYPE html>
<html><head>
 <?$t="New Group";$fs="ac,groups";$cfs="ac";include("../inc/head.php");?>
</head><body>
 <?include("../inc/header.php");?>
 <div class="content">
  <h2>Create Group</h2>
  <p>Create A Group & Invite Friends.</p><div clear></div>
  <form method="POST" action="/groups/new">
   <label>
    Group Name
    <input type="text" size="40" name="groupName" placeholder="Max 20 Chars. Exceeding characters will be removed"/>
   </label>
   <div>
    <label>
     Invite<br/>
     <textarea id="toInvite" name="toInvite" type="text"></textarea>
    </label>
   </div>
  </form>
 </div>
</body></html>