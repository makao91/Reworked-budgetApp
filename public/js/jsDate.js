var dzisiaj = new Date();
var miesiac = dzisiaj.getMonth()+1;
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
function uncommonDate ()
{
  var dateFrom = document.getElementById("dateFrom").value;
  var dateTo = document.getElementById("dateTo").value;
  document.getElementById("dateFromToWorkWith").innerHTML = dateFrom+" do "+dateTo;
  document.getElementById("dateFromToWorkWith").value = dateFrom+" do "+dateTo;
}
