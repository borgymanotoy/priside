<?php
	require_once( $_SERVER['DOCUMENT_ROOT']."/translations/common.php" );

	$translation_array_sv = array(
						"mail_title" => "Mail",
						"mail_help_info" => "Här kan du skapa maillistor som du sedan importerar i Mailchimp för mailutskick.\n\nMarkera de kundtyper, tjänster och län du vill använda i maillistan. Klicka sedan på ”Skapa maillista”.",
						"mail_create_list" => "Skapa maillista",
						"mail_link_to_mailchimp" => "Länk till Mailchimp",

						"mail_suppliers" => "Leverantörer",
						"mail_consumers" => "Konsumenter",
						"mail_advertisers" => "Annonsörer",
						"mail_industrties" => "Branscher",

						"mail_fltr_services" => "Tjänster",
						"mail_fltr_countries_municipalities" => "Län/kommuner",
					);
	$translation_array_en = array(
						"mail_title" => "Mail",
						"mail_help_info" => "Here you can create email lists you can then import into MailChimp for mailings.\n\nSelect the types of customers, services and counties to use in email list. Then click on ”Create Mailing List”.",
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