<?php
/* ----------------------------------------------------------------------
 * includes/ShowController.php
 * ----------------------------------------------------------------------
 * CollectiveAccess
 * Open-source collections management software
 * ----------------------------------------------------------------------
 *
 * Software by Whirl-i-Gig (http://www.whirl-i-gig.com)
 * Copyright 2009-2013 Whirl-i-Gig
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
 * ----------------------------------------------------------------------
 */
 
 	require_once(__CA_MODELS_DIR__.'/ca_sets.php');
 	require_once(__CA_MODELS_DIR__.'/ca_objects.php');
 	require_once(__CA_MODELS_DIR__.'/ca_set_items.php');
 	require_once(__CA_MODELS_DIR__.'/ca_lists.php');
 	require_once(__CA_APP_DIR__.'/helpers/accessHelpers.php');
 	require_once(__CA_LIB_DIR__.'/ca/ResultContext.php');
 
 	class ShowController extends ActionController {
 		# -------------------------------------------------------
 		private $opo_plugin_config;			// plugin config file
 		private $ops_theme;						// current theme
 		private $opo_result_context;			// current result context
 		
 		# -------------------------------------------------------
 		public function __construct(&$po_request, &$po_response, $pa_view_paths=null) {
 			AssetLoadManager::register('panel');
 			AssetLoadManager::register('jquery', 'expander');
 			AssetLoadManager::register('jquery', 'swipe');
 			
 			parent::__construct($po_request, $po_response, $pa_view_paths);
			$this->opo_plugin_config = Configuration::load($this->request->getAppConfig()->get('application_plugins').'/simpleGallery/conf/simpleGallery.conf');
 			
 			if (!(bool)$this->opo_plugin_config->get('enabled')) { die(_t('simpleGallery plugin is not enabled')); }
 			
 			$this->ops_theme = __CA_THEME__;																		// get current theme
 			if(!is_dir(__CA_APP_DIR__.'/plugins/simpleGallery/views/'.$this->ops_theme)) {		// if theme is not defined for this plugin, try to use "default" theme
 				$this->ops_theme = 'default';
 			}
 			
 			$this->opo_result_context = new ResultContext($po_request, 'ca_objects', 'simple_gallery');
 		}
 		# -------------------------------------------------------
 		public function Index() {
 			$va_access_values = caGetUserAccessValues($this->request);
 			
 			// get sets for public display
 			$t_list = new ca_lists();
 			$vn_public_set_type_id = $t_list->getItemIDFromList('set_types', $t_list->getAppConfig()->get('simpleGallery_set_type'));
 			
 			$t_set = new ca_sets();
 			$va_sets = caExtractValuesByUserLocale($t_set->getSets(array('table' => 'ca_objects', 'checkAccess' => $va_access_values, 'setType' => $vn_public_set_type_id)));
 			$va_set_first_items = $t_set->getFirstItemsFromSets(array_keys($va_sets), array("version" => "medium", "checkAccess" => $va_access_values));
 			$va_set_descriptions = $t_set->getAttributeFromSets($this->opo_plugin_config->get('set_description_element_code'), array_keys($va_sets), array("checkAccess" => $va_access_values));
 			$this->view->setVar('sets', $va_sets);
 			$this->view->setVar('first_items_from_sets', $va_set_first_items);
 			$this->view->setVar('set_descriptions', $va_set_descriptions);
 					
 			$this->render($this->ops_theme.'/landing_html.php');
 		}
 		# -------------------------------------------------------
 		public function displaySet() {
 			# --- set info
 			$pn_set_id = $this->request->getParameter('set_id', pInteger);
 			$t_set = new ca_sets($pn_set_id);
 			
 			$va_access_values = caGetUserAccessValues($this->request);
 			
 			# Enforce access control
 			if(sizeof($va_access_values) && !in_array($t_set->get("access"), $va_access_values)){
  				$this->notification->addNotification(_t("This set is not available for view"), "message");
 				$this->response->setRedirect(caNavUrl($this->request, "", "", "", ""));
 				return;
 			}
 			
 			$this->view->setVar('t_set', $t_set);
 			$va_items = caExtractValuesByUserLocale($t_set->getItems(array('thumbnailVersions' => array('widepreview', 'medium', 'setimage'), "checkAccess" => $va_access_values)));
 			$this->view->setVar('items', $va_items);
 			
 			$va_row_ids = array();
 			foreach($va_items as $vn_item_id => $va_item_info) {
 				$va_row_ids[] = $va_item_info['row_id'];
 			}
 			
 			
 			# --- all featured sets - for display in right hand column
 			
 			// get sets for public display
 			$t_list = new ca_lists();
 			$vn_public_set_type_id = $t_list->getItemIDFromList('set_types', $t_list->getAppConfig()->get('simpleGallery_set_type'));
 			
 			$t_set = new ca_sets($pn_set_id);
 			$va_sets = caExtractValuesByUserLocale($t_set->getSets(array('table' => 'ca_objects', 'checkAccess' => $va_access_values, 'setType' => $vn_public_set_type_id)));
 		
 			$va_set_first_items = array();
 			$va_set_first_items = $t_set->getFirstItemsFromSets(array_keys($va_sets), array("version" => "icon", "checkAccess" => $va_access_values));
 		
 			$this->view->setVar('sets', $va_sets);
 			$this->view->setVar('first_items_from_sets', $va_set_first_items);
 			
 			$this->view->setVar('set_title', $t_set->getLabelForDisplay());
 			$this->view->setVar('set_description', $t_set->get($this->opo_plugin_config->get('set_description_element_code'), array('convertLinkBreaks' => true)));
 			
 			
 			// Needed to figure out what result context to use on details
			$this->opo_result_context->setParameter('set_id', $pn_set_id);
			$this->opo_result_context->setResultList($va_row_ids);
			$this->opo_result_context->setAsLastFind();
			$this->opo_result_context->saveContext();
 			
 			$this->render($this->ops_theme.'/set_info_html.php');
 		}
 		# -------------------------------------------------------
 		# --- returns set item info in panel - used with small image list results
 		public function setItemInfo(){
 			$va_access_values = caGetUserAccessValues($this->request);
 
 			$pn_set_id = $this->request->getParameter('set_id', pInteger);
 			$t_set = new ca_sets($pn_set_id);
 			$this->view->setVar('set_id', $pn_set_id);
 			
 			$pn_set_item_id = $this->request->getParameter('set_item_id', pInteger);
 			$t_set_item = new ca_set_items($pn_set_item_id);
 			
 			$va_set_item_info = array();
 			$va_items = $t_set->getItemIDs(array("checkAccess" => $va_access_values));
 			$pn_previous_id = "";
 			foreach($va_items as $vn_item_id => $va_item_info){
 				if($va_set_item_info["item_id"]){
 					$va_set_item_info["next_id"] = $vn_item_id;
 					break;
 				}
 				if($pn_set_item_id == $vn_item_id){
 					$va_set_item_info["previous_id"] = $pn_previous_id;
 					$va_set_item_info["item_id"] = $vn_item_id;
 				}
 				$pn_previous_id = $vn_item_id;
 			}
 			
 			
 			$va_set_item_info["item_id"] = $t_set_item->get("item_id");
			$va_reps = $t_set_item->getRepresentations(array("mediumlarge", "small"), null, array("return_with_access" => $va_access_values));
			$va_rep = array_shift($va_rep);
			
			$this->view->setVar('t_object_representation', $t_rep = new ca_object_representations($va_rep['representation_id']));
			$va_rep_display_info = caGetMediaDisplayInfo('cropped_gallery_media_overlay', $t_rep->getMediaInfo('media', 'INPUT', 'MIMETYPE'));
			
			$this->view->setVar('rep_display_version', $va_rep_display_info['display_version']);
			unset($va_display_info['display_version']);
			$va_rep_display_info['poster_frame_url'] = $t_rep->getMediaUrl('media', $va_rep_display_info['poster_frame_version']);
			unset($va_display_info['poster_frame_version']);
			$this->view->setVar('rep_display_options', $va_rep_display_info);

			$va_set_item_info["info"] = $va_rep['info'];
			$va_set_item_info["label"] = $t_set_item->getLabelForDisplay();
			$va_set_item_info["description"] = $t_set_item->get($this->opo_plugin_config->get('set_description_element_code'), array('convertLineBreaks' => true));
			$va_set_item_info["item_description"] = $t_set_item->get($this->opo_plugin_config->get('set_item_description_element_code'), array('convertLineBreaks' => true));
			$va_set_item_info["row_id"] = $t_set_item->get("row_id");
			$va_set_item_info["lesson"] = $t_set_item->get('set_item_description');
			
			$t_object = new ca_objects($t_set_item->get("row_id"));
			$va_set_item_info["object_label"] = $t_object->getLabelForDisplay();
 			
 			$this->view->setVar('item_info', $va_set_item_info);
 			
 			$this->render($this->ops_theme.'/ajax_item_info_html.php');
 		}
 		# -------------------------------------------------------
 	}
 ?>
