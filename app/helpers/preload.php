<?php
/** ---------------------------------------------------------------------
 * app/helpers/preload.php : includes for commonly used classes and libraries
 * ----------------------------------------------------------------------
 * CollectiveAccess
 * Open-source collections management software
 * ----------------------------------------------------------------------
 *
 * Software by Whirl-i-Gig (http://www.whirl-i-gig.com)
 * Copyright 2008-2014 Whirl-i-Gig
 *
 * For more information visit http://www.CollectiveAccess.org
 *
 * This program is free software; you may redistribute it and/or modify it under
 * the terms of the provided license as published by Whirl-i-Gig
 *
 * CollectiveAccess is distributed in the hope that it will be useful, but
 * WITHOUT ANY WARRANTIES whatsoever, including any implied warranty of 
 * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  
 *
 * This source code is free and modifiable under the terms of 
 * GNU General Public License. (http://www.gnu.org/copyleft/gpl.html). See
 * the "license.txt" file for details, or visit the CollectiveAccess web site at
 * http://www.CollectiveAccess.org
 * 
 * @package CollectiveAccess
 * @subpackage utils
 * @license http://www.gnu.org/copyleft/gpl.html GNU Public License version 3
 * 
 * ----------------------------------------------------------------------
 */
 
  /**
   *
   */
   
	require_once(__CA_BASE_DIR__.'/vendor/autoload.php');	// composer
	require_once(__CA_LIB_DIR__."/core/Utils/Debug.php");
	
	require_once(__CA_LIB_DIR__."/core/Cache/MemoryCache.php"); // is used in utilityHelpers
	require_once(__CA_LIB_DIR__."/core/Cache/ExternalCache.php"); // is used in utilityHelpers
	require_once(__CA_LIB_DIR__."/core/Cache/CompositeCache.php"); // is used in utilityHelpers
	
	require(__CA_APP_DIR__."/helpers/utilityHelpers.php");
	require(__CA_APP_DIR__."/helpers/navigationHelpers.php");
	require(__CA_APP_DIR__."/helpers/mailHelpers.php");
	require(__CA_APP_DIR__."/helpers/clientServicesHelpers.php");
	
	require(__CA_LIB_DIR__."/core/BaseModel.php");
	require(__CA_LIB_DIR__."/core/Controller/AppController.php");
	require(__CA_LIB_DIR__."/core/Zend/Translate.php");
	require(__CA_LIB_DIR__."/core/Zend/Registry.php");
	require(__CA_LIB_DIR__."/ca/Search/DidYouMean.php");
	
	require(__CA_LIB_DIR__."/ca/MetaTagManager.php");
	require(__CA_LIB_DIR__."/ca/AssetLoadManager.php");
	require(__CA_LIB_DIR__."/ca/TooltipManager.php");

	require(__CA_LIB_DIR__."/ca/ResultContext.php");
	require(__CA_LIB_DIR__."/ca/AppNavigation.php");
	
	require(__CA_LIB_DIR__."/core/Controller/ActionController.php");
	
	require(__CA_MODELS_DIR__."/ca_acl.php");
	
	// initialize Tooltip manager
	TooltipManager::init();
	
	require(__CA_MODELS_DIR__.'/ca_sets.php');
	require(__CA_MODELS_DIR__.'/ca_commerce_communications.php');
?>
