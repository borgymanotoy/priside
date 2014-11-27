<!-- START: Translations -->
<?php
$translation_array_sv = array(
					"lang_how_priside" => "S� fungerar Prisid�!",
					"lang_simple" => "Enkelt",
					"lang_safe" => "Tryggt",
					"lang_free" => "Gratis",
					"lang_read_more" => "L�s mer h�r",
					"lang_connect_company" => "Anslut ditt f�retag",
					"lang_connect_company_text" => "Vi hj�lper ditt f�retag att v�xa!",
					"lang_learn_benefits" => "L�s om f�rdelarna med Prisid� h�r",
					"lang_new_assign" => "Aktuella uppdrag",
					"lang_new_window" => "F�nsterputs",
					"lang_ljungby" => "Ljungby",
					"lang_search_task" => "S�k fler uppdrag h�r",
					"lang_search_rekond" => "Rekond",
					"lang_search_varnamo" => "V�rnamo",
					"lang_wall" => "mur",
					"lang_paving" => "stens�ttning",
					"lang_sjobo" => "Sj�bo",
				);
$translation_array_en = array(
					"lang_how_priside" => "How Prisid�?",
					"lang_office_relocation" => "Office Relocation?",
					"lang_simple" => "Simpe",
					"lang_safe" => "Safe",
					"lang_free" => "Free",
					"lang_read_more" => "Read more here",
					"lang_connect_company" => "Connect your company",
					"lang_connect_company_text" => "We help your business grow!",
					"lang_learn_benefits" => "Learn about the benefits of Prisid� here",
					"lang_new_assign" => "New Assignments.",
					"lang_new_window" => "Window",
					"lang_ljungby" => "Ljungby",
					"lang_search_task" => "Search this task tert",
					"lang_search_rekond" => "Rekond",
					"lang_search_varnamo" => "V�rnamo",
					"lang_wall" => "wall",
					"lang_paving" => "paving",
					"lang_sjobo" => "Sj�bo",
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
