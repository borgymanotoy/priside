<?php


$translation_array_sv = array(
					"cancel_accnt_cancel_account"=>"Avsluta mitt konto",
					"cancel_accnt_cancel_acc_text"=>"Du kan n�r som helst avsluta ditt konto hos Prisid�. Vi tar d� bort dina uppgifter fr�n v�ra register. Observera att �ven alla dina konversationer, kontakter och fler du har lagrat p� priside.se f�rsvinner. Om du vill kan du spara dem till din dator genom att klicka p� 
					\"Spara mina konversationer, kontakt-uppgifter och fler...\" under Mina f�rfr�gningar och Mina projekt.",
					"cancel_accnt_save_email_add_text"=>"Prisid� f�r g�rna spara min mailadress f�r fortsatta utskick med information och erbjudanden kring deras tj�nster",
					"cancel_accnt_close_acc"=>"Avsluta konto",
					"cancel_accnt_cancel_contract"=>"S�g upp avtal",
					"cancel_accnt_cancel_contract_text"=>"F�r att s�ga upp ditt avtal med Prisid� skickar du in upps�gningblanketten i ett rekomenderat blev till 
oss. Upps�gningsblanketten kan du ladda ner via l�nken nedan. Observera att ditt konto d� raderas 
tillsammans med alla dina konversationer, kontakter och fler du har lagrat p� priside.se. Om du vill 
kan du f�rst spara dem till din dator genom att klicka p� \"Spara mina konversationer, kontaktuppgifter 
och fler...\" under Mina f�rfr�gningar och Mina projekt.",
					"cancel_accnt_uppsag"=>"Upps�gnigsblankett",
					"cancel_accnt_cancel_acc_text_side_bar"=>"H�r kan du l�gga till anv�ndare som du vill ska ha tillg�ng till ditt konto p� Prisid�. Du v�ljer sj�lv vilka beh�righeter de ska ha. N�r en anv�ndare har lagts till skickas ett meddelande till den personens e-postadress med instruktioner om hur de loggar in och skapar en anv�ndarprofl.",
					"cancel_accnt_cancel_help"=>"hj�lp"
				);

$translation_array_en = array(
					"cancel_accnt_cancel_account"=>"Cancel My Account",
					"cancel_accnt_cancel_acc_text"=>"You can always cancel your account with Prisid�. We will remove your details from our records. Note that all your conversations, contacts and more you have stored on priside.se disappears. If you prefer, you can save them to your computer by clicking the \"Save my conversations, contacts data and more ... \"Under My inquiries and My projects.",
					"cancel_accnt_save_email_add_text"=>"Prisid� Feel free to save my email address for future mailings with information
and offers regarding their services",
					"cancel_accnt_close_acc"=>"Close Account",
					"cancel_accnt_cancel_contract"=>"Cancel the contract",
					"cancel_accnt_cancel_contract_text"=>"To cancel your contract with Prisid� send in notice form in a recommended was to
us. Termination This form can be downloaded from the link below. Please note that your account will be deleted when
with all of your conversations, contacts and more you have stored on priside.se. If you want to
you can first save them to your computer by clicking the \"Save my conversations, contacts
and more ... \"under my inquiries and my projects.",
					"cancel_accnt_uppsag"=>"Upps�gnigsblankett",
					"cancel_accnt_cancel_acc_text_side_bar"=>"You can add users that you want to have access to your account Prisid�. You decide what permissions they should have. When a user is added to sent a message to the person's email address with instructions on how to log on and creating a anv�ndarprofl.",
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