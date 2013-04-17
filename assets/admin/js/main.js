	function SendForm(frm){
	 	//ShowLoader();
		$.ajax({
			type: "POST",
			url: $(frm).attr('action'),
			data:$(frm).serialize(),
			beforeSend: function(data){return Validate(frm)},
			success: function(data){ShowResults(data, frm)},
            error: function(data, status){ShowAjaxError(data)},
			dataType: "json"
		});
	   return false;
	 }

	 function Validate(frm)
	 {
	 	if(!$.validationEngine){
	 		ShowLoader();
			return;
		}

		 var res =  $(frm).validationEngine('validate');
		 if(res){ ShowLoader();}
		 return res;
	 }

	 function ShowResults(data, frm)
	 {
	 	HideLoader();


	 	B.HideAlerts();

	 	
	 	
		if(data.error){
			ShowError(data.error, data.container);
		}
		if(data.success){
			ShowSuccess(data.success, data.container);
		}
		if(data.redirect){
			window.location = data.redirect;
		}
		if(data.hash){
			window.location.hash=data.hash;
		}
		if(data.refresh){
			location.reload(true);
		}
		if(data.back){
			history.back();
		}
		if(data.list){
			$("#list").html(data.list);
		}
		if(data.hide)
		{
			$(frm).hide();
		}
		if(data.callback){
			eval(data.callback);
		}
		if(data.reset){
			frm.reset();
		}
		if(data.collapse){
			B.Collapse(frm);
		}

		$(".error, .err").html('');
		$(".border_red", frm).removeClass('border_red');
		if(data.errors){

			for(key in data.errors){
				$(".err_"+key, frm).html(data.errors[key]);
				$("[name='"+key+"']", frm).addClass('border_red');
			}

			$(".border_red:first", frm).focus();
		}

	 }

	function ShowError(message, container)
	{
		if(container == null) container = "#message";
		$(container).hide().html(
			'<div class="alert alert-error">'+
			'<button type="button" class="close">×</button>'+
				message+
			'</div>'
		).fadeIn(1000);
		HideLoader();
		InitObjects(container);
	}

	 function ShowSuccess(message, container)
	 {

	 	if(container == null) container = "#message";
        $(container).hide().html(
        	'<div class="alert alert-success">'+
        	'<button type="button" class="close">×</button>'+
        		message+
        	'</div>'
        ).fadeIn(1000);
		InitObjects(container);
	 }
	 function ShowAjaxError(data)
	 {
	 	$("#debug").html('');
        alert("Ajax error occured: \nPage Status: " + data.status +"\nStatus Text: "+data.statusText);
		$("#debug").html(data.responseText);
		HideLoader();
	 }
	 function InitObjects(container)
	 {
        $(".close").click(function(){$(this).parent().fadeOut('slow')});
		$("#debug").html('');

		if(container){
	    	$('html, body').animate({scrollTop: $(container).offset().top-100}, 500);	
		}

     }

	 function ShowLoader()
	 {	
	 	$("#loader").show();
	 }
	 function HideLoader()
	 {		
	 	$("#loader").hide();
	 }

     function SimpleDelete(id, obj, url)
	 {
     	if(confirm("Are you sure?")){
	   		$.post(
		    	url,
				{id:id, csrf_test_name:crsf},
				function(data){
		        	if(data.success){
		            	ShowSuccess(data.success);
						$(obj).parent().parent().fadeOut('500');
					}
					if(data.error){
		            	ShowError(data.error);
					}
				},
				"json"
	   		);
	 	}
	 }

  function SimpleActivate(id, obj, url)
  {
    $.post(
		url,
		{id:id, csrf_test_name:crsf},
		function(data){
		 	if(data==1)
			{
				var cur_cl=$(obj).attr('class');
				$(obj).removeClass(cur_cl);
				$(obj).toggleClass(function(){
					if(cur_cl=='icon-ok')
						return 'icon-off'
					return 'icon-ok';
				});
			}
			else{
				$("#debug").html(data);
			}
			ShowResults(data);
		},
		"json"
	 );
  }

	function Sort(column)
	{
		dir=$("#sort_dir").val();
		col=$("#sort_col").val();
		if(col==column)
		{
			if(dir=="asc")
				dir="desc";
			else
				dir="asc";
		}
		$("#sort_col").val(column);
		$("#sort_dir").val(dir);
		SendFilter();
	}

	function SendFilter(frm){
		ShowLoader();
        if (frm == null) frm = $("#filter_form");
		SendForm(frm);
	}



$(document).ready(function(){		

	$(document).bind('keydown', function(event) {
		if(event.keyCode==115)
		{					
			var href= $('#add').attr('href');
			if(href)
			{
				document.location =href;
			}
		}	
	});  

	
	
	InitObjects();


	//click on save from modal
	$("#modal .save").click(function(){
		$("#modal form").submit();
	})
	

	$('#modal').on('hidden', function() {
    	$(this).removeData('modal');
	});

	//check hidden blocks
	var hash = window.location.hash;
	if($(hash).not("visible")){

		$(hash).addClass('collapse-in').removeClass('collapse');
	}

	//autogrow textareas and count chars
	var autogrow = $('textarea.autogrow');
	if(autogrow.size()){
		$.getScript(base_url+"assets/js/charCount.js", function(data, textStatus, jqxhr) {
		   $(autogrow).autogrow();
		   var limit = $(autogrow).attr('maxlength');
		   if(limit){
			   	$(autogrow).charCount({allowed:limit})
		   }
		});
	}
	//load masked input plugin if required
	var $mask = $('input[data-mask]');	
	if($mask.size()){
		$.getScript(base_url+"assets/js/jquery.maskedinput-1.3.min.js", function(data, textStatus, jqxhr) {
			$mask.each(function(){
				$(this).mask($(this).attr('data-mask'));
			})
		   //$mask.mask($mask.attr('data-mask'));		
		});
	}

});



/** for arrays indexOf is not defined in IE ***/

if(!Array.prototype.indexOf) {
    Array.prototype.indexOf = function(needle) {
        for(var i = 0; i < this.length; i++) {
            if(this[i] === needle) {
                return i;
            }
        }
        return -1;
    };
}


//create namespace 
var B = B || {};

B.RelatedDropdowns = function(elm, dest_id, action_url, options_cache, params)
{

	var form = $(elm).closest("form");
	var dest = $(dest_id, form);
	var value = $(elm).val();

	if(!value){
		return $(dest).html('');
	}

	
	$(dest).attr('disabled',true).html('');
	$(dest).append('<option value="">Loading...</option>');

	if(!options_cache[value]){
		$.get(
			action_url,
			params,
			function(data){
				$(dest).attr('disabled',false).val('');
				var html="";
				for (var id in data){
					html+='<option value='+id+'>'+data[id]+'</option>';
				}
				options_cache[value] = html;
				$(dest).html(html);	
						
			},
			'json'
		);
	}
	else{
		$(dest).attr('disabled',false).val('');
		$(dest).html(options_cache[value]);	
	}	
	$(dest).trigger('change');
}


B.cities_cache = {};
B.SetCities = function(elm)
{			
	var action_url = index_url+'ajax/json_cities'; 		
	var params = {country_code: $(elm).val(), crsf: crsf};		
	B.RelatedDropdowns(elm, "[name=city_id]", action_url, B.cities_cache, params);
}

B.regions_cache = {};

B.SetRegions = function(elm)
{
	var action_url = index_url+'ajax/json_regions';
	var params = {city_id: $(elm).val(), crsf:crsf};
	B.RelatedDropdowns(elm, "[name=region_id]", action_url, B.regions_cache, params);
}

B.Collapse = function (obj){
	$(obj).closest('.collapse').collapse('hide');
	window.location.hash=''; //remove hash
	this.HideAlerts();
}

B.HideAlerts = function(){
	$(".alert-error").parent().html('');
}


B.log = function(message, level) {
    if (window.console) {
        if (!level || level === 'info') {
            window.console.log(message);
        }
        else
        {
            if (window.console[level]) {
                window.console[level](message);
            }
            else {
                window.console.log('<' + level + '> ' + message);
            }
        }
    }
};