<?php

	//require_once('ajax.php');
	//require_once('updates.php');
	
	//require_once('analytics.php');

	
 // <link href='http://fonts.googleapis.com/css?family=Open+Sans' rel='stylesheet' type='text/css'>

 // <script type="text/javascript">


/*if(!$user->uid){
	
	echo "var userLoggedIn = false;\nvar userStatus = 'none';\n";
}else{
	
	echo "var userLoggedIn = true;\nvar userStatus = '".$user->status."';\n";
}*/

?>
var logInProg = false;
 
var procTxt = "Processing...";
var loginAlert = "Please login first";

function timeNow()
{
	var dt = new Date();
	var tm = dt.getTime();
	
	return tm;
}

function showLoginBox()
{
	document.getElementById('loginbox').style.display = "block";
}

function bgColr(elmt,color)
{
	document.getElementById(elmt).style.backgroundColor = color;
}

function isInside(arr,val)
{
	for(i=0;i<arr.length;i++)
	{
		if(arr[i] == val){ return true; break; }
	}
	return false;
}

function getIndex(arr,val)
{
	for(x=0;x<arr.length;x++)
	{
		if(arr[x] == val){ return x; break; }
	}
	return false;
}

function switchThis(elmt,cmd)
{
	if(cmd == 'on'){
		document.getElementById(elmt).disabled = false;
	}
	else if(cmd == 'off'){
		document.getElementById(elmt).disabled = true;
	}
}

function upCheckbox(lnkid)
{
	if(document.getElementById(lnkid).checked == true){ document.getElementById(lnkid).checked = false; }else{ document.getElementById(lnkid).checked = true; }
}

function loginNow()
{
	if(userLoggedIn == true){ return false; }
	if(logInProg == true){ return false; }
	
	var usr = document.getElementById('userid').value;
	var pswrd = document.getElementById('pswrd').value;
	var rmbr;
	
	if(usr == "" || usr.length < 4)
	{
		bgColr('userid', "#FCDDC9");
		return false;
	}
	
	if(pswrd == "" || pswrd.length < 6)
	{
		bgColr('pswrd', "#FCDDC9");
		return false;
	}
	
	if(document.getElementById('rmbr').checked == true){ rmbr = "yes"; }else{ rmbr = "no"; }
	
	var senddata = new Array();
	
	senddata[0] = new Array();
	senddata[0][0] = "usr";
	senddata[0][1] = usr;
	
	senddata[1] = new Array();
	senddata[1][0] = "pswrd";
	senddata[1][1] = pswrd;
	
	senddata[2] = new Array();
	senddata[2][0] = "rmbr";
	senddata[2][1] = rmbr;
	
	doGet('user_login','array',senddata,'json','none');
	
	switchThis('userid','off');
	switchThis('pswrd','off');
	switchThis('rmbr','off');
	document.getElementById('loginbut').innerHTML = procTxt;
	
	logInProg = true;
}


function expandThis(elid)
{
	document.getElementById('expcmd_'+elid).style.display = "none";
	document.getElementById('expres_'+elid).style.display = "block";
}

</script>
