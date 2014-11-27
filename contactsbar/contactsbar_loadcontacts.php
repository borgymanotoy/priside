<?php
	require_once( "../libs/verify_session.php" );
	

/* This file generates the HTML used for displaying the contact list in
 * the contacts bar.
 *
 * This script does different things depending on the value of a url parameter
 * "action". "action" can be any of the following values:
 *    "generate"  Generates and displays the list of online contacts.
 *                When using this the optional parameter "page" specifies what
 *                page of contacts to display. The first page has index 1.
 *                If "page" isn't supplied, it will default to 1.
 *    "countpages"   Returns the number of pages needed to display the online contacts.
 *                   The result is return as a JSON object, eg { 'pages' : 4 }.
 *    "onlinecontacts"  Returns a list of the online contacts.
 *    "allcontacts"  Returns all contacts.
 */

      /* Database simulation code. */
      $contacts = array(
         array('id' => 1, 'name' => 'Alice Baah', 'company' => 'Big Company'),
         array('id' => 2, 'name' => 'Daniel Lemma', 'company' => 'The Bureau AB'),
         array('id' => 3, 'name' => 'Chris Veeneman', 'company' => 'Vancouver Carrots'),
         array('id' => 4, 'name' => 'Charles Amper', 'company' => 'Lady Killers Inc'),
         array('id' => 5, 'name' => 'Eric Ahmad', 'company' => 'MIBS'),
         array('id' => 6, 'name' => 'Jef Kong', 'company' => 'Sleepless Knights Ltd'),
         array('id' => 7, 'name' => 'Josielyn Sunga', 'company' => 'Indoor Vacancies Inc'),
         array('id' => 8, 'name' => 'Fredrik Nygren', 'company' => 'OpFor Inc')
		 
      );

$MAX_COLUMNS = 2; // Maximum number of simultaneously displayed columns.
$CONTACTS_PER_COLUMN = 3; // Maximum number of contacts per column.



// Check what action we are expected to do.
if (!isset($action)) {
   // Do nothing, but probably we should simply generate the first page.
}
else if (strcmp($action, 'countpages') == 0)
{
   // Get number of online contacts.
   $nContacts = count($contacts);
   $nPages = $nContacts / ($MAX_COLUMNS * $CONTACTS_PER_COLUMN);
   $nPages = ceil($nPages);
   echo json_encode( array('onlinecontacts' => $nContacts, 'pages' => $nPages));
}
else if (strcmp($action, 'onlinecontacts') == 0)
{
   echo json_encode($contacts);
}
else if (strcmp($action, 'allcontacts') == 0)
{
   echo json_encode($contacts);
}
else if (strcmp($action, 'getconversationid') == 0)
{
   //var_dump($users);
   //echo json$users[0];
}
else if (strcmp($action, 'generate') == 0)
{
      $startPage = 1;
      if (isset($page) && !empty($page)) {
         $startPage = intval($page);
      }

      // We will only show $MAX_COLUMNS columns of contacts at once.
      // Each column shall only show $CONTACTS_PER_COLUMN contacts.
      // So first we have an outer loop representing columns (hence +3 in each iteration).
      // Inside that we have a loop for outputting the contacts within that column.
      $startRow = ($startPage - 1) * $MAX_COLUMNS * $CONTACTS_PER_COLUMN;
      $contacts = array_slice($contacts, $startRow, $MAX_COLUMNS * $CONTACTS_PER_COLUMN);
      $nContacts = count($contacts);
      $contactIdx = 0;
      ?>

      <? for ($i = 0; $i < $MAX_COLUMNS && $contactIdx < $nContacts; $i++): ?>
         <div class="cb_contacts_column">

         <? for ($j = 0; $j < $CONTACTS_PER_COLUMN && $contactIdx < $nContacts; $j++): ?>
            <?php $contact = $contacts[$contactIdx]; ?>
            <div id="cb_contact_<?=$contact['id']?>" class="cb_contact">
               <span><img src="/img/cb_contactphoto.png" class="cb_contactphoto"></span>
               <span class="cb_contact_name"><?=$contact['name']?></span>
               <span class="cb_contact_company">, <?=$contact['company']?></span>
            </div>
            <?php $contactIdx++; ?>
         <? endfor ?>

         </div> <!-- contacts column -->
      <? endfor; ?>


<?php
} // End action if-else
?>
