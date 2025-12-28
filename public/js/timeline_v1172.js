var page = 0;
var view = 1;
var order =2;
var timeline_ajax=null;
var type_time_out=null;
$(document).ready(function() {
	$('body').on('click', '.page_number', function(){
		page = $(this).attr('page');
		reload_filter(page);

	});
	$('body').on('keyup', '#Keyword', function(){
        clearTimeout(type_time_out);
        type_time_out=  setTimeout(function(){
            reload_filter();
        }, 250);


	});

	$('.order_view').on('click', function() {
		if($(this).attr('id')=='Ascending'){
			$('#Descending').removeClass('active');
		}
		if($(this).attr('id')=='Descending'){
			$('#Ascending').removeClass('active');
		}

		order=$(this).attr('order');
		reload_filter();
	});

	$('.view_level').on('click', function() {
	if($(this).attr('id')=='Timeline_View'){
		$('#Table_View').removeClass('active');
	}
	if($(this).attr('id')=='Table_View'){
		$('#Timeline_View').removeClass('active');
	}

	view=$(this).attr('view');
		reload_filter();
	});

    $('body').on('change', '#DateRange', function(){
            reload_filter();
    });

	$('#fliter-action').multiselect({
	 enableFiltering: true,
	 enableCaseInsensitiveFiltering: true,
	 allSelectedText: allActionsTxt,
	 nonSelectedText: allActionsTxt,
	 onChange: function(option, checked, select) {
		reload_filter();
	}
	});

	$('#fliter-staff').multiselect({
	 enableFiltering: true,
	 enableCaseInsensitiveFiltering: true,
	 allSelectedText: allStaffTxt,
	 nonSelectedText: allStaffTxt,
	 onChange: function(option, checked, select) {
		reload_filter();
	}
	});
		if(window.location.hash.substring(1,timeline_url.length+1)==timeline_url)
			$.ajax({url: window.location.hash.substring(1,window.location.hash.length), success: function(result){
					$("#timeline_content").html(result);
			}});


});

function reload_filter(in_page){

	 in_page = typeof in_page !== 'undefined' ? in_page : 0;

	var selected = [];
	$('#fliter-action option:selected').each(function() {
		selected.push($(this).val());
	});


	var staff = [];
	$('#fliter-staff option:selected').each(function() {
		staff.push($(this).val());
	});

	var date_from=$('#DateFrom').val();
	var date_to=$('#DateTo').val();
	$("#timeline_content").html('<div class="notifcation-loader"><div class="inner-loader"></div></div>');
	if($('#Keyword').val()){
        keyword=$('#Keyword').val();
        }else{
        keyword='';
        }
	if(timeline_url.indexOf('?') !== -1) {
		var ajax_url=timeline_url+"&action="+selected+'&view='+view+'&order='+order+'&staff='+staff+'&date_from='+date_from+'&date_to='+date_to+'&page='+in_page+'&keyword='+keyword;
	} else {
		var ajax_url=timeline_url+"?action="+selected+'&view='+view+'&order='+order+'&staff='+staff+'&date_from='+date_from+'&date_to='+date_to+'&page='+in_page+'&keyword='+keyword;
	}
if(timeline_ajax!=null){
    timeline_ajax.abort();
}
	timeline_ajax=$.ajax({url: ajax_url, cache:false, success: function(result){
		$("#timeline_content").html(result);
	}});
	 window.location.hash = ajax_url;
}

function timeline_row(id) {
	$.ajax({url: timeline_row_url+'/'+id, success: function(result){
	$("#timeline_row").html(result);
	$('#Showaction').modal();
	}});

}
