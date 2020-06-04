var dzisiaj = new Date();
var miesiac = dzisiaj.getMonth()+1;
var previousMon = dzisiaj.getMonth();
var dzien = dzisiaj.getDate();
var rok = dzisiaj.getFullYear();

if(dzien<10)
{
  dzien="0"+dzien;
}
if(miesiac<10)
{
  miesiac="0"+miesiac;
}
if(previousMon<10)
{
  previousMon="0"+previousMon;
}

function currentDateYMD (popo)
	{
		document.getElementById(popo).value = rok+"-"+miesiac+"-"+dzien;
	}
function previousDateYMD (popo)
  {
    var prevD = dzien-1;
    if(prevD<10)
    {
      prevD="0"+prevD;
    }
		document.getElementById(popo).value = rok+"-"+miesiac+"-"+prevD;
  }

function currentMonth ()
{
  var date = new Date();
  var last = new Date(date.getFullYear(), (date.getMonth()-1), 0);

  var firstDayOfTheMonth = "01";
  var lastDayInt = last.getDate();

  var firstDayFull = rok+"-"+miesiac+"-"+firstDayOfTheMonth;
  var lastDayFull = rok+"-"+miesiac+"-"+lastDayInt;

  document.getElementById("fromDate").value = firstDayFull;
  document.getElementById("dateTo").value = lastDayFull;
}

function previousMonth ()
{
  var date = new Date();
  var last = new Date(date.getFullYear(), (date.getMonth()-2), 0);

  var firstDayOfTheMonth = "01";
  var lastDayInt = last.getDate();

  var firstDayFull = rok+"-"+previousMon+"-"+firstDayOfTheMonth;
  var lastDayFull = rok+"-"+previousMon+"-"+lastDayInt;

  document.getElementById("fromDate").value = firstDayFull;
  document.getElementById("dateTo").value = lastDayFull;
}

function currentYear ()
{
  var firstDayFull = rok+"-"+"01"+"-"+"01";
  var lastDayFull = rok+"-"+"12"+"-"+"31";

  document.getElementById("fromDate").value = firstDayFull;
  document.getElementById("dateTo").value = lastDayFull;
}
function previousYear ()
{
  var firstDayFull = (rok-1)+"-"+"01"+"-"+"01";
  var lastDayFull = (rok-1)+"-"+"12"+"-"+"31";

  document.getElementById("fromDate").value = firstDayFull;
  document.getElementById("dateTo").value = lastDayFull;
}
