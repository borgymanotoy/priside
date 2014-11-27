<!-- START: Translations -->
<?php
$translation_array_sv = array(
					"footer_how_it_works" => "Så fungerar Prisidé",
					"footer_make_an_inquiry" => "Skapa en förfrågan",
					"footer_connect_your_company" => "Anslut ditt företag",
					"footer_affiliated_companies" => "Anslutna företag",
					"footer_vacancies" => "Lediga jobb",
					"footer_contact_us" => "Kontakta oss",
					"footer_press_news" => "Pressnyheter",
					"footer_service" => "KUNDTJÄNST",
					"footer_mail_address" => "POSTADRESS",
					"footer_recommend_priside" => "REKOMENDERA PRISIDÈ",
					"footer_copyright" => "Goda affärer på nätet AB",
				);
$translation_array_en = array(
					"footer_how_it_works" => "How Prisidé Works",
					"footer_make_an_inquiry" => "Make an inquiry",
					"footer_connect_your_company" => "Connect your company",
					"footer_affiliated_companies" => "Affiliated companies",
					"footer_vacancies" => "Vacancies",
					"footer_contact_us" => "Contact Us",
					"footer_press_news" => "Press News",
					"footer_service" => "SERVICE",
					"footer_mail_address" => "MAILING ADDRESS",
					"footer_recommend_priside" => "RECOMMEND PRISIDÈ",
					"footer_copyright" => "Good business on the Web Ltd",
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