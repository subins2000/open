function passwordStrength(f,i,d){var k=1,h=2,b=3,a=4,c=5,g=0,j,e;if((f!=d)&&d.length>0){return c}if(f.length==0){return 0;}if(f.length<4){return k}if(f.toLowerCase()==i.toLowerCase()){return h}if(f.match(/[0-9]/)){g+=10}if(f.match(/[a-z]/)){g+=26}if(f.match(/[A-Z]/)){g+=26}if(f.match(/[^a-zA-Z0-9]/)){g+=31}j=Math.log(Math.pow(g,f.length));e=j/Math.LN2;if(e<40){return h}if(e<56){return b}return a};
function widthofpr(p){
 if(p==0){return "0%";}
 if(p==1){return "25%";}
 if(p==2){return "50%";}
 if(p==3){return "75%";}
 if(p==4){return "100%";}
}
function textofpr(p){
 if(p==0){return "Type A Password";}
 if(p==1){return "Short Password";}
 if(p==2){return "Bad Password";}
 if(p==3){return "Good Password";}
 if(p==4){return "Strong Password";}
 if(p==5){return "Password Mismatch";}
}
$(document).ready(function(){
 u=$("#user");
 v=$("#pass");
 v2=$("#pass2");
 p=$("#pbar");
 pt=$("#ppbartxt");
 v.bind('keyup',function(){
  p.css('width',widthofpr(passwordStrength(v.val(),u.val(),v2.val())));
  pt.text(textofpr(passwordStrength(v.val(),u.val(),v2.val())));
 });
 v2.bind('keyup',function(){
  p.css('width',widthofpr(passwordStrength(v.val(),u.val(),v2.val())));
  pt.text(textofpr(passwordStrength(v.val(),u.val(),v2.val())));
 });
});
