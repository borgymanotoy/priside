<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="en" lang="en">
<head>
   <meta http-equiv="Content-Type" content="text/html; charset=ISO-8859-1"/>
   <title>index</title>  
   <link href="css/screen.css" type="text/css" rel="stylesheet" media="screen,projection" />
   <script type="text/javascript" src="js/jquery-latest.js"></script>
   <script type="text/javascript" src="js/events.js"></script>
   <script type="text/javascript" src="js/dummy_data.js"></script>
   <script type="text/javascript" src="js/priside-script.js"></script>

   
	<style type="text/css">
	
	/*
		http://www.alistapart.com/articles/slidingdoors/
		http://www.jankoatwarpspeed.com/post/2008/04/30/make-fancy-buttons-using-css-sliding-doors-technique.aspx
	*/
	


	
	
	/*= css for this page */
	#kontorsfytt{
		float:left; 
		height:100%; 
		width:320px;
	}
	
	#kontorsfytt h2{
		text-align:center;
	}
	#kontorsfytt  img {
		margin: 0 auto; 
		display: block;
	}
	
	#search-service {
		float:left; 
		width:630px; 
		height:200px;
	}
	
	#search-service h1{
		color:#E66313;
		margin-top:1.2em;
	}
	
	
	 					
	.date { width:100px; }
	.inquiry { width:280px; }
	.municipality { width:100px;}
	
	

	</style>
</head>
<body>
		
		<? include( "header.php" ); ?>
		
		<!-- body section -->
		<div id="main">
			<div id="content" class="wrapper">
				
				<div id="main-content" class="clearfix">		
					
					<div id="main-contents">
					
						<div class="clearfix">
							<div id="kontorsfytt">
								<h2 class="h2-violet">Kontorsfytt?</h2>
								<img src="img/boxes.jpg" />
								<h3>Låt proffs från våra kvalitetssäkrade företag ta hand om jobbet!</h3>
							</div>
							<div id="search-service">
								<ul class="home-page-num">
									<li><a id="active-pgnum" href="#">1</a></li>
									<li><a href="#">2</a></li>
									<li><a href="#">3</a></li>
								</ul>
								<h1>Vad behöver du hjälp med?</h1>
						
									<div style="float: left;">
										<!-- Start: Search dropbox 1 -->
										<span class="dropboxLabel" valign="middle"><span class="txt12Bold">Vad</span>&nbsp;<span class="txt12Bold">|</span>&nbsp;<span class="txt12">bransch, tjänst</span></span>
										<div class="dropbox" style="z-index: 4;">
											<input type="text" id="txt_vad_1" class="search-input-box txt12Default" maxlength="30" value="Ex. Flyttstädning" onkeyup="showSearchResultList(this.id, 'Ex. Flyttstädning', 'lst_vad_1', 'selected_item_value', event);" onclick="highlightSearchboxContent(this.id);" />
											<span class="site-color arrow-down-button" onclick="showSubgroup('lst_cat_vad_1', 'list_categories');"><span class="txt14Bold">|</span>&nbsp;<span class="txt11">välj</span>&nbsp;<span class="txt12">▼</span></span>
											<div id="lst_vad_1" class="listBox" style="display: none;">
												<div id="divClose" align="right" onclick="closeListBox('lst_vad_1');">
													<img src="img/closeImage.png" alt="Close" />
												</div>
												<div class="bottom-spacer">
													<ul class="listItems" type="none"></ul>
												</div>
											</div>
											<div id="lst_cat_vad_1" class="listBox" style="display: none;">
												<div id="divClose" align="right" onclick="closeListBox('lst_cat_vad_1');">
													<img src="img/closeImage.png" alt="Close" />
												</div>
												<div class="bottom-spacer">
													<ul id="list_categories" class="listItems" type="none"></ul>
												</div>
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
									<p style="float:right;"><a class="btn-orange" href="#"><span>Gå vidare ►</span></a></p>
							</div>
						</div>
						
						
						
						<div class="box-pane">
							<h2>Så fungerar Prisidé</h2>
							<div class="box-pane-content clearfix">
								<div>
									<ul class="checked">
										<li><a href="#">Enkelt</a></li>
										<li><a href="#">Tryggt</a></li>
										<li><a href="#">Gratis</a></li>
									</ul>
									<ul>
										<li>► Läs mer här</li>
									</ul>
								</div>
								<img src="img/man_pic.png" />
							</div>
						</div>

						<div class="box-pane">
							<h2>Anslut ditt företag</h2>
							<div class="box-pane-content" style="height:121px;">
								<p>Vi hjälper ditt företag att växa!<img style="float:right;" src="img/line_graph.png" /></p>
								<ul>
									<li>► Läs om fördelarna med Prisidé här</li>
								</ul>
							</div>
						</div>

						<div class="box-pane">
							<h2>Aktuella uppdrag</h2>
							<div class="box-pane-content">
								<ul>
									<li><a href="#">Fönsterputs | Ljungby</a></li>
									<li><a href="#">Rekond | Värnamo</a></li>
									<li><a href="#">Mur & stensättning | Sjöbo</a></li>
								</ul>
								<ul>
									<li>► Sök fer uppdrag här</li>
								</ul>
							</div>
						</div>

					</div>	
					
				</div>
				
				
				
				<div id="main-bottom-curve">&nbsp;</div>
			</div>
		</div>
		
		<? include( "footer.php" ); ?>
		
</body>
</html>