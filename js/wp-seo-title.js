
jQuery(document).ready(function($) {
	
	appendHtml = '';
	appendHtml += '<div id="wpst-suggestions-widget" class="widget">';
	appendHtml += '<h3 class="widget-top">';
	appendHtml += '<span>' + objectL10nWPST.suggestionstitles + '</span>';
	appendHtml += '</h3>';
	
	appendHtml += '<div id="wpst-results" class="inside" style="display: none;">';
	appendHtml += '<span id="wpst-suggestions">&nbsp;</span>';
	appendHtml += '</div>';

	appendHtml += '<div id="wpst-tools">';
	appendHtml += '<span>';
	appendHtml += '<label class="hide-if-no-js wpst-prompt-text" for="wpst-keyword-suggestions">' + objectL10nWPST.placekeyword + '</label>';
	appendHtml += '<input type="text" id="wpst-keyword-suggestions" class="wpst-input" autocomplete="off" value="" />';
	appendHtml += '<a href="#wpst-get-suggestions" id="wpst-get-suggestions" class="wpst-button button">' + objectL10nWPST.getsuggestions + '</a>';
	appendHtml += objectL10nWPST.countries_list;
	appendHtml += '</span>';
	appendHtml += '</div>';
	appendHtml += '</div>';
	$('#titlediv').append(appendHtml);

	jQuery("#wpst-keyword-suggestions").gcomplete({
		style: "default",
		effect: false,
		pan: '#wpst-keyword-suggestions'
	});

	var keyword_selected = '';
	var country_selected = '';
	
	function display_suggestions(keyword, sortfield, sorttype) {
		$('#wpst-results').show();
		country_selected = $('#wpst_country_selected').val();
		keyword_selected = keyword;
		$('#wpst-suggestions-widget .widget-top').html(objectL10nWPST.suggestionsfor + ' <strong>' + keyword + '</strong> (<strong>' + country_selected.toUpperCase() + '</strong>)');
		$('#wpst-suggestions').html('<div id="ajax-loader"></div>');
		$.post(ajaxurl, {'action': 'wpst_title_suggestions', 'wpst_keyword': keyword, 'wpst_country_selected': country_selected, 'wpst_sortfield': sortfield, 'wpst_sorttype': sorttype}, function(response) {
			$('#wpst-suggestions').html(response);
		});
	}
	
	$('#wpst-toggle-button').live('click', function(event) {
		if ($('#wpst-toggle-button').attr('class') != 'button disabled') {
			$('#wpst-suggestions-widget').toggle();
			if ($('#wpst-suggestions-widget').is(':visible')) {
				$('#wpst-toggle-button').html('Ocutar Sugerencias');
				if ($('#wpst-suggestions').html() == '&nbsp;') {
					if ($('#post #titlediv #title').val() == '') {
						$('#wpst-suggestions-widget .inside p').html(objectL10nWPST.placekeyword);
					}
					else {
						display_suggestions($('#post #titlediv #title').val());
					}
				}
			}
			else {
				$('#wpst-toggle-button').html(objectL10nWPST.get-suggestions);
			}
		}
		event.preventDefault();
	});
	
	$('#wpst-get-suggestions').live('click', function(event) {
		if ($('#post #titlediv #title').val() != '' && $('#wpst-keyword-suggestions').val() == '') {
			display_suggestions($('#post #titlediv #title').val());
		}
		else if ($('#wpst-keyword-suggestions').val() != '') {
			display_suggestions($('#wpst-keyword-suggestions').val());
		}
		event.preventDefault();
	});
	
	$('.wpst-more').live('click', function(event) {
		display_suggestions($(this).attr('keyword'));
		$('#wpst-keyword-suggestions').val($(this).attr('keyword'));
		$('#wpst-keyword-suggestions').blur();
		event.preventDefault();
	});
	
	$('.wpst-set-title').live('click', function(event) {
		$('#post #titlediv #title').attr('value', $(this).attr('keyword'));
		$('#post #titlediv #title').blur();
		event.preventDefault();
	});

	$('.wpst-sorting-arrows').live('click', function(event) {
		if(!$(this).parent().hasClass('wpst-sorted-'+$(this).attr('title')))
			display_suggestions(keyword_selected, $(this).attr('alt'), $(this).attr('title'));
	});
	
	$('.wpst-input').live('focus', function(event) {
		$(this).prev('.wpst-prompt-text').hide();
	});
	
	$('.wpst-input').live('blur', function(event) {
		if ($(this).val() == '') {
			$(this).prev('.wpst-prompt-text').show();
			$(this).prev('.wpst-prompt-text').css('display', '');
		}
		else {
			$(this).prev('.wpst-prompt-text').hide();
		}
	});
	
	if ($('.wpst-input').val() != '') {
		$('.wpst-input').blur();
	}
	
	$('#post #titlediv #title').keyup();

	$('#wpst_country_selected_ul').ddslick({
	    width: 80,
	    onSelected: function(data){
        if(data.selectedIndex >= 0) {
        	$('#wpst_country_selected').val(data.selectedData.value);
        }
    }      
	});

});
