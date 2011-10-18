// Get the HTTP Object
var isIE = document.all ? true:false;
var inputField;
var ReturnDiv = "";

var curItem=0;

var changeArray=new Array();


function countNodes(parent)
{
	i=0;
  realNodes = 0;
	nodes = parent.childNodes.length;
	while(i < nodes)
  {
		if(parent.childNodes[i].nodeType != 3)
			realNodes++;
		i++;
	}
	return realNodes;
}

function MakeHTTPObject()
{
if (window.ActiveXObject) 
  return new ActiveXObject("Microsoft.XMLHTTP");
else if (window.XMLHttpRequest) 
  return new XMLHttpRequest();
else
  {
  alert("Your browser does not support AJAX.");
  return null;
  }
}

function RequestHoliday(devID)
{
httpObject = MakeHTTPObject();

var x=document.getElementById("DevID");
devID = x.value;

if (httpObject != null)
  {
  httpObject.open("GET", "GetHoliday.php?user=" + devID, true);
  httpObject.send(null);
  httpObject.onreadystatechange = ShowHoliday;
  }
}

function ShowHoliday()
{
var x=document.getElementById("DevID");
devName = x.options[x.selectedIndex].text;

if(httpObject.readyState == 4)
  {
  document.getElementById('FullName').innerHTML = devName + "'s ";
  document.getElementById('HolTable').innerHTML = httpObject.responseText;
  }
}

function AJAXCall(param,callback)
{
httpObject = MakeHTTPObject();
if (httpObject != null)
  {
  httpObject.open("POST", param, true);
  httpObject.send(null);
  if (callback ==null) httpObject.onreadystatechange = AJAXCallback;
  else httpObject.onreadystatechange = callback;
  }
}

function strToMin(inMin)
{
minArr = inMin.split(/[,.:;-]/);
if (minArr.length==2) minutes = parseInt(minArr[0]*60) + parseInt(minArr[1]);
else minutes = minArr[0];

return minutes;
}

function AddTime(devID,taskID,isZero,subid)
{
if (isZero) minutes=0;
else minutes = strToMin(document.getElementById("minutes").value);
date = document.getElementById("date").value;

if (!subid) sub = "";
else if (!isNaN(subid)) sub = subid
else sub = document.getElementById(subid).value;

ReturnDiv = "timesheet";

AJAXCall("SetTime.php?action=add&user=" + devID + "&task=" + taskID + "&date=" + date 
  + "&minutes=" + minutes + "&sub="+ sub + getTimeFields());
changeArray.length=0;
}

function DeleteTime(devID,timeID)
{
date = document.getElementById("date").value;

ReturnDiv = "timesheet";

AJAXCall("SetTime.php?action=delete&timeid=" + timeID + "&user=" + devID+ "&date=" + date + getTimeFields());
changeArray.length=0;
}

function Now(devID,timeID,minid,subid)
{
date = document.getElementById("date").value;
minutes = strToMin(document.getElementById(minid).value);

if (!subid) sub = "";
else sub = document.getElementById(subid).value;

ReturnDiv = "timesheet";
AJAXCall ("SetTime.php?action=now&timeid=" + timeID + "&user=" + devID+ "&date=" + date 
  + "&minutes=" + minutes + "&sub="+ sub + getTimeFields());
changeArray.length=0;
}

function GetTime(devID)
{
date = document.getElementById("date").value;

ReturnDiv = "timesheet";

AJAXCall("SetTime.php?action=get&user=" + devID+ "&date=" + date + getTimeFields());
}

function AJAXCallback()
{
if(httpObject.readyState == 4)
  document.getElementById(ReturnDiv).innerHTML = httpObject.responseText;
}

// Callback function after date picker is closed. Calls getTime to refresh timesheet
function datePickerClosed(date)
{
GetTime(document.getElementById("devID").innerHTML);
}

function TimesheetReport()
{
devid = document.getElementById("DevID").value;
month = document.getElementById("MonthID").value;
year = document.getElementById("YearID").value;

month++;

ReturnDiv = "timesheet";
AJAXCall("tsrep_ajax.php?dev=" + devid + "&mon=" + month + "&year=" + year);
}

function findPosX(obj)
{
var curleft = 0;
if (obj.offsetParent)
  {
  while (obj)
    {
    curleft += obj.offsetLeft;
    obj = obj.offsetParent;
    }
  }
else if (obj.x)
  curleft += obj.x;
return curleft;
}

function findPosY(obj)
{
var curtop = 0;
if (obj.offsetParent)
  {
  while (obj)
    {
    curtop += obj.offsetTop;
    obj = obj.offsetParent;
    }
  }
else if (obj.y)
  curtop += obj.y;
return curtop;
}

// Function to highlight menu items
function highlight(itemID)
{
j = 0;

// loop through entire menu. Highlight selected row and unhighlight the rest
do
  {
  if (myMenuItem = document.getElementById("task-"+ j))
    {
    if (j == itemID)
      {
      myMenuItem.className="menuMouseOver";
      window.defaultStatus = myMenuItem.title;
      }
    else myMenuItem.className="menuNormal";
    j++;
    }
  }
while (myMenuItem);
}

function moveDiv(divID)
{
  menuDiv = document.getElementById('subtaskPop');
  inputField = document.getElementById(divID);

  if (isIE)
    {
    menuDiv.style.top = findPosY(inputField) + 22;
    menuDiv.style.left = findPosX(inputField) - 4;
    }
  else
    {
    menuDiv.style.top = findPosY(inputField) + 22  +"px";
		menuDiv.style.left = findPosX(inputField) - 4 + "px";
		}
}

function keyHandler(event,parID,devID,taskID)
{
  moveDiv(parID);

  if (isIE) desc = event.srcElement.value;
  else desc = event.target.value

  if (event.keyCode == 40) // Down Arrow
    {
    if ( ++curItem > countNodes(document.getElementById('subtasklist')) - 2) curItem=0;
    highlight(curItem);
    }
  else if (event.keyCode == 38) // Up Arrow
    {
    if (--curItem<0) curItem = countNodes(document.getElementById('subtasklist')) - 2;
    highlight(curItem);
    }
  else if (event.keyCode == 13) // Enter or Return
    {
    s = document.getElementById('task-'+curItem).innerHTML;
    s = s.replace("&nbsp;",'');
    s = s.replace(/^\s*|\s*$/g,'');
    popClick(s)
    }
  else if (event.keyCode==27) popClick('XXX') //Esc to exit menu
  else if (desc != '')
  {
    ReturnDiv = "subtaskPop";
    AJAXCall("subtaskajax.php?desc=" + desc + "&dev=" + devID + "&task=" + taskID,subTaskCallback);
  }
  else document.getElementById("subtaskPop").style.visibility = "hidden";
}

function popClick(text)
{
  if (text !='XXX') inputField.value = text;
  document.getElementById('subtaskPop').style.visibility = "hidden";
  curItem=0;
}

function subTaskCallback()
{
if(httpObject.readyState == 4)
  {
  if (httpObject.responseText !="")
    {
    document.getElementById(ReturnDiv).innerHTML = httpObject.responseText;
    document.getElementById(ReturnDiv).style.visibility = "visible";
    highlight(curItem);
    }
  else document.getElementById(ReturnDiv).style.visibility = 'hidden';
  }
}

function hrTimer(h,m)
{
  getHours(h,m);
  setInterval("getHours(" + h + "," + m + ")",1000);
}

function getHours(hour,min)
{
  var today = new Date();
  var dayStart = new Date(today.getFullYear(),today.getMonth(),today.getDate(),hour,min,0,0);

  var diff = Math.floor((today.getTime() - dayStart.getTime())/60000);
  if (diff < 0) document.getElementById('wkHrs').innerHTML = 'Early';
  else
    {
    var hours = Math.floor(diff/60);
    var mins = diff % 60;

    if (mins<10) mins = '0' + mins;

    document.getElementById('wkHrs').innerHTML = hours + ":" + mins;
    }
}

function tracklink(id)
{
 AJAXCall("http://dilbert/taskmon/tracklink.php?id=" + id,null);
}


function getTimeFields()
{
  items = changeArray.length;
  idList = "";
  subList = "";
  minList = "";
  for (i=0; i<items; i++)
  {
    idList += changeArray[i] + "|";
    subList += document.getElementById("Sub-" + changeArray[i]).value  + "|";
    minList += strToMin(document.getElementById("Minutes-" + changeArray[i]).value) + "|";
  }

  return "&ids=" + idList + "&subs=" + subList + "&mins=" + minList;
}

function updateAll(devId)
{
date = document.getElementById("date").value;
ReturnDiv = "timesheet";
AJAXCall("SetTime.php?action=none&user=" + devId + "&date=" + date + getTimeFields());
changeArray.length = 0;
}

function timeChange(id)
{
  if (changeArray.indexOf(id)== -1) changeArray.push(id);
}