<?php
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
defined( '_JEXEC' ) or die( 'Restricted access' );

$mainframe->registerEvent('onPrepareContent', 'pluginJNovaContact');


function pluginJNovaContact(&$row, &$params)
{

	$plugin =& JPluginHelper::getPlugin('content', 'jnovacontact');
	
	$pluginParams = new JParameter( $plugin->params );

	preg_match_all('/\{jnovacontact (.*)\}/U', $row->text, $matches);
	
	$idcode = $matches[1][0];

	$contact = contentJNovaContact_checkContact($idcode);
	
	if(!$contact)
		return false;
		
		// the above needs to change to support multiple forms (future version).
		
	if($pluginParams->get('width_manual', 1))
		$width = $pluginParams->get('width');
	else
		$width = NULL;
		
	$response_html = addslashes($pluginParams->get('response_html'));
	$fade_in_time = addslashes($pluginParams->get('fade_in_time'));
	
	$html = contentJNovaContact_createForm($idcode, $width);
	$row->text = str_replace("{jnovacontact $idcode}", $html, $row->text);
		
	$document = JFactory::getDocument();
	$document->addScriptDeclaration('var jnovaFadeInTime = '.$fade_in_time.';');
	$document->addScriptDeclaration('var jnovaResponseHTML = "'.$response_html.'";');
	$document->addScript('plugins/content/jnovacontact.js');
	
	return true;
}

function contentJNovaContact_checkContact($id)
{

	$db =& JFactory::getDBO();
	$id = $db->getEscaped($id);
	$query = "SELECT * FROM #__contact_details WHERE id = '$id'";
	
	$db->setQuery($query);
	
	$contact = $db->loadObject();
	
	return $contact;

}

function contentJNovaContact_createForm($id, $width = NULL)
{

	if($width)
		$width_param = "style=\"width: ".$width."px\"";
	else
		$width_param = "";

	$html .= ' <div id="contact_form" class="jnova-contact-form-container" '.$width_param.'>
				
				<form id="contact" name="contact" method="post" action="index.php" class="jnova-form"> <fieldset> 
				
				<label for="contact_name" id="name_label" class="jnova-form-label">Name</label> 
				
				<input name="name" id="contact_name" class="text-input" type="text" /> 
				
				<label class="error" for="contact_name" id="name_error" class="jnova-form-label-error">This field is required.</label> 
				
				<label for="email" id="email_label" class="jnova-form-label">Return Email</label> 
				
				<input name="email" id="contact_email" class="text-input" type="text" /> 
				
				<label class="error" for="contact_email" id="email_error" class="jnova-form-label-error">This field is required.</label> 
				
				<label for="contact_text" id="text_label" class="jnova-form-label">Enter your Message</label> 
				
				<textarea name="text" id="contact_text" class="jnova-text-input"></textarea> </fieldset>

				<label class="error" for="contact_text" id="text_error" class="jnova-form-label-error">This field is required.</label> <br /> 
				
				<input name="submit" id="submit_btn" value="Send" type="submit" class="jnova-submit-button button" /> 
				
				<input name="view" value="contact" type="hidden" /> <input name="id" value="'.$id.'" id="contact_id" type="hidden" /> 
				
				<input name="task" value="submit" type="hidden" />  <div id="token-field">'. JHTML::_( 'form.token' ) . '</div></form></div>';

	return $html;

}