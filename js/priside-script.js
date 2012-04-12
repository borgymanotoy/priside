/* Start: Hide all tabs.
   Function name: hideAllTabs()
   Parameters:
       N/A
*/
var hideAllTabs = function(){
	$(".tab_content").hide();
};

/* Start: Show or Hide Tab.
   Function name: showHideTab()
   Parameters:
       tab_name - The name of the tab to show or hide.
       show_tab - Flag for showing or hiding (True = Show / False = Hide).
*/
var showHideTab = function(tab_name, show_tab){
	if(show_tab)
	{
		jQuery("#"+tab_name).show();
	}
	else
	{
		jQuery("#"+tab_name).hide();
	}
}

/* Start: Initialize all components.
   Function name: initComponents()
   Parameters:
       N/A
   Note:
       All components initialization should be placed here. 
*/
var initComponents = function(){
	//by default enable the first tab
	hideAllTabs();
	showHideTab("tab1", true);

	$("#toc li").click(function() {
		var headerId = $(this).attr("id");
		if(headerId) {
			$("#toc li").removeClass('current');
			$(this).addClass("current");
			$(".tab_content").hide();
			var selected_tab = headerId.substring(0,4) // means tab1header to tab1
			$("#"+selected_tab).show();
		}
	});
	
	$(".txtbox").click(function(){
		this.focus();
		this.select();
	});

	$(".txtbox").keyup(function(event){
		if(event.which == 13){
			saveUserprofile();	
		}
	});	
	
};

/* Start: Clear all priside textboxes values.
   Function name: clearAllFields()
   Parameters:
       N/A
*/
var clearAllFields = function(){
	jQuery(".txtbox").val("");
}

/* Start: Get textbox value.
   Function name: getTextValue()
   Parameters:
       textbox_name - The name of the textbox object where the value is to be read.
*/
var getTextValue = function(textbox_name){
	return jQuery("#"+textbox_name).val().trim();
}

/* Start: Enable specific text box.
   Function name: enableTextBox()
   Parameters:
       textbox_name - The textbox object name that will be enabled.
*/
var enableTextBox = function(textbox_name){
    jQuery('#'+textbox_name).removeClass('txtbox-selected').removeClass('txtbox-disabled').addClass('txtbox').removeAttr("disabled");
    
};

/* Start: Disable specific text box.
   Function name: disableTextBox()
   Parameters:
       textbox_name - The textbox object name that will be disabled.
*/
var disableTextBox = function(textbox_name){
    jQuery('#'+textbox_name).removeClass('txtbox').removeClass('txtbox-selected').addClass('txtbox-disabled').attr("disabled", "disabled");
};

/* Start: Select specific text box.
   Function name: selectTextBox()
   Parameters:
       textbox_name - The textbox object name that will be selected and focused.
*/
var selectTextBox = function(textbox_name){
    jQuery('#'+textbox_name).removeClass('txtbox-disabled').removeAttr("disabled").addClass('txtbox').focus();
};

/* Start: Highlight search dropbox content.
   Function name: highlightSearchboxContent()
   Parameters:
       object_id - The textbox object name that will be selected and focused.
*/
var highlightSearchboxContent = function(object_id){
    jQuery("#"+object_id).select().focus();
};

/* Start: Close the listbox.
   Function name: closeListBox()
   Parameters:
       N/A
*/
var closeListBox = function(parentBoxId){
    jQuery('#'+parentBoxId).hide();    
};

/* Start: Show the list of the search result.
   Function name: showSearchResultList()
   Parameters:
       textId - The name of the textbox object where the value will be read.
       sampleText - The default text to display when ESCAPE key is invoked.
       listBoxId - List box object name that will contain the list items (combo box options)
       value_container - Object name of the additional filter to use.
       e - JS event.
   Note:
       This function will take care of every keypress from the user. If the user presses ESCAPE key,
       it will display the default or sample text.
*/
var showSearchResultList = function(textId, sampleText, listBoxId, value_container, e){
    if(e.keyCode == 27){
        jQuery("#" + textId).removeClass('txt12').addClass('txt12Default').val(sampleText).select().focus();
	hideListBox(listBoxId);
    }else{
        jQuery("#" + textId).removeClass('txt16Sample').addClass('txt16');
	showListBox(textId, listBoxId, value_container);
    }
};

/* Start: Show the location items into the customized combo box.
   Function name: showListBox()
   Parameters:
       textId - The name of the textbox object where the value will be read.
       listBoxId - List box object name that will contain the list items (combo box options).
       value_container - hidden field object that contains additional filter to use in the population of the list objects.
   Note:
       This is not a real list box but only a search list box like component created for priside project.
       This is used for auto-filling the result of the searching in the list box.
*/
var showListBox = function (textId, listBoxId, value_container) {
    var selectObj = jQuery("#"+listBoxId+" ul");
    selectObj.empty();
    hideListBox(listBoxId);

    var searchKey = jQuery("#" + textId).val();
    searchKey = (searchKey!=null && searchKey.length > 0) ? searchKey : "";
    
    var added_filter = jQuery("#"+value_container).val();
    added_filter = (added_filter != null && added_filter.length > 0 && added_filter != "-1") ? added_filter : "";
    
    var slocations = new Array();
    for(var i = 0; i < locations.length; i++){
	var loc = locations[i];

	var city = loc.city;
	var state = loc.state;
	
	city = (city != null && city.length > 0) ? city : "";
	
	var complete_location = city;
	
	if(added_filter.length > 0)
	{	
		if(state == added_filter)
		{
			if(complete_location.toLowerCase().indexOf(searchKey.toLowerCase()) >= 0) slocations.push(loc);
		}
	}
	else
	{
		if(complete_location.toLowerCase().indexOf(searchKey.toLowerCase()) >= 0) slocations.push(loc);
	}
    }
    if(slocations.length > 0)
    {
	for(var a = 0; a < slocations.length; a++){
	    var location = slocations[a];
	    selectObj.append("<li class='pre-padding-15 txt12Purple' onclick=\"setSelectedItemValueToSearchbox(this, '" + textId + "', '" + location.city + "')\";>"+location.city+"</li>");
	}
	jQuery("#"+listBoxId+" ul").show();
	jQuery("#"+listBoxId).show();
    }
};

/* Start: Show the combobox items into the customized combo box.
   Function name: showComboboxItems()
   Parameters:
       listBoxId - List box object name that will contain the list items (combo box options)
       spanId - Name of the span object that will display the selected combo box item.
       value_container - name of the hidden field that will contain the value of the selected item.
   Note:
       This is not a real combo box but only a combo box like component created for priside project.
*/
var showComboboxItems = function (listBoxId, spanId, value_container) {
    var selectObj = jQuery("#"+listBoxId+" ul");
    selectObj.empty();
    hideListBox(listBoxId);
    if(location_states.length > 0)
    {
	for(var a = 0; a < location_states.length; a++){
	    var location = location_states[a];
	    selectObj.append("<li class='pre-padding-15 txt12Purple' onclick=\"setSelectedItemValueToCombobox(this, '" + spanId + "', '" + location.city + "', '" + value_container + "', '" + location.state + "')\";>"+location.city+"</li>");
	}
	jQuery("#"+listBoxId+" ul").show();
	jQuery("#"+listBoxId).show();
    }
};

/* Start: Hide list box
   Function name: hideListBox()
   Parameter: listBoxId [Name of the listbox object to hide]
*/
var hideListBox = function(listBoxId){
    jQuery("#"+listBoxId).hide();
};

/* Start: Set selected item value to search box
   Function name: setSelectedItemValueToSearchbox()
   Parameters:
       obj - list item object
       textId - Name of the text object where the selected item is to be displayed.
       itemValue - The value to be displayed in the textbox
*/
var setSelectedItemValueToSearchbox = function(obj, textId, itemValue){
    jQuery("#"+textId).val(itemValue);
    jQuery("#"+obj.parentNode.parentNode.parentNode.id).hide();
};

/* Start: Set selected item value to combo box
   Function name: setSelectedItemValueToCombobox()
   Parameters:
       obj - list item object
       spanId - Name of the span object where the selected item is to be displayed.
       selected_item_text - The value to be displayed in the span object.
       value_container - The object name of the hidden field that will contain the selected item value.
       selected_item_value - The selected item value.
*/
var setSelectedItemValueToCombobox = function(obj, spanId, selected_item_text, value_container, selected_item_value){
    jQuery("#"+value_container).val(selected_item_value);
    jQuery("#"+spanId).html(selected_item_text);
    jQuery("#"+obj.parentNode.parentNode.parentNode.id).hide();
};

/* START: Show subgroup or display items by categories for search dropbox
   Function name: showSubgroup()
   Parameters:
       obj - list item object
   Note: This is just to play with the PHP saving, not used in Priside project.
*/
var showSubgroup = function(){
	alert('Show subgroup');
};








/* START: Save User Profile (Not needed in Priside)
   Note: This is just to play with the PHP saving, not used in Priside project.
*/
var saveUserprofile = function(){
	var fname = getTextValue('txtfname');
	var lname = getTextValue('txtlname');
	var mname = getTextValue('txtmname');
	var email1 = getTextValue('txtemail1');
	var contact1 = getTextValue('txtcontactno1');
	
	if(fname.length > 0 && lname.length > 0 && email1.length > 0 && contact1.length > 0){
		$.ajax({
			type: "POST",
			url: "saveUserprofile.php",
			data: "fname=" + fname + "&mname=" + mname + "&lname=" + lname + "&email1="+ email1 + "&contact1="+ contact1 + "&process_date=" +  new Date().getTime(),
			success: function(){
				alert("Entry successfully saved.");
				clearAllFields();
				refreshUserTable();
			}
		});
	}else{
		alert("Please complete all the required fields.");
	}
};
/* END: Save User Profile (Not needed in Priside) */
