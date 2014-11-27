<?php
	require_once( $_SERVER['DOCUMENT_ROOT']."/translations/common.php" );

	$translation_array_sv = array(
						"mail_title" => "Mail",
						"mail_help_info" => "H�r kan du skapa maillistor som du sedan importerar i Mailchimp f�r mailutskick.\n\nMarkera de kundtyper, tj�nster och l�n du vill anv�nda i maillistan. Klicka sedan p� �Skapa maillista�.",
						"mail_create_list" => "Skapa maillista",
						"mail_link_to_mailchimp" => "L�nk till Mailchimp",

						"mail_suppliers" => "Leverant�rer",
						"mail_consumers" => "Konsumenter",
						"mail_advertisers" => "Annons�rer",
						"mail_industrties" => "Branscher",

						"mail_fltr_services" => "Tj�nster",
						"mail_fltr_countries_municipalities" => "L�n/kommuner",
					);
	$translation_array_en = array(
						"mail_title" => "Mail",
						"mail_help_info" => "Here you can create email lists you can then import into MailChimp for mailings.\n\nSelect the types of customers, services and counties to use in email list. Then click on �Create Mailing List�.",
						"mail_create_list" => "Create mailing list",
						"mail_link_to_mailchimp" => "Link to MailChimp",

						"mail_suppliers" => "Suppliers",
						"mail_consumers" => "Consumers",
						"mail_advertisers" => "Advertisers",
						"mail_industrties" => "Industries",

						"mail_fltr_services" => "Services",
						"mail_fltr_countries_municipalities" => "Counties / municipalities",
					);

if( ! is_array( $trans ) ) $trans = array();

if( "en" == $lang ){
	$trans = array_merge( $trans, $translation_array_en );
}
else{
	$trans = array_merge( $trans, $translation_array_sv );
}

?>