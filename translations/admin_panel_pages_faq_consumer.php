<?php
	require_once( $_SERVER['DOCUMENT_ROOT']."/translations/admin_panel_pages_faq.php" );
	require_once( $_SERVER['DOCUMENT_ROOT']."/translations/pages_common.php" );

	$translation_array_sv = array(
						"faq_consumer_click_to_edit" => "Klicka på en fråga för att redigera den.",
						"faq_consumer_account" => "Vad är ett konto",
						"faq_consumer_create_account" => "Hur skapar jag ett konto",
						"faq_consumer_who_create_account" => "Vem kan skapa ett konto",
						"faq_consumer_industry_questions" => "Finns det förfrågningar inom min bransch",
						"faq_consumer_access_requests" => "Hur får jag tillgång till förfrågningar",
						"faq_consumer_rejected_application" => "Kan jag göra något om jag anser att jag har fått ett felaktigt avslag på min ansökan",
						"faq_consumer_login_problems" => "Vad gör jag om jag har inloggningsproblem",
						"faq_consumer_how_guard" => "Hur fungerar bevakningen",

						"faq_consumer_sample_text" => "Vi på kundtjänst fnns alltid här för dig för att hjälpa dig med frågor och funderingar!\nTveka inte att kontakta oss!",

						"faq_consumer_ans_1" => "Via ditt konto på Priside.se hanterar du din bevakningsprofil och mottagna och svarade förfrågningar. Du kan även söka fritt bland alla aktuella förfrågningar. För att få ett konto betalar du en fast avgift per månad.",
						"faq_consumer_ans_2" => "som leverantör klickar du på ”Företag? Ansök om konto” där du fyller i din företagsinformation och trycker sedan ”skicka”. Sedan kommer leverantören bli kontaktad av Priside.se.",
						"faq_consumer_ans_3" => "Endast företag som har F-skattesedel, är registrerat för moms och arbetsgivaravgift samt är skuldfritt hos kronofogden.",
						"faq_consumer_ans_4" => "Idag stödjer Priside.se följande områden: bygg & renovering, mark & trädgård, städning, fytt, fest & event, motor, catering, data/IT, foto/bild, utbildning, marknadsföring, ekonomi, juridik samt arkitekt & inredning. Vi arbetar kontinuerligt med att utöka tjänsten för fera branscher. Om din bransch inte fnns med idag kom gärna med förslag till oss.",
						"faq_consumer_ans_5" => "Du kan alltid söka förfrågningar direkt via förstasidan för leverantörer. För att svara på en förfrågan behöver du ha ett konto. Inget konto? Klicka HÄR",
						"faq_consumer_ans_6" => "Ja. Om du anser att grunderna för ditt avslag har varit felaktiga kan du kontakta kundservice för att ompröva din ansökan genom att komplettera din ansökan med ytterligare uppgifter och handlingar.",
						"faq_consumer_ans_7" => "Kontakta omedelbart vår kundservice per e-post eller telefon för att åtgärda problemet.",
						"faq_consumer_ans_8" => "När en förfrågan skapas matchas den mot din bevakningsprofil. Du tar endast emot de förfrågningar som matchar din bevaknings profil. Därefter är det bara att börja svara på jobbförfrågningarna.",

						"faq_consumer_save_success_msg" => "Vanliga frågor : Konsument information framgångsrikt sparas.",
						"faq_consumer_update_success_msg" => "Vanliga frågor : Konsument information framgångsrikt uppdateras.",
						"faq_consumer_remove_success_msg" => "Vanliga frågor : Konsument information framgångsrikt avlägsnades.",
						"faq_consumer_error_entry_validation" => "Vänligen fyll upp posterna på rätt sätt.",

						"faq_consumer_confirm_delete_title" => "Avlägsna Vanliga objektet",
						"faq_consumer_confirm_delete" => "Är du säker på att du vill ta bort den återkommande FAQ?",

						"faq_consumer_no_data" => "Inga konsumentprodukter frågor som finns.",

						// php errors / etc.
						"pfaq_consumer_ErrMsg_ConsumerId" => "Consumer Id Question cannot be blank.",
						"pfaq_consumer_ErrMsg_ConsumerQuestion" => "Consumer Question cannot be blank.",
						"pfaq_consumer_ErrMsg_ConsumerAnswer" => "Consumer Answer cannot be blank.",

					);

	$translation_array_en = array(
						"faq_consumer_click_to_edit" => "Click a question to edit it.",
						"faq_consumer_account" => "What is an account",
						"faq_consumer_create_account" => "How do I create an account",
						"faq_consumer_who_create_account" => "Who can create an account",
						"faq_consumer_industry_questions" => "Are there any questions in my industry",
						"faq_consumer_access_requests" => "How do I access requests",
						"faq_consumer_rejected_application" => "What can I do if I think I have been wrongly rejected my application",
						"faq_consumer_login_problems" => "What do I do if I have login problems",
						"faq_consumer_how_guard" => "How does the Guard",

						"faq_consumer_sample_text" => "We at Customer Service fnns always here for you to help you with questions and concerns!\nDo not hesitate to contact us!",

						"faq_consumer_ans_1" => "Through your account at Priside.se manage your bevakningsprofl and received and answered inquiries. You can also search freely among all current requests. To get an account, you pay a flat fee per month.",
						"faq_consumer_ans_2" => "as a supplier, click on ”Company? Apply for an Account” where you fill in your company information and press ”send”. Then the supplier to be contacted by Priside.se.",
						"faq_consumer_ans_3" => "Only companies that have an F-tax, is registered for VAT, payroll and is debt free the magistrate's office.",
						"faq_consumer_ans_4" => "Today supports Priside.se following areas: construction & renovation, land & garden, cleaning, fytt, party & event, engine, catering, computing / IT, photo / video, training, marketing, finance, law and architectural and interior design. We are continually working to expand the service to fera industries. If your industry not fnns today please come with suggestions.",
						"faq_consumer_ans_5" => "You can always search requests directly via the front page for suppliers. To respond to a request you need to have an account. No account? Click HERE",
						"faq_consumer_ans_6" => "Yes. If you believe that the grounds for your refusal has been inaccurate, please contact Customer Service to reconsider your application by supplementing your application with additional information and documents.",
						"faq_consumer_ans_7" => "Immediately contact our customer service via e-mail or telephone to resolve the issue.",
						"faq_consumer_ans_8" => "When a request is created matched it against your bevakningsprofl. You accept only those requests that match your guard profl. Then you can begin to respond to job requests.",

						"faq_consumer_save_success_msg" => "FAQs : Consumer information are successfully saved.",
						"faq_consumer_update_success_msg" => "FAQs : Consumer information are successfully updated.",
						"faq_consumer_remove_success_msg" => "FAQs : Consumer information are successfully removed.",
						"faq_consumer_error_entry_validation" => "Please fill-up the entries properly.",

						"faq_consumer_confirm_delete_title" => "Remove FAQ item",
						"faq_consumer_confirm_delete" => "Are you sure you want to delete the Regular FAQ?",

						"faq_consumer_no_data" => "No consumer FAQs available.",

						// php errors / etc.
						"pfaq_consumer_ErrMsg_ConsumerId" => "Consumer Id Question cannot be blank.",
						"pfaq_consumer_ErrMsg_ConsumerQuestion" => "Consumer Question cannot be blank.",
						"pfaq_consumer_ErrMsg_ConsumerAnswer" => "Consumer Answer cannot be blank.",

						);

if( ! is_array( $trans ) ) $trans = array();

if( "en" == $lang ){
	$trans = array_merge( $trans, $translation_array_en );
}
else{
	$trans = array_merge( $trans, $translation_array_sv );
}
?>
