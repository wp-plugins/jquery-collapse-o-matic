jQuery(document).ready(function() {
                  jQuery('.collapse').click(function() {
		    //alert('phones ringin dude');
		    jQuery(this).toggleClass('close');
		    var id = jQuery(this).attr('title');
		    jQuery('#'+id).slideToggle('fast', function() {
		      // Animation complete.
		    });
	  });
});