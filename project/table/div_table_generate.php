
<?php

$startRow = $_GET['start'];
$endRow = $startRow + 1;


if (isset( $_GET['count'] )) {

}
   
   
$rows = getRequests();
$rows = array_slice($rows, $startRow, ($endRow - $startRow + 1));
?>

   <!-- Header -->
   <div class="requestsHeader">
   <div class="serviceheadercontainer_top_left"><div class="serviceheadercontainer_bottom_right">
   <div class="serviceheadercontainer_top_right"><div class="serviceheadercontainer_bottom_left">
      <div class="tableColumn date">DATUM</div>
      <div class="tableColumn category">FÖRFRÅGAN</div>
      <div class="tableColumn area">KOMMUN</div>
      <div class="tableColumn status">STATUS</div>
      <div class="tableColumn delete"> </div>
   </div></div>
   </div></div>
   </div>

   <!-- Rows -->
   <?php $rowNbr = 1; ?>
   <?php foreach ($rows as &$row): ?>
   <div id="serviceRequestRow<?=$rowNbr?>" class="serviceRequest <?php echo $rowNbr%2==0 ? 'even' : 'odd'; ?> nonselectable">
   <div class="servicecontainer_top_left"><div class="servicecontainer_bottom_right">
   <div class="servicecontainer_top_right"><div class="servicecontainer_bottom_left">

      <!-- Anything inside this div can be clicked to open details. -->
      <div class="clickable requestTableRowClickClass<?=$rowNbr?>">
         <div class="tableColumn date"><?=$row['date']?></div>

         <div class="tableColumn category">
            <?=$row['category']?><br>
            <h3 class="requestTitle"><?=$row['title']?></h3>
         </div>

         <div class="tableColumn area"><?=$row['kommun']?></div>

         <div class="tableColumn status">
            <?php if ($row['replied'] == true): ?>
               <img src="/img/request_replied.png" width="16" height="20">
            <?php else: ?>
               <img src="/img/request_notreplied.png" width="16" height="20">
            <?php endif ?>
            <!-- Hack to keep images together
            <?php for ($i=0; $i<$row['nReplies']; $i++): ?>
               --><img src="/img/request_replyicon.png" width="13" height=18"><!--
            <?php endfor ?>
            -->
         </div>

         <div class="tableColumn delete">
            <img id="deleteRequestRow<?=$rowNbr?>" class="notShown" src="/img/row_delete.png" width="8" height="8">
         </div>

      </div> <!-- END: requestTableRowClickClass -->

      <!-- Expandable detail area -->
      <div id="requestTableRowExpand<?=$rowNbr?>" class="requestDetailsContainer notShown">
         <!-- Project info -->
         <div class="requestDetails">
            <h3 class="requestDetailLabel">Utföres åt:</h3> <?=$row['entity']?><br>
            <h3 class="requestDetailLabel">När:</h3> <?=$row['when']?><br>
            <h3 class="requestDetailLabel">Beskrivning:</h3> <?=$row['description']?>
         </div>
         <!-- Contact info -->
         <div class="contactInfo">
            <div class="contactinfobox_top_left"><div class="contactinfobox_bottom_right">
            <div class="contactinfobox_top_right"><div class="contactinfobox_bottom_left">
            <h3 class="contactInfoHeader">Kontaktuppgifter</h3>
            Namn: <?=$row['contactName']?><br>
            Telefon: <?=$row['contactPhone']?><br>
            E-post: <a href="mailto:<?=$row['contactEmail']?>"><?=$row['contactEmail']?></a>
            </div></div>
            </div></div>
         </div>
      </div>
      <!-- END: Expandable detail area -->

   </div></div>
   </div></div>
   </div> <!-- END row (<div class="tableRow">) -->

   <script type="text/javascript">
   var $rtrcc = $('.requestTableRowClickClass<?=$rowNbr?>');
   // Register click handlers for request row.
   $rtrcc.toggle(
      function() {
         $('#requestTableRowExpand<?=$rowNbr?>').slideDown(150);
         return false;
      },
      function() {
         $('#requestTableRowExpand<?=$rowNbr?>').hide();
         return false;
      }
   );
   // Register onMouseOver for request row.
   var $row = $('#serviceRequestRow<?=$rowNbr?>');
   $row.mouseover(
      function() {
        $('#deleteRequestRow<?=$rowNbr?>').show(); 
      }
   );
   $row.mouseleave(
      function() {
        $('#deleteRequestRow<?=$rowNbr?>').hide(); 
      }
   );
   </script>
   <?php $rowNbr++; ?>
   <?php endforeach ?>

<?php

/* Returns available requests.
 *
 * Simple database stub for testing purposes. */
function getRequests() {

   $rows = array (
      array('date' => '2011-12-15', 'category' => 'Administration',
            'title' => 'Bokföringsuppdrag till firma i ...',
            'kommun' => 'Malmö',
            'replied' => false,
            'nReplies' => 4,
            'entity' => 'Företag', 'when' => 'Snarast möjligt',
            'description' => 'Vi behöver bokföringshjälp till vårt familjeföretag med 5 anställda. Företagets jobb är inom städbranschen. Uppdraget gäller ett år till att börja med då vår ordinare ekonomiansvarige gått på föräldraledighet.<br>
   <br>
   Vår omsättning är ca 1 800 000 kr/år.',
            'contactName' => 'Simon',
            'contactPhone' => '0709-72 04 73',
            'contactEmail' => 'simon@kefas.se' ),
      array('date' => '2011-12-15', 'category' => 'Administration',
            'title' => 'Vi behöver bokföringshjälp till vårt långa meddelande. Det är så långt att det sträcker sig längre än vad det får.',
            'kommun' => 'Lund',
            'replied' => true,
            'nReplies' => 2,
            'entity' => 'Företag', 'when' => 'Inom 3 månader',
            'description' => 'Vi behöver bokföringshjälp till vårt familjeföretag med 5 anställda. Företagets jobb är inom städbranschen. Uppdraget gäller ett år till att börja med då vår ordinare ekonomiansvarige gått på föräldraledighet.<br>
   <br>
   Vår omsättning är ca 1 800 000 kr/år.',
            'contactName' => 'Mike',
            'contactPhone' => '0709-12 34 56<br>a<br><br>a<br><br>a<br><br>a<br>',
            'contactEmail' => 'mark@aratherlongandlengthyexample.com' ),

      array('date' => '2011-12-15', 'category' => 'Administration',
            'title' => 'Monitor cleaning',
            'kommun' => 'Lund',
            'replied' => false,
            'nReplies' => 0,
            'entity' => 'Företag', 'when' => 'Inom 3 månader',
            'description' => 'My monitor is the dirty. Please, clean it. OK, thx, bye!',
            'contactName' => 'Dirty Harry',
            'contactPhone' => '0709-12 34 56<br>a<br><br>a<br><br>a<br><br>a<br>',
            'contactEmail' => 'harry@home.com' ),
      array('date' => '2011-12-15', 'category' => 'Administration',
            'title' => 'Bottle counting',
            'kommun' => 'Bofors',
            'replied' => false,
            'nReplies' => 1,
            'entity' => 'Företag', 'when' => 'Inom 3 månader',
            'description' => 'I have three bottles on my desk. I need someone to count them and make sure that there are actually three.',
            'contactName' => 'Count Ing',
            'contactPhone' => '0709-12 34 56<br>a<br><br>a<br><br>a<br><br>a<br>',
            'contactEmail' => 'ing@enuity.com' )
      );

return $rows;
}
?>
