/* Start: Initialize all components.
   Function name: initComponents()
   Parameters:
       N/A
   Note:
       All components initialization should be placed here. 
*/
var initAdminComponents = function(){

	//Hide all related tabs
	$(".sub_tab_content").hide();
	$(".hiw_sub_tab_content").hide();
	$(".cu_sub_tab_content").hide();

	//Add on click event to every list items (Pages)
	$("ul#pages_sub_list li").click(function() {
		var headerId = $(this).attr("id");
		if(headerId) {
			var key = headerId.replace(/pages_sub_tab_/g,'');
			showHideSubTab(key, true);
		}
	});

	//Add on click event to every list items (How it works)
	$("ul#hiw_tab_list li").click(function() {
		var headerId = $(this).attr("id");
		if(headerId) {
			var key = headerId.replace(/hiw_sub_tab_/g,'');
			showHideHIWSubtabContent(key, true);
		}
	});

	//Add on click event to every list items (FAQ)
	$("ul#faq_tab_list li").click(function() {
		var headerId = $(this).attr("id");
		if(headerId) {
			var key = headerId.replace(/faq_sub_tab_/g,'');
			showHideFaqSubtabContent(key, true);
		}
	});

	//Add on click event to every list items (Customers and Users)
	$("ul#cu_sub_list li").click(function() {
		var headerId = $(this).attr("id");
		if(headerId) {
			var key = headerId.replace(/cu_sub_tab_/g,'');
			showHideCustomersUsersSubtabContent(key, true);
		}
	});

	showHideTab("tab1", true);

	//Show default tabs
	jQuery("#pages_tab_home").show();
	jQuery("#pages_hiw_consumer").show();
	jQuery("#pages_faq_consumer").show();
	jQuery("#cu_tab_home").show();

	$("#outer-tabs li").click(function() {
		var headerId = $(this).attr("id");
		if(headerId) {
			$("#outer-tabs li").removeClass('current');
			$(this).addClass("current");
			$(".tab_content").hide();
			var selected_tab = headerId.substring(0,4) // means tab1header to tab1
			$("#"+selected_tab).show();
		}
	});

	// javascript file for curve bottons
	var serviceRow = $('.serviceRequest');
	var attr_id = "";
	serviceRow.click(function() {
		var id = $(this).attr('id');
		if (id && id.split('-').length > 1) {
			attr_id = id.split('-')[1]; 
			$('#requestTableRowExpand-'+attr_id).toggle();
		}
	});
	initializeCareerPage();
	initializePressnewsPage();

	hideAllPopupTabContents();
	setupPopupTabs();
	showHidePopupTab('tab1', true);
	
	//Temporary only since popup is not yet ajaxified
	initPopupComponents();
	showHideCustomersInfoAdsContent('ads', true);
};

var initPopupComponents = function(){

	//Add on click event to every list items (Customer Infos - Activities)


	//Add on click event to every list items (Customer Infos - Ads)
	$("ul#ads_sub_list li").click(function() {
		var headerId = $(this).attr("id");
		if(headerId) {
			var key = headerId.replace(/cust_ads_subtab_/g,'');
			showHideCustomersInfoAdsContent(key, true);
		}
	});
};


/* Start: Hide all sub tabs.
   Function name: hideAllSubTabs()
   Parameters:
       N/A
*/
var hideAllSubTabs = function(){;
	$(".sub_tab_content").hide();
};
/* Start: Show or Hide sub Tab.
   Function name: showHideSubTab()
   Parameters:
       key - The name of the tab to show or hide.
       show_tab - Flag for showing or hiding (True = Show / False = Hide).
*/
var showHideSubTab = function(key, show_tab){
	if(show_tab)
	{
		hideAllSubTabs();
		jQuery("#loaded_page_tab").val("pages_sub_tab_" + key);
		jQuery("#pages_tab_"+key).show();

		$("ul#pages_sub_list li a").removeClass('pages_current');
		$("#pages_sub_tab_"+key).children(":first").addClass("pages_current");
	}
	else
	{
		jQuery("#pages_tab_"+key).hide();
	}
};


/* Start: Hide all How it works sub tabs.
   Function name: hideAllHiwSubTabs()
   Parameters:
       N/A
*/
var hideAllHiwSubTabs = function(){;
	$(".hiw_sub_tab_content").hide();
};
/* Start: Show or Hide sub Tab.
   Function name: showHideHIWSubtabContent()
   Parameters:
       key - The name of the tab to show or hide.
       show_tab - Flag for showing or hiding (True = Show / False = Hide).
*/
var showHideHIWSubtabContent= function(key, show_tab) {
	//alert("key: " + key);
	if(show_tab)
	{
		hideAllHiwSubTabs();
		jQuery("#pages_hiw_"+key).show();
		$('ul#hiw_tab_list li a').removeClass('hiw_current');
		$("#hiw_sub_tab_"+key).children(':first').addClass('hiw_current');
		$("#span_hiw_page_subtitle").html($("#hiw_subtab_"+key).val());
	}
	else
	{
		jQuery("#pages_hiw_"+key).hide();
	}
};


/* Start: Hide all FAQ sub tabs.
   Function name: hideAllFaqSubTabs()
   Parameters:
       N/A
*/
var hideAllFaqSubTabs = function(){;
	$(".faq_sub_tab_content").hide();
};
/* Start: Show or Hide sub Tab.
   Function name: showHideFaqSubtabContent()
   Parameters:
       key - The name of the tab to show or hide.
       show_tab - Flag for showing or hiding (True = Show / False = Hide).
*/
var showHideFaqSubtabContent= function(key, show_tab) {
	if(show_tab)
	{
		hideAllFaqSubTabs();
		jQuery("#pages_faq_"+key).show();
		$('ul#faq_tab_list li a').removeClass('faq_current');
		$("#faq_sub_tab_"+key).children(':first').addClass('faq_current');
		$("#span_faq_page_subtitle").html($("#faq_subtab_"+key).val());
	}
	else
	{
		jQuery("#pages_faq_"+key).hide();
	}
};


/* Start: Hide all Customers Users sub tabs.
   Function name: hideAllCustomersUsersSubTabs()
   Parameters:
       N/A
*/
var hideAllCustomersUsersSubTabs = function(){
	$("div.cu_sub_tab_content").hide();
};
/* Start: Show or Hide sub Tab.
   Function name: showHideCustomersUsersSubtabContent()
   Parameters:
       key - The name of the tab to show or hide.
       show_tab - Flag for showing or hiding (True = Show / False = Hide).
*/
var showHideCustomersUsersSubtabContent= function(key, show_tab) {
	//alert("showHideCustomersUsersSubtabContent\n" + "key: " + key + "\nshow_tab : " + show_tab);
	if(show_tab)
	{
		hideAllCustomersUsersSubTabs();

		jQuery("#loaded_cu_tab").val("cu_sub_tab_" + key);
		jQuery("#cu_tab_"+key).show();

		$("ul#cu_sub_list li a").removeClass('cu_current');
		$("#cu_sub_tab_"+key).children(":first").addClass("cu_current");


	}
	else
	{
		jQuery("#cu_tab_"+key).hide();
	}
};


/* Start: Initialize career page by hiding the entry div while showing the default div.
   Function name: initializeCareerPage()
   Parameters:
       N/A
*/
var initializeCareerPage = function(){
	jQuery("#div_careers_entry").hide();
	jQuery("#div_careers_display").show();
};
/* Start: Initialize press news page by hiding the entry div while showing the default div.
   Function name: initializePressnewsPage()
   Parameters:
       N/A
*/
var initializePressnewsPage = function(){
	jQuery("#div_pnews_entry").hide();
	jQuery("#div_pnews_display").show();
};

/* Start: Set careers page mode.
   Function name: enterPageMode()
   Parameters:
	   page - the page key that will identify the selected page
       mode - the career page mode (display or entry)
*/
var enterPageMode = function(page, mode){
	if(mode == "entry")
	{
		jQuery("#div_"+page+"_entry").show();
		jQuery("#div_"+page+"_display").hide();	
	}
	else if(mode == "display")
	{
		jQuery("#div_"+page+"_entry").hide();
		jQuery("#div_"+page+"_display").show();
	}
};

/* Start: Expand and collapse inquries search filters.
   Function name: expandCollapseSearchFilters()
   Parameters:
       objId - Id of the expand/collapse button image
*/
var expandCollapseSearchFilters = function(objId){
	if($("#filters_panel").is(":visible"))
	{
		$("#"+objId).removeClass("expanded_button").addClass("collapsed_button");
		$("#filters_panel").hide();
	}else{
		$("#"+objId).removeClass("collapsed_button").addClass("expanded_button");
		$("#filters_panel").show();
	}
};

var showCustomerInfo = function(customerId){
	var params = new Array();
	params.push("cust_id="+customerId);
	var url = "" + params.join("&");
	//ajax here

	showCustomersModalPopup();

};
var showCustomersModalPopup = function(){
		jQuery('.modal-profile').fadeIn("slow");
		jQuery('.modal-lightsout').fadeTo("slow", .5);
};


var hideAllPopupTabContents = function(){
	$(".popup_tab_content").hide();
};
var setupPopupTabs = function(){
	$("#popup_outer_tabs li").click(function() {
		var headerId = $(this).attr("id");
		if(headerId) {
			$("#popup_outer_tabs li").removeClass('popup-current');
			$(this).addClass("popup-current");
			hideAllPopupTabContents();
			headerId = headerId.replace(/popup_/g,"");
			var selected_tab = headerId.substring(0,4) // means tab1header to tab1
			showHidePopupTab(selected_tab, true);
			//$("#popup_"+selected_tab).show();
		}
	});
};
var showHidePopupTab = function(tab_name, show_tab){
	if(show_tab)
	{
		hideAllPopupTabContents();
		jQuery("#popup_"+tab_name).show();
	}
	else
	{
		jQuery("#popup_"+tab_name).hide();
	}
};

/* Start: Hide all Customers Users sub tabs.
   Function name: hideAllCustomersInfoAdsSubTabs()
   Parameters:
       N/A
*/
var hideAllCustomersInfoAdsSubTabs = function(){
	$("div.ads_tab_content").hide();
};
/* Start: Show or Hide sub Tab.
   Function name: showHideCustomersInfoAdsContent()
   Parameters:
       key - The name of the tab to show or hide.
       show_tab - Flag for showing or hiding (True = Show / False = Hide).
*/
var showHideCustomersInfoAdsContent = function(key, show_tab) {
	if(show_tab)
	{
		hideAllCustomersInfoAdsSubTabs();

		jQuery("#loaded_ads_tab").val("ads_tab_" + key);
		jQuery("#ads_tab_"+key).show();

		$("ul#ads_sub_list li a").removeClass('ads-current');
		$("#cust_ads_subtab_"+key).children(":first").addClass("ads-current");


	}
	else
	{
		jQuery("#ads_tab_"+key).hide();
	}
};