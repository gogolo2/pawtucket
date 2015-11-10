<?php
	AssetLoadManager::register('superfish');
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
	<title><?php print $this->request->config->get('html_page_title'); ?></title>
	<meta http-equiv="Content-type" content="text/html; charset=utf-8" />
	<?php print MetaTagManager::getHTML(); ?>
	
	<meta name="viewport" content="width=device-width, initial-scale=1.0, minimum-scale=1.0"/>
	
	<link href="<?php print $this->request->getThemeUrlPath(true); ?>/css/global.css" rel="stylesheet" type="text/css" />
	<link href="<?php print $this->request->getThemeUrlPath(true); ?>/css/sets.css" rel="stylesheet" type="text/css" />
	<link href="<?php print $this->request->getThemeUrlPath(true); ?>/css/bookmarks.css" rel="stylesheet" type="text/css" />
	<link rel="stylesheet" href="<?php print $this->request->getBaseUrlPath(); ?>/js/videojs/video-js.css" type="text/css" media="screen" />
	<link rel="stylesheet" href="<?php print $this->request->getBaseUrlPath(); ?>/js/jquery/jquery-autocomplete/jquery.autocomplete.css" type="text/css" media="screen" />
 	<!--[if IE]>
    <link rel="stylesheet" type="text/css" href="<?php print $this->request->getThemeUrlPath(true); ?>/css/iestyles.css" />
	<![endif]-->

	<!--[if (!IE)|(gte IE 8)]><!-->
	<link href="<?php print $this->request->getBaseUrlPath(); ?>/js/DV/viewer-datauri.css" media="screen" rel="stylesheet" type="text/css" />
	<link href="<?php print $this->request->getBaseUrlPath(); ?>/js/DV/plain-datauri.css" media="screen" rel="stylesheet" type="text/css" />
	<link href="<?php print $this->request->getBaseUrlPath(); ?>/js/DV/plain.css" media="screen" rel="stylesheet" type="text/css" />
	<!--<![endif]-->
	<!--[if lte IE 7]>
	<link href="<?php print $this->request->getBaseUrlPath(); ?>/viewer.css" media="screen" rel="stylesheet" type="text/css" />
	<link href="<?php print $this->request->getBaseUrlPath(); ?>/plain.css" media="screen" rel="stylesheet" type="text/css" />
	<![endif]-->
	<link rel="stylesheet" href="<?php print $this->request->getBaseUrlPath(); ?>/js/jquery/jquery-tileviewer/jquery.tileviewer.css" type="text/css" media="screen" />
<?php
	print AssetLoadManager::getLoadHTML($this->request->getBaseUrlPath());
?>
	<script type="text/javascript">
		 jQuery(document).ready(function() {
			jQuery('#quickSearch').searchlight('<?php print $this->request->getBaseUrlPath(); ?>/index.php/Search/lookup', {showIcons: false, searchDelay: 100, minimumCharacters: 3, limitPerCategory: 3});
		});
		// initialize CA Utils
			var caUIUtils = caUI.initUtils();
	</script>
	


</head>
<body class='pawtucket'>
<?php include_once("analytics.php") ?>

		<div id="topBar">
		<a href='http://www.appalshop.org'>Back to Appalshop website ></a>
<?php
/*
			$vb_client_services = (bool)$this->request->config->get('enable_client_services');
			if (!$this->request->config->get('dont_allow_registration_and_login')) {
				if($this->request->isLoggedIn()){
					$o_client_services_config = caGetClientServicesConfiguration();
					if ($vb_client_services && (bool)$o_client_services_config->get('enable_user_communication')) {
						//
						// Unread client communications
						//
						$t_comm = new ca_commerce_communications();
						$va_unread_messages = $t_comm->getMessages(array('unreadOnly' => true, 'user_id' => $this->request->getUserID()));
						
						$va_message_set_ids = array();
						foreach($va_unread_messages as $vn_transaction_id => $va_messages) {
							$va_message_set_ids[] = $va_messages[0]['set_id'];
						}
						
					}
					
					if(!$this->request->config->get('disable_my_collections')){
						# --- get all sets for user
						$t_set = new ca_sets();
						$va_sets = caExtractValuesByUserLocale($t_set->getSets(array('table' => 'ca_objects', 'user_id' => $this->request->getUserID())));
						if(is_array($va_sets) && (sizeof($va_sets) > 1)){
							print "<div id='lightboxLink'>
										<a href='#' onclick='$(\"#lightboxList\").toggle(0, function(){
																								if($(\"#lightboxLink\").hasClass(\"lightboxLinkActive\")) {
																									$(\"#lightboxLink\").removeClass(\"lightboxLinkActive\");
																								} else {
																									$(\"#lightboxLink\").addClass(\"lightboxLinkActive\");
																								}
																								});')>Lightbox</a>";
							if(is_array($va_message_set_ids) && sizeof($va_message_set_ids)){
								print " <img src='".$this->request->getThemeUrlPath()."/graphics/icons/envelope.gif' border='0'>";
							}
							print "<div id='lightboxList'><b>"._t("your lightboxes").":</b><br/>";
							foreach($va_sets as $va_set){
								print caNavLink($this->request, ((strlen($va_set["name"]) > 30) ? substr($va_set["name"], 0, 30)."..." : $va_set["name"]), "", "", "Sets", "Index", array("set_id" => $va_set["set_id"]));
								if($vb_client_services && is_array($va_message_set_ids) && in_array($va_set["set_id"], $va_message_set_ids)){
									print " <img src='".$this->request->getThemeUrlPath()."/graphics/icons/envelope.gif' border='0'>";
								}
								print "<br/>";
							}
							print "</div>";
							print "</div>";
						}else{
							print caNavLink($this->request, _t("Lightbox"), "", "", "Sets", "Index");
							if($vb_client_services && is_array($va_message_set_ids) && sizeof($va_message_set_ids)){
								print " <img src='".$this->request->getThemeUrlPath()."/graphics/icons/envelope.gif' border='0'>";
							}
						}
					}
					
					if ($vb_client_services && (bool)$o_client_services_config->get('enable_my_account')) {
						$t_order = new ca_commerce_orders();
						if ($vn_num_open_orders = sizeof($va_orders = $t_order->getOrders(array('user_id' => $this->request->getUserID(), 'order_status' => array('OPEN', 'SUBMITTED', 'IN_PROCESSING', 'REOPENED'))))) {
							print "<span style='color: #cc0000; font-weight: bold;'>".caNavLink($this->request, _t("My Account (%1)", $vn_num_open_orders), "", "", "Account", "Index")."</span>";
						} else {
							print caNavLink($this->request, _t("My Account"), "", "", "Account", "Index");
						}
							
					}				
					
					if($this->request->config->get('enable_bookmarks')){
						print caNavLink($this->request, _t("My Bookmarks"), "", "", "Bookmarks", "Index");
					}
					print caNavLink($this->request, _t("Logout"), "", "", "LoginReg", "logout");
				}else{
					print caNavLink($this->request, _t("Login/Register"), "", "", "LoginReg", "form");
				}
			}
			
			# Locale selection
			global $g_ui_locale;
			$vs_base_url = $this->request->getRequestUrl();
			$vs_base_url = ((substr($vs_base_url, 0, 1) == '/') ? $vs_base_url : '/'.$vs_base_url);
			$vs_base_url = str_replace("/lang/[A-Za-z_]+", "", $vs_base_url);
			
			if (is_array($va_ui_locales = $this->request->config->getList('ui_locales')) && (sizeof($va_ui_locales) > 1)) {
				print caFormTag($this->request, $this->request->getAction(), 'caLocaleSelectorForm', null, 'get', 'multipart/form-data', '_top', array('disableUnsavedChangesWarning' => true));
			
				$va_locale_options = array();
				foreach($va_ui_locales as $vs_locale) {
					$va_parts = explode('_', $vs_locale);
					$vs_lang_name = Zend_Locale::getTranslation(strtolower($va_parts[0]), 'language', strtolower($va_parts[0]));
					$va_locale_options[$vs_lang_name] = $vs_locale;
				}
				print caHTMLSelect('lang', $va_locale_options, array('id' => 'caLocaleSelectorSelect', 'onchange' => 'window.location = \''.caNavUrl($this->request, $this->request->getModulePath(), $this->request->getController(), $this->request->getAction(), array('lang' => '')).'\' + jQuery(\'#caLocaleSelectorSelect\').val();'), array('value' => $g_ui_locale, 'dontConvertAttributeQuotesToEntities' => true));
				print "</form>\n";
			
			}
*/			
?>
		</div><!-- end topbar -->
		<div id="pageArea">
			<div id="header">
<?php
				print caNavLink($this->request, "<img src='".$this->request->getThemeUrlPath()."/graphics/spacer.gif' width='300' height='80' border='0'>", "", "", "", "");
		print "<div id='headerLinks'>";	
// 				print "<div class='headerLink'><a href=''>About</a></div>";
// 				print "<div class='headerLink'><a href='http://archive.appalshop.org/news'>News</a></div>";
// 				print "<div class='headerLink'><a href='http://archive.appalshop.org/news/?page_id=13'>Services</a></div>";
// 				print "<div class='headerLink'><a href='http://archive.appalshop.org/news/?page_id=15'>Support</a></div>";
// 				print "<div class='headerLink'><a href='http://archive.appalshop.org/news/?page_id=17'>Contact</a></div>";
// 				print "<div class='headerLink'><a href='http://www.facebook.com' style='padding-right:5px;'><img src='".$this->request->getThemeUrlPath()."/graphics/f_logo.jpg' border='0'></a></div>";
// 				print "<div class='headerLink'><a href='http://www.twitter.com' style='padding-right:5px;'><img src='".$this->request->getThemeUrlPath()."/graphics/imgres.jpg' border='0'></a></div>";

?>
			<ul class='sf-menu'>
				<li><a href='#'>About</a>
					<ul>
						<li><a href='http://archive.appalshop.org/news/?page_id=7'>History of Appalshop</a></li>
						<li><a href='http://archive.appalshop.org/news/?page_id=75'>The Archive</a></li>
						<li><a href='http://archive.appalshop.org/news/?page_id=9'>Supporters</a></li>
						<li><a href='http://archive.appalshop.org/news/?page_id=11'>Staff</a></li>
					</ul>
				</li>
				<li><a href='http://archive.appalshop.org/news'>News</a></li>
				<li><a href='http://archive.appalshop.org/news/?page_id=13'>Services</a></li>
				<li><a href='http://archive.appalshop.org/news/?page_id=15'>Support</a></li>
				<li><a href='http://archive.appalshop.org/news/?page_id=17'>Contact</a></li>
			</ul>
			<a href='https://www.facebook.com/Appalshop?fref=ts'><img src='<?php print $this->request->getThemeUrlPath(); ?>/graphics/f_logo.jpg' border='0' style='margin:0px 5px 0px 8px;'></a>
			<a href='https://twitter.com/AppalArchive'><img src='<?php print $this->request->getThemeUrlPath(); ?>/graphics/imgres.jpg' border='0'></a>
		</div>

			</div><!-- end header -->
<?php
	// get last search ('basic_search' is the find type used by the SearchController)
	$o_result_context = new ResultContext($this->request, 'ca_objects', 'basic_search');
	$vs_search = $o_result_context->getSearchExpression();
?>
			<div id="nav">
<?php				
				print caNavLink($this->request, _t("Browse The Collections"), '', '', 'Browse', 'Index')." ";
				print caNavLink($this->request, _t("Finding Aids"), '', 'FindingAids', 'List', 'Index')." ";
				print caNavLink($this->request, _t("Special Projects"), '', 'simpleGallery', 'Show', 'Index');
				#print join(" ", $this->getVar('nav')->getHTMLMenuBarAsLinkArray());
?>			
				<div id="search"><form name="header_search" action="<?php print caNavUrl($this->request, '', 'Search', 'Index'); ?>" method="get">
						<a href="#" style="position: absolute; z-index:1500; margin: 2px 0px 0px 140px;" name="searchButtonSubmit" onclick="document.forms.header_search.submit(); return false;"><img src='<?php print $this->request->getThemeUrlPath(); ?>/graphics/searchglass.gif' width='13' height='13' border='0'></a>
						<input type="text" name="search" id="quickSearch"  autocomplete="off" size="100"  placeholder="Search"/>
				</form></div>
				<a href='https://npo1.networkforgood.org/Donate/Donate.aspx?npoSubscriptionId=10058' target='_blank' class='donateLink'>Donate</a>
			</div><!-- end nav -->
			<div id='contentArea'>
<script>

	$(document).ready(function(){
		$("ul.sf-menu").superfish();
	});

</script>