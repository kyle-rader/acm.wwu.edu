
$(document).ready(function() {
	var page = getCookie('page') || 'home';
	LoadPage(page);
});

function LoadPage(page) {
	var content = $('#content');
	if (content.text() != '') {
		content.fadeOut(50, function() {
			$.get('/ajax/get_page.php?page=' + page, function(response) {
				content.html(response).fadeIn(250);
			});
		});
	} else {
		$.get('/ajax/get_page.php?page=' + page, function(response) {
			content.html(response).fadeIn(250);
		});
	}	
};

$(document).on('click', 'a.top-btn', function(event) {
	var page = $(this).attr('data-target');
	$('a.top-btn').parent().removeClass('active');
	$(this).parent().addClass('active');
	setCookie('page', page, 1);
	LoadPage(page);
});

function getCookie(cname)
{
var name = cname + "=";
var ca = document.cookie.split(';');
for(var i=0; i<ca.length; i++) 
  {
  var c = ca[i].trim();
  if (c.indexOf(name)==0) return c.substring(name.length,c.length);
  }
return "";
}

function setCookie(cname,cvalue,exdays)
{
var d = new Date();
d.setTime(d.getTime()+(exdays*24*60*60*1000));
var expires = "expires="+d.toGMTString();
document.cookie = cname + "=" + cvalue + "; " + expires;
}