<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="en" lang="en">
<head>
	<meta http-equiv="Content-Type" content="text/html; charset=utf-8"/>
	<title>index</title>

	<link href="css/screen.css" type="text/css" rel="stylesheet" media="screen,projection" />

	<link href="css/main.css" type="text/css" rel="stylesheet" media="screen" />

	<script type="text/javascript" src="js/jquery-latest.js"></script>
	<script type="text/javascript" src="js/events.js"></script>
	<script type="text/javascript" src="js/dummy_data.js"></script>
	<script type="text/javascript" src="js/priside-script.js"></script>

	<script>
	$(document).ready(function(){
		initComponents();
		refreshUserTable("or");
	});
	
	</script>
</head>
<body>
	<div id="branding">
            <div class="wrapper">
                <!-- Logo start -->
                  <h1 id="logo"><a href="./">Priside</a></h1>
                <!-- Logo end -->
            </div>
        </div>

	<div id="main">
		<div id="content" class="wrapper">
			<ol id="toc">
				<li class="current" id="tab1header"><a href="javascript:void(0);"><span>Forms</span></a></li>
				<li id="tab2header"><a href="javascript:void(0);"><span>Data</span></a></li>
				<li id="tab3header"><a href="javascript:void(0);"><span>Searching</span></a></li>
			</ol>
			<div id="main-content" class="clearfix">
				<div id="tab1" class="tab_content">
					<div class="container" align="left" valign="middle">
					<p>
						<span class="pre-margin-10 common-height txt12Bold">Nam*</span><br/>
						<input type="text" id="txtSample" class="txtbox txt12" maxlength="30" value="" onkeydown="if(event.keyCode == 13){ alert('Search for: ' + this.value); }" />
						&nbsp;&nbsp;&nbsp;
						<span id="spnButton" class="purple_button button_text" onclick="alert('Search for: ' + jQuery('#txtSample').val());">Submit</span>
						&nbsp;&nbsp;&nbsp;
						<span id="spnButton" class="purple_button button_text" onclick="enableTextBox('txtSample');">Enable</span>
						&nbsp;&nbsp;&nbsp;
						<span id="spnButton" class="purple_button button_text" onclick="disableTextBox('txtSample');">Disable</span>
						&nbsp;&nbsp;&nbsp;
						<span id="spnButton" class="purple_button button_text" onclick="selectTextBox('txtSample');">Selected</span>
					</p>
					<br/>
					<p>
						<!-- Start: Combo-box 1 -->
						<span class="txt12Bold comboboxLabel" valign="middle">State / Region:</span>
						<input type="hidden" id="selected_item_value" value="-1" />
						<div class="combobox-container" style="z-index: 10;">
							<span id="spnSelectedItem" class="cmbText" style="float: left; width: 175px;">Select location</span>
							<span class="site-color arrow-down-button" style="width: 25px; height: 18px;" onclick="showComboboxItems('div_cmb_box_1', 'spnSelectedItem', 'selected_item_value');"><span class="txt14Bold">|</span>&nbsp;<span class="txt12">▼</span></span>
							<br/>
							<div id="div_cmb_box_1" class="comboBox" style="display: none;">
								<div id="divClose" align="right" onclick="closeListBox('div_cmb_box_1');">
									<img src="img/closeImage.png" alt="Close" />
								</div>
								<div class="bottom-spacer">
									<ul class="listItems" type="none"></ul>
								</div>
							</div>
						</div>
						<!-- End: Combo-box 1 -->
					</p>
					<br/>
					<p>
						<div style="float: left;">
							<!-- Start: Search dropbox 1 -->
							<span class="dropboxLabel" valign="middle"><span class="txt12Bold">Vad</span>&nbsp;<span class="txt12Bold">|</span>&nbsp;<span class="txt12">bransch, tjänst</span></span>
							<div class="dropbox" style="z-index: 4;">
								<input type="text" id="txt_vad_1" class="search-input-box txt12Default" maxlength="30" value="Ex. Flyttstädning" onkeyup="showSearchResultList(this.id, 'Ex. Flyttstädning', 'lst_vad_1', 'selected_item_value', event);" onclick="highlightSearchboxContent(this.id);" />
								<span class="site-color arrow-down-button" onclick="showSubgroup('');"><span class="txt14Bold">|</span>&nbsp;<span class="txt11">välj</span>&nbsp;<span class="txt12">▼</span></span>
								<div id="lst_vad_1" class="listBox" style="display: none;">
									<div id="divClose" align="right" onclick="closeListBox('lst_vad_1');">
										<img src="img/closeImage.png" alt="Close" />
									</div>
									<div class="bottom-spacer">
										<ul class="listItems" type="none"></ul>
									</div>
								</div>
								<div id="lst_sub_vad_1" class="listBox" style="display: none;">
									<ul class="listItems" type="none"></ul>
								</div>
							</div>
							<!-- End: Search dropbox 1 -->
						</div>
						<div style="margin-left: 350px;">
							<!-- Start: Search dropbox 2 -->
							<span class="dropboxLabel" valign="middle"><span class="txt12Bold">Vad</span>&nbsp;<span class="txt12Bold">|</span>&nbsp;<span class="txt12">ort, kommun, postnummer</span></span>
							<div class="dropbox" style="z-index: 5;">
								<input type="text" id="txt_var_1" class="search-input-box txt12Default" maxlength="30" value="Ex. Landskrona" onkeyup="showSearchResultList(this.id, 'Ex. Landskrona', 'lst_var_1', 'selected_item_value', event);" onclick="highlightSearchboxContent(this.id);" />
								<span class="site-color arrow-down-button" onclick="showSubgroup('');"><span class="txt14Bold">|</span>&nbsp;<span class="txt11">välj</span>&nbsp;<span class="txt12">▼</span></span>
								<div id="lst_var_1" class="listBox" style="display: none;">
									<div id="divClose" align="right" onclick="closeListBox('lst_var_1');">
										<img src="img/closeImage.png" alt="Close" />
									</div>
									<div class="bottom-spacer">
										<ul class="listItems" type="none"></ul>
									</div>
								</div>
							</div>
							<!-- End: Search dropbox 2 -->
						</div>
					</p>
					</div>
				</div>
				<div id="tab2" class="tab_content">
					<div id="div_entries" class="left_panel" style="float: left; width: 350px;">
						<p align="left">
							<span class="pre-margin-10 txt12Bold">First name : *</span><br/>
							<input type="text" id="txtfname" class="txtbox txt12" size="25" maxlength="30" value="" />
						</p>
						<br/>
						<p align="left">
							<span class="pre-margin-10 txt12Bold">Last name : *</span><br/>
							<input type="text" id="txtlname" class="txtbox txt12" size="25" maxlength="30" value="" />
						</p>
						<br/>
						<p align="left">
							<span class="pre-margin-10 txt12Bold">Middle name:</span><br/>
							<input type="text" id="txtmname" class="txtbox txt12" size="25" maxlength="30" value="" />
						</p>
						<br/>
						<p align="left">
							<span class="pre-margin-10 txt12Bold">Email Address : *</span><br/>
							<input type="text" id="txtemail1" class="txtbox txt12" size="25" maxlength="30" value="" />
						</p>
						<br/>
						<p align="left">
							<span class="pre-margin-10 txt12Bold">Contact Number : *</span><br/>
							<input type="text" id="txtcontactno1" class="txtbox txt12" size="25" maxlength="30" value="" />
						</p>
						<br/>
						<p align="left">
							<span class="purple_button button_text" onclick="saveUserprofile();">Save</span>
							&nbsp;&nbsp;&nbsp;
							<span class="purple_button button_text" onclick="clearAllFields();">Clear</span>
						</p>
					</div>
					<div id="div_entries" class="right_panel" style="margin-left: 375px;">
						<table id="tbl_user_profiles" cellpadding="5px" cellspacing="0px" border="1" width="100%">
							<tr class="header-row">
								<th class="txt14White">NAME</th>
								<th class="txt14White">EMAIL ADDRESS</th>
								<th class="txt14White">CONTACT NUMBER</th>
							</tr>
							<tr>
								<td>Dummy Name 1</td>
								<td>Dummy Email 1</td>
								<td>Dummy Contact 1</td>
							</tr>
						</table>
					</div>
				</div>
				<div id="tab3" class="tab_content">	
					<p align="left" class="txt18">
						<span class="pre-margin-10 txt12Bold">Search key:</span><br/>
						<input type="text" id="txtSample" class="txtbox txt14" size="25" maxlength="30" value="" />&nbsp;&nbsp;&nbsp;
						<span class="purple_button button_text" onclick="alert('Text: ' + jQuery('#txtSample').val());">Submit</span>
					</p>
					<br/>
					<p align="left">
						<div style="float: left; margin-left: 10px; width: 250px;">
							<span class="txt16Bold" style="margin-left: 5px;">Vad<span class="txt20Bold"> | </span><span class="txt14">bransch, tjänst</span><br/>
							<input type="text" id="txtVad" class="txtbox txt14" size="25" maxlength="30" value="Ex. Flyttstädning" />
						</div>
						<div style="display: block; width: 250px; margin-left: 450px;">
							<span class="txt16Bold" style="margin-left: 5px;">Var<span class="txt20Bold"> | </span><span class="txt14">ort, kommun, postnummer</span><br/>
							<input type="text" id="txtVar" class="txtbox txt14" size="25" maxlength="30" value="Ex. Landskrona" />
						</div>
					</p>
				</div>
			</div>
			<div id="main-bottom-curve">&nbsp;</div>
		</div>
	</div>
	
	<div id="footer">
		<div id="content-footer" class="wrapper">
			<div id="main-content-footer" class="clearfix">
				<ul id="company-info">
					<li>
						<h3>PRISIDÉ.SE</h3>
						<ul>
							<li><a href="#">Så fungerar Prisidé</a></li>
							<li><a href="#">Skapa en förfrågan</a></li>
							<li><a href="#">Anslut ditt företag</a></li>
							<li><a href="#">Anslutna företag</a></li>
							<li><a href="#">Lediga jobb</a></li>
							<li><a href="#">Kontakta oss</a></li>
							<li><a href="#">Pressnyheter</a></li>
							<li><a href="#"></a></li>
							<li><a href="#"></a></li>
						</ul>
					</li>
					<li>
						<h3>KUNDTJÄNST</h3>
						<ul>
							<li><a href="#">040 - 67 14 422</a></li>
							<li><a href="#">Vardagar 9-17</a></li>
							<li><a href="#">info@priside.se</a></li>
						</ul>
					</li>
					<li>
						<h3>POSTADRESS</h3>
						<ul>
							<li><a href="#">Norra Vallgatan 98</a></li>
							<li><a href="#">211 22 Malmö</a></li>
						</ul>
					</li>
					<li>
						<h3>REKOMENDERA PRISIDÈ</h3>
						<ul>
							<li><a href="#"></a></li>
							<li><a href="#"></a></li>
							<li><a href="#"></a></li>
							<li><a href="#"></a></li>
						</ul>
					</li>
				</ul>
				<p>Copyright © 2011 Goda affärer på nätet AB  |  Cookies</p>
			</div>
			
			<div id="main-bottom-footer">&nbsp;</div>
		</div>
	</div>
</body>
</html>
