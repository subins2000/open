// JavaScript Document
jQuery(document).ready(function() {
	
   jQuery('#vecb_single-block').hide();
   jQuery('#vecb_quicktag').hide();
   
  if(jQuery('#vecb_wrap').is(':checked')) { 
  	jQuery('#vecb_single-block').hide();
  	jQuery('#vecb_wrap-selection').show();
   } else {
	jQuery('#vecb_single-block').show();
  	jQuery('#vecb_wrap-selection').hide();
   }
   
    if(jQuery('#vecb_html_editor').is(':checked')) { 
  	jQuery('#vecb_quicktag').show();
   } else {
	jQuery('#vecb_quicktag').hide();
   }
   
   if(jQuery('#vecb_rich_editor').is(':checked')) { 
  	jQuery('#vecb_btnicon').show();
	jQuery('#vecb_rowdef').show();
   } else {
	jQuery('#vecb_btnicon').hide();
	jQuery('#vecb_rowdef').hide();
   }
  
  jQuery('.vecb_radiobtn').change(function() {
	  
	  jQuery('.vecb_inputbox').toggle();
	  
  });
  
    jQuery('#vecb_html_editor').change(function() {
	  
	  jQuery('#vecb_quicktag').slideToggle();
	  
  });
  
      jQuery('#vecb_rich_editor').change(function() {
	  
	  jQuery('#vecb_btnicon').slideToggle();
	  
  });
  
  
  //Preview
  
    var iconname = jQuery('#vecb_icon').val();
	
	var pluginurl = jQuery('#vecb_pluginurl').html();
	var custompluginurl = jQuery('#vecb_custompluginurl').html();
	
	if(iconname) {
    var firstletter = (iconname.charAt(0));
	}
	if (firstletter == "_") {
		if(iconname) {
		iconname = iconname.substr(1);
		}
		jQuery('#vecb_btnpreview').html('<img src="'+ custompluginurl + iconname + '">');
	} else {
		jQuery('#vecb_btnpreview').html('<img src="'+ pluginurl + '/visual-editor-custom-buttons/js/icons/'+ iconname + '">');
	}
	
	 
  
  
  jQuery('#vecb_icon').change(function() {
	  
	
    var iconname = jQuery('#vecb_icon').val();
	
	if(iconname) {
	var firstletter = (iconname.charAt(0));
	}
	if (firstletter == "_") {
		if(iconname) {
		 iconname = iconname.substr(1);
		}
		jQuery('#vecb_btnpreview').html('<img src="'+ custompluginurl + iconname + '">');
	} else {
		jQuery('#vecb_btnpreview').html('<img src="'+ pluginurl + '/visual-editor-custom-buttons/js/icons/'+ iconname + '">');
	}
	  
  });
  
  
  //
  
  
  
});