$.extend({
  get_url_vars: function(){
    var vars = [], hash;
    var hashes = window.location.href.slice(window.location.href.indexOf('/') + 1).split('/');
	alert(hashes);
    for(var i = 0; i < hashes.length; i++)
    {
      vars[i] = hashes[i];
    }
    return vars;
  }
});

var panel_container = false;

jQuery(document).ready(function($){
        function ajax_load(num) {
			
			loading();
			var container = num.split('!');
			var url = container[0];
			container = 'content';
			
			if(panel_container)
			{
				$('#panel_2').load(url);
			}else{
				$('#'+container).load(url);
			}

			var my_split = num.split('/');

			if( my_split[3]!=undefined )
			{
				my_split[2] = my_split[3]+"/"+my_split[2];
			}
			
			if(my_split[1] != 'show_config')
			{
				$('#breadcrumb').load(my_split[0]+'/make_breadcrumb/'+my_split[1]+'/'+my_split[2]);
				//$('#top_menu_actions').load(my_split[0]+'/top_menu_actions/'+my_split[1]+'/'+my_split[2]);
			}
			stop_loading();
        }
				
        $.history.init(function(url) {
                ajax_load(url == "" ? "admin/x/" : url);
        },
	    { unescape: ",/" });

		$('.ajax-links-top-menu').live('click', function(e) {
				var url = $(this).attr('href');
				url = url.replace(/^.*#/, '');

				panel_container = false;
				
				$(this).attr('');
				$.history.load(url);
                return false;
        });

        $('.ajax-links').live('click', function(e) {
				var url = $(this).attr('href');
				if(url!=undefined)
				{
					url = url.replace(/^.*#/, '');
					$(this).attr('');
					$.history.load(url);
					return true;
				}
				return false;
        });
});

function load_ajax(url)
{
		url = url.replace(/^.*#/, '');
		$.history.load(url);
		return false;
}

function load_panel_2_ajax(url)
{
		url = url.replace(/^.*#/, '');
		$.history.load(url);
		// $('#'+container_id).html(doc_html);
		return false;
}

function loading()
{
	$('#loading').text('Cargando...');
	$('#loading').fadeIn();
	$('#loading').addClass('alert alert-success');
}

function stop_loading()
{
	$('#loading').fadeOut();
}

function notify(message, type, timeOut) {
	$('#notification').text(message).css({visibility: "visible"})
	$('#notification').removeClass();
	$('#notification').addClass(type);
	setTimeout(function(){
		$('#notification').css({visibility: "hidden"})
	}, timeOut*1000);
}

function kill_notify()
{
	$('#notification').fadeOut();
}

