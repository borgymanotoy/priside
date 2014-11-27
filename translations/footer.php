<!-- START: Translations -->
<?php
$translation_array_sv = array(
					"footer_how_it_works" => "S� fungerar Prisid�",
					"footer_make_an_inquiry" => "Skapa en f�rfr�gan",
					"footer_connect_your_company" => "Anslut ditt f�retag",
					"footer_affiliated_companies" => "Anslutna f�retag",
					"footer_vacancies" => "Lediga jobb",
					"footer_contact_us" => "Kontakta oss",
					"footer_press_news" => "Pressnyheter",
					"footer_service" => "KUNDTJ�NST",
					"footer_mail_address" => "POSTADRESS",
					"footer_recommend_priside" => "REKOMENDERA PRISID�",
					"footer_copyright" => "Goda aff�rer p� n�tet AB",
				);
$translation_array_en = array(
					"footer_how_it_works" => "How Prisid� Works",
					"footer_make_an_inquiry" => "Make an inquiry",
					"footer_connect_your_company" => "Connect your company",
					"footer_affiliated_companies" => "Affiliated companies",
					"footer_vacancies" => "Vacancies",
					"footer_contact_us" => "Contact Us",
					"footer_press_news" => "Press News",
					"footer_service" => "SERVICE",
					"footer_mail_address" => "MAILING ADDRESS",
					"footer_recommend_priside" => "RECOMMEND PRISID�",
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