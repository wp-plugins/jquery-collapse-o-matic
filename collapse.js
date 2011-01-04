/*!
 * jQuery Collapse-O-Matic v1.1
 * http://www.twinpictures.de/collapse-o-matic/
 *
 * Copyright 2010, Twinpictures
 * 
 * Permission is hereby granted, free of charge, to any person obtaining a copy
 * of this software and associated documentation files (the "Software"), to deal
 * in the Software without restriction, including without limitation the rights
 * to use, copy, modify, merge, publish, distribute, sublicense, blend, trade,
 * bake, hack, scramble, difiburlate, digest and/or sell copies of the Software,
 * and to permit persons to whom the Software is furnished to do so, subject to
 * the following conditions:
 * 
 * The above copyright notice and this permission notice shall be included in
 * all copies or substantial portions of the Software.
 * 
 * THE SOFTWARE IS PROVIDED "AS IS", WITHOUT WARRANTY OF ANY KIND, EXPRESS OR
 * IMPLIED, INCLUDING BUT NOT LIMITED TO THE WARRANTIES OF MERCHANTABILITY,
 * FITNESS FOR A PARTICULAR PURPOSE AND NONINFRINGEMENT. IN NO EVENT SHALL THE
 * AUTHORS OR COPYRIGHT HOLDERS BE LIABLE FOR ANY CLAIM, DAMAGES OR OTHER
 * LIABILITY, WHETHER IN AN ACTION OF CONTRACT, TORT OR OTHERWISE, ARISING FROM,
 * OUT OF OR IN CONNECTION WITH THE SOFTWARE OR THE USE OR OTHER DEALINGS IN
 * THE SOFTWARE.
 */

jQuery(document).ready(function() {
    jQuery('.collapseomatic').hover(function () {
            jQuery(this).addClass("hover");
        },
        function () {
            jQuery(this).removeClass("hover");
        }
    );
    
    jQuery('.collapseomatic').click(function() {
		//alert('phones ringin dude');
		jQuery(this).toggleClass('close');
		var id = jQuery(this).attr('id');
		jQuery('#target-'+id).slideToggle('fast', function() {
		    // Animation complete.
		});
        
        //deal with grouped items if needed
        if(jQuery(this).attr('rel') !== undefined){
            var rel = jQuery(this).attr('rel');
            closeOthers(rel);
        }
        
	});
    
    function closeOthers(rel){
        jQuery('.collapseomatic[rel!="' + rel +'"]').each(function(index) {
            //add close class if open
            if(jQuery(this).hasClass('close') && jQuery(this).attr('rel') !== undefined){                
                jQuery(this).removeClass('close');
                var id = jQuery(this).attr('id');
                jQuery('#target-'+id).slideToggle('fast', function() {
                    // Animation complete.
                });
            }
        });
    }
    
    var myFile = document.location.toString();
    if (myFile.match('#')) { // the URL contains an anchor
        // click the navigation item corresponding to the anchor
        var myAnchor = '#' + myFile.split('#')[1];
        jQuery(myAnchor).click();
    }
});