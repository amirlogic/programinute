<script type="text/javascript">

var curUpInt;
var lastUp = false;
var lastUpCheck;

function setCurInt()
{
	var curtime = timeNow();
	
	if(lastUp === false)
	{
		lastUp = curtime;
	}
	
	var inact = curtime-lastUp;
	
	if(inact < 60000){
		
		curUpInt = 10000;
	}
	else if(inact < 300000){
		
		curUpInt = 20000;
	}
	else if(inact < 1200000){
		
		curUpInt = 40000;
	}
	else if(inact < 1800000){
		
		curUpInt = 80000;
	}
	else if(inact > 1800000){
		
		curUpInt = 200000;
	}
}

function detectUpdate()
{
	for(b=0; b<xBuffer.length; b++)
	{
		if(xBuffer[b][0] == "newupdate")
		{
			lastUp = timeNow();
			xBuffer.splice(b,1);
		    b--;
		}
	}
}

function checkUpdates()
{
	doGet("updates_check","string","c",'json','none');
	detectUpdate();
	setCurInt();
	var nxt = setTimeout(checkUpdates,curUpInt);
}

function waitUpdates()
{
	
}

</script>