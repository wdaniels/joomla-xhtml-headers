<?php
/**
* XHTML Plugin for Joomla!
*
* This is a system plugin which sets the Content-Type header to the correct
* mime type for XHTML (for capable browsers, tested using HTTP_ACCEPT).
*
* @author     Will Daniels <mail@willdaniels.co.uk>
* @version    0.1 (proto)
* @copyright  Copyright (C) 2008 Will Daniels. All rights reserved.
* @license    GNU/GPL
*/

// no direct access
defined( '_JEXEC' ) or die( 'Restricted access' );

// Import library dependencies
jimport('joomla.event.plugin');

/**
* System - XHTML Headers
*/
class plgSystemXHTML extends JPlugin
{
	/**
	* Constructor
	*
	* For php4 compatability we must not use the __constructor as a constructor
	* for plugins because func_get_args ( void ) returns a copy of all passed
	* arguments NOT references.  This causes problems with cross-referencing
	* necessary for the observer design pattern.
	*/
	function plgSystemXHTML(&$subject, $config)
	{
		parent::__construct($subject, $config);
	}

	/**
	* System event onAfterRender, invoked automatically by Joomla!
	*
	* @todo Check the q values to see if the browser prefers plain html.
	* @todo Add the document prolog if asked to.
	* @todo Maybe fix the rendered document for xhtml compatibility?
	*/
	function onAfterDispatch()
	{
		$app = JFactory::getApplication();
		$xhtml_admin = $this->params->get('xhtml_admin', 0);
		if ($app->isAdmin() && $xhtml_admin == 0) {
			return; // Don't run in admin
		}

		$xhtml = $this->params->get('xhtml_mime_type', 'application/xhtml+xml');
		$html = $this->params->get('html_mime_type', 'text/html');
		$charset = $this->params->get('charset', 'iso-8859-1');
		
		if(stristr($_SERVER['HTTP_ACCEPT'], $xhtml)) $browser_accepts = true;

		if($browser_accepts)
		{
			$doc = JFactory::getDocument();
			$doc->setMimeEncoding($xhtml);
			$doc->setCharset($charset);
		}
	
		return true;
	}
}
