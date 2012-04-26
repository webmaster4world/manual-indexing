// JavaScript Document

function preSel(elemName, n) 
{
	var x = document.getElementById(elemName)
	var xLen = x.length
	for (var i = 0; i < xLen; i++) 
	{
		x.options[i].selected = (x.options[i].value == n) ? true : false
	}
}

function chckAll()
{
  var chck = Array("news_modelle", "news_technologie", "news_unternehmen", "news_nachhaltigkeit", "news_events")
  var cLen = chck.length
  var y = document.getElementById("alleval")
  var bchk = (y.value > 0) ? true : false ;
  for (var i = 0; i < cLen; i++)
  {
    var x = document.getElementById(chck[i]).checked = bchk
  }
  var val = (bchk == 'checked') ? 1 : 0 ;
  y.setAttribute('value', val)
}