<script type="text/javascript">

var xBuffer = new Array();

function doGet(data)
{

	var xmlhttp;

    xmlhttp=new XMLHttpRequest();
 
	console.log('Sent Data: '+data);
  
	xmlhttp.onreadystatechange=function()
	{
		if (xmlhttp.readyState==4 && xmlhttp.status==200)
		{
		
			console.log(xmlhttp.responseText);
			handleJSON(xmlhttp.responseText);
		
		}
	}
  
 
  xmlhttp.open("POST","/pg/dish.php",true);
  xmlhttp.setRequestHeader("Content-type","application/x-www-form-urlencoded");
  xmlhttp.send('data='+data);
  
}

function handleJSON(jsonstr)
{
	var jsonobj = JSON.parse(jsonstr);
	
	for(i=0; i<jsonobj.length; i++)
	{
		switch(jsonobj[i].action)
		{
			case "setContent":
				document.getElementById(jsonobj[i].target).innerHTML = jsonobj[i].newval;
			break;
			
			case "setValue":
				document.getElementById(jsonobj[i].target).value = jsonobj[i].newval;
			break;
			
			case "setColor":
				document.getElementById(jsonobj[i].target).style.color = jsonobj[i].newval;
			break;
			
			case "setBgColor":
				document.getElementById(jsonobj[i].target).style.backgroundColor = jsonobj[i].newval;
			break;
			
			case "setDisplay":
				document.getElementById(jsonobj[i].target).style.display = jsonobj[i].newval;
			break;
			
			case "makeDisabled":
				document.getElementById(jsonobj[i].target).disabled = true;
			break;
			
			case "makeEnabled":
				document.getElementById(jsonobj[i].target).disabled = false;
			break;
			
			case "makeChecked":
				document.getElementById(jsonobj[i].target).checked = true;
			break;
			
			case "makeUnchecked":
				document.getElementById(jsonobj[i].target).checked = false;
			break;
			
			case "newElement":
			 var futurel = document.createElement( jsonobj[i].type );
			 var attr;
			 
			 for( attr in jsonobj[i].attributes )
			 {
				 var attname = jsonobj[i].attributes[attr].att;
				 var attcont = jsonobj[i].attributes[attr].content;
				 
				 futurel.setAttribute( attname,attcont );
			 }
			 
			 futurel.innerHTML = jsonobj[i].newcontent;
			 document.getElementById( jsonobj[i].target ).appendChild( futurel );
			break;
			
			case "addLineBreak":
				var br = document.createElement( 'BR' );
				document.getElementById( jsonobj[i].target ).appendChild( br );
			break;
			
			case "setLoginInfos":
			 if(jsonobj[i].loginok == "yes"){
				 
				 userLoggedIn = true;
				 userStatus = jsonobj[i].userstatus;
			 }
			 else if( jsonobj[i].loginok == "no" ){
				 
				 userLoggedIn = false;
				 userStatus = "none";
			 }
			 
			 logInProg = false;
			break;
			
			case "consoleLog":
			
				console.log( jsonobj[i].val );
			
			break;
			
			case "makeSensitive":
			
				document.getElementById( jsonobj[i].target ).addEventListener( jsonobj[i].evnt,function(){
					attReader( jsonobj[i].target ) } );
			
			break;
			
			case "nothing":
			
			break;

		}
	}
}

</script>