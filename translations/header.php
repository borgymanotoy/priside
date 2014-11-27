<!-- START: Translations -->
<?php
$translation_array_sv = array(
					"header_make_query" => "Skapa en förfrågan",
					"header_search_companies" => "Sök företag",
					"header_connect_your_company" => "Anslut ditt företag",
					"header_how_priside" => "Så fungerar Prisidé",
					"header_contact_us" => "Kontakta oss",
					
					"header_customer_service" => "Kundtjänst",
					"header_faqs" => "Vanliga frågor",
					"header_vacancies" => "Lediga jobb",
					"header_press_news" => "Pressnyheter",

					"header_login" => "Logga in",
					"header_logout" => "Logga out",
				);
$translation_array_en = array(
					"header_make_query" => "Make an inquiry",
					"header_search_companies" => "Search companies",
					"header_connect_your_company" => "Connect your company",
					"header_how_priside" => "How Prisidé",
					"header_contact_us" => "Contact Us",
					
					"header_customer_service" => "Customer Service",
					"header_faqs" => "FAQs",
					"header_vacancies" => "Vacancies",
					"header_press_news" => "Press News",

					"header_login" => "Log in",
					"header_logout" => "Log out",
				);

if( ! is_array( $trans ) ) $trans = array();

if( "en" == $lang ){
	$trans = array_merge( $trans, $translation_array_en );
}
else{
	$trans = array_merge( $trans, $translation_array_sv );
}

?>
<!-- END: Translations -->