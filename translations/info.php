<!-- START: Translations -->
<?php
$translation_array_sv = array(
					"lang_how_priside" => "Så fungerar Prisidé!",
					"lang_simple" => "Enkelt",
					"lang_safe" => "Tryggt",
					"lang_free" => "Gratis",
					"lang_read_more" => "Läs mer här",
					"lang_connect_company" => "Anslut ditt företag",
					"lang_connect_company_text" => "Vi hjälper ditt företag att växa!",
					"lang_learn_benefits" => "Läs om fördelarna med Prisidé här",
					"lang_new_assign" => "Aktuella uppdrag",
					"lang_new_window" => "Fönsterputs",
					"lang_ljungby" => "Ljungby",
					"lang_search_task" => "Sök fler uppdrag här",
					"lang_search_rekond" => "Rekond",
					"lang_search_varnamo" => "Värnamo",
					"lang_wall" => "mur",
					"lang_paving" => "stensättning",
					"lang_sjobo" => "Sjöbo",
				);
$translation_array_en = array(
					"lang_how_priside" => "How Prisidé?",
					"lang_office_relocation" => "Office Relocation?",
					"lang_simple" => "Simpe",
					"lang_safe" => "Safe",
					"lang_free" => "Free",
					"lang_read_more" => "Read more here",
					"lang_connect_company" => "Connect your company",
					"lang_connect_company_text" => "We help your business grow!",
					"lang_learn_benefits" => "Learn about the benefits of Prisidé here",
					"lang_new_assign" => "New Assignments.",
					"lang_new_window" => "Window",
					"lang_ljungby" => "Ljungby",
					"lang_search_task" => "Search this task tert",
					"lang_search_rekond" => "Rekond",
					"lang_search_varnamo" => "Värnamo",
					"lang_wall" => "wall",
					"lang_paving" => "paving",
					"lang_sjobo" => "Sjöbo",
				);
				
				
if( ! is_array( $trans ) )
	$trans = array();

if( "en" == $lang ){
	$trans = array_merge( $trans, $translation_array_en );
}
else{
	$trans = array_merge( $trans, $translation_array_sv );
}
 return;
