/*
*
*	JNova Contact Plugin for Joomla! 1.5
*	(c) 2010 CartaNova Inc.
*
*	Author: 	Robert Gerald Porter
*	Version: 	0.2.1a
*	Status:		Closed alpha
*
*	Email: 		rob@cartanova.ca
*	Twitter:	@cartanovadesign
*	Identi.ca:	@cartanova
*	
*/
jQuery.noConflict();
		

jQuery(function(){
jQuery('.error').hide();

jQuery(".button").click(function() 
{
      // validate and process form here
//alert ("alert");return false;


	jQuery('.error').hide();
  	var name = jQuery("input#contact_name").val();
  	
  	if (name == "") 
  	{
        jQuery("label#name_error").show();
        jQuery("input#contact_name").focus();
        return false;
     }
     
  	var email = jQuery("input#contact_email").val();
  	
  	if (email == "") 
  	{
        jQuery("label#email_error").show();
        jQuery("input#contact_email").focus();
        return false;
     }
      
  	var text = jQuery("textarea#contact_text").val();
  	
  	if (text == "") 
  	{
        jQuery("label#text_error").show();
        jQuery("input#contact_text").focus();
        return false;
     }
      
	var id = jQuery("input#contact_id").val();
	 jQuery('#submit_btn').hide();
	
	var token = jQuery("input", jQuery("div#token-field")).attr("name");
	// grabbing the token name attribute (took a long time to get this...)

var view = 'contact';

var dataString = 'name='+ name + '&email=' + email + '&text=' + text + '&view=' + view + '&id=' + id + '&option=com_contact&task=submit&' + token + '=1&';
 

    jQuery.ajax({
      type: "POST",
      url: "index.php",
      data: dataString,
      success: function() {
        jQuery('#contact_form').html("<div id='jnova_message'></div>");
        jQuery('#jnova_message').html("<h2 class='jnova-form-response-header'>Contact Form Submitted!</h2>")
        .append("<div class='jnova-form-response-text'>"+jnovaResponseHTML+"</div>")
        .hide()
        .fadeIn(jnovaFadeInTime);
      }
    });
    return false;


    });

});
