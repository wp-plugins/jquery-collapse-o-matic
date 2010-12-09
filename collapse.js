jQuery(document).ready(function() {
    jQuery('.collapseomatic').click(function() {
		//alert('phones ringin dude');
		jQuery(this).toggleClass('close');
		var id = jQuery(this).attr('id');
		jQuery('#target-'+id).slideToggle('fast', function() {
		    // Animation complete.
		});
	});
        
    var myFile = document.location.toString();
    if (myFile.match('#')) { // the URL contains an anchor
        // click the navigation item corresponding to the anchor
        var myAnchor = '#' + myFile.split('#')[1];
        jQuery(myAnchor).click();
    }
});