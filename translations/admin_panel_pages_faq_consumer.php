<?php
	require_once( $_SERVER['DOCUMENT_ROOT']."/translations/admin_panel_pages_faq.php" );
	require_once( $_SERVER['DOCUMENT_ROOT']."/translations/pages_common.php" );

	$translation_array_sv = array(
						"faq_consumer_click_to_edit" => "Klicka p� en fr�ga f�r att redigera den.",
						"faq_consumer_account" => "Vad �r ett konto",
						"faq_consumer_create_account" => "Hur skapar jag ett konto",
						"faq_consumer_who_create_account" => "Vem kan skapa ett konto",
						"faq_consumer_industry_questions" => "Finns det f�rfr�gningar inom min bransch",
						"faq_consumer_access_requests" => "Hur f�r jag tillg�ng till f�rfr�gningar",
						"faq_consumer_rejected_application" => "Kan jag g�ra n�got om jag anser att jag har f�tt ett felaktigt avslag p� min ans�kan",
						"faq_consumer_login_problems" => "Vad g�r jag om jag har inloggningsproblem",
						"faq_consumer_how_guard" => "Hur fungerar bevakningen",

						"faq_consumer_sample_text" => "Vi p� kundtj�nst fnns alltid h�r f�r dig f�r att hj�lpa dig med fr�gor och funderingar!\nTveka inte att kontakta oss!",

						"faq_consumer_ans_1" => "Via ditt konto p� Priside.se hanterar du din bevakningsprofil och mottagna och svarade f�rfr�gningar. Du kan �ven s�ka fritt bland alla aktuella f�rfr�gningar. F�r att f� ett konto betalar du en fast avgift per m�nad.",
						"faq_consumer_ans_2" => "som leverant�r klickar du p� �F�retag? Ans�k om konto� d�r du fyller i din f�retagsinformation och trycker sedan �skicka�. Sedan kommer leverant�ren bli kontaktad av Priside.se.",
						"faq_consumer_ans_3" => "Endast f�retag som har F-skattesedel, �r registrerat f�r moms och arbetsgivaravgift samt �r skuldfritt hos kronofogden.",
						"faq_consumer_ans_4" => "Idag st�djer Priside.se f�ljande omr�den: bygg & renovering, mark & tr�dg�rd, st�dning, fytt, fest & event, motor, catering, data/IT, foto/bild, utbildning, marknadsf�ring, ekonomi, juridik samt arkitekt & inredning. Vi arbetar kontinuerligt med att ut�ka tj�nsten f�r fera branscher. Om din bransch inte fnns med idag kom g�rna med f�rslag till oss.",
						"faq_consumer_ans_5" => "Du kan alltid s�ka f�rfr�gningar direkt via f�rstasidan f�r leverant�rer. F�r att svara p� en f�rfr�gan beh�ver du ha ett konto. Inget konto? Klicka H�R",
						"faq_consumer_ans_6" => "Ja. Om du anser att grunderna f�r ditt avslag har varit felaktiga kan du kontakta kundservice f�r att ompr�va din ans�kan genom att komplettera din ans�kan med ytterligare uppgifter och handlingar.",
						"faq_consumer_ans_7" => "Kontakta omedelbart v�r kundservice per e-post eller telefon f�r att �tg�rda problemet.",
						"faq_consumer_ans_8" => "N�r en f�rfr�gan skapas matchas den mot din bevakningsprofil. Du tar endast emot de f�rfr�gningar som matchar din bevaknings profil. D�refter �r det bara att b�rja svara p� jobbf�rfr�gningarna.",

						"faq_consumer_save_success_msg" => "Vanliga fr�gor : Konsument information framg�ngsrikt sparas.",
						"faq_consumer_update_success_msg" => "Vanliga fr�gor : Konsument information framg�ngsrikt uppdateras.",
						"faq_consumer_remove_success_msg" => "Vanliga fr�gor : Konsument information framg�ngsrikt avl�gsnades.",
						"faq_consumer_error_entry_validation" => "V�nligen fyll upp posterna p� r�tt s�tt.",

						"faq_consumer_confirm_delete_title" => "Avl�gsna Vanliga objektet",
						"faq_consumer_confirm_delete" => "�r du s�ker p� att du vill ta bort den �terkommande FAQ?",

						"faq_consumer_no_data" => "Inga konsumentprodukter fr�gor som finns.",

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
						"faq_consumer_ans_2" => "as a supplier, click on �Company? Apply for an Account� where you fill in your company information and press �send�. Then the supplier to be contacted by Priside.se.",
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
