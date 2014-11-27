<?php


$translation_array_sv = array(
					"cancel_accnt_cancel_account"=>"Avsluta mitt konto",
					"cancel_accnt_cancel_acc_text"=>"Du kan när som helst avsluta ditt konto hos Prisidé. Vi tar då bort dina uppgifter från våra register. Observera att även alla dina konversationer, kontakter och fler du har lagrat på priside.se försvinner. Om du vill kan du spara dem till din dator genom att klicka på 
					\"Spara mina konversationer, kontakt-uppgifter och fler...\" under Mina förfrågningar och Mina projekt.",
					"cancel_accnt_save_email_add_text"=>"Prisidé får gärna spara min mailadress för fortsatta utskick med information och erbjudanden kring deras tjänster",
					"cancel_accnt_close_acc"=>"Avsluta konto",
					"cancel_accnt_cancel_contract"=>"Säg upp avtal",
					"cancel_accnt_cancel_contract_text"=>"För att säga upp ditt avtal med Prisidé skickar du in uppsägningblanketten i ett rekomenderat blev till 
oss. Uppsägningsblanketten kan du ladda ner via länken nedan. Observera att ditt konto då raderas 
tillsammans med alla dina konversationer, kontakter och fler du har lagrat på priside.se. Om du vill 
kan du först spara dem till din dator genom att klicka på \"Spara mina konversationer, kontaktuppgifter 
och fler...\" under Mina förfrågningar och Mina projekt.",
					"cancel_accnt_uppsag"=>"Uppsägnigsblankett",
					"cancel_accnt_cancel_acc_text_side_bar"=>"Här kan du lägga till användare som du vill ska ha tillgång till ditt konto på Prisidé. Du väljer själv vilka behörigheter de ska ha. När en användare har lagts till skickas ett meddelande till den personens e-postadress med instruktioner om hur de loggar in och skapar en användarprofl.",
					"cancel_accnt_cancel_help"=>"hjälp"
				);

$translation_array_en = array(
					"cancel_accnt_cancel_account"=>"Cancel My Account",
					"cancel_accnt_cancel_acc_text"=>"You can always cancel your account with Prisidé. We will remove your details from our records. Note that all your conversations, contacts and more you have stored on priside.se disappears. If you prefer, you can save them to your computer by clicking the \"Save my conversations, contacts data and more ... \"Under My inquiries and My projects.",
					"cancel_accnt_save_email_add_text"=>"Prisidé Feel free to save my email address for future mailings with information
and offers regarding their services",
					"cancel_accnt_close_acc"=>"Close Account",
					"cancel_accnt_cancel_contract"=>"Cancel the contract",
					"cancel_accnt_cancel_contract_text"=>"To cancel your contract with Prisidé send in notice form in a recommended was to
us. Termination This form can be downloaded from the link below. Please note that your account will be deleted when
with all of your conversations, contacts and more you have stored on priside.se. If you want to
you can first save them to your computer by clicking the \"Save my conversations, contacts
and more ... \"under my inquiries and my projects.",
					"cancel_accnt_uppsag"=>"Uppsägnigsblankett",
					"cancel_accnt_cancel_acc_text_side_bar"=>"You can add users that you want to have access to your account Prisidé. You decide what permissions they should have. When a user is added to sent a message to the person's email address with instructions on how to log on and creating a användarprofl.",
					"cancel_accnt_cancel_help"=>"help"
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
?>