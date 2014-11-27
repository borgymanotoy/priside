<html>
<head>
<?php
/*
 * This php file can be used for two purposes:
 *    1. Create the entire page.
 *    2, Create only the table part of this page.
 * It detects what it should do by checking if the parameter "start" is sent
 * to it. Purpose 2. (creating only the table part) is only intended for use
 * by links created by this php file.
 *
 * The basic layout/algorithm of the file is as follows:
 *    1. Check if we should generate the entire page.
 *    2. If entire page output top of page (pre table).
 *    3. Output table.
 *    4. If entire page output bottom of page (post table).
 *
 * */

include('div_table.css');

// STEP 1 - Generate entire page?
$entirePage = true;
if (isset( $_GET['start'] )) {
   $entirePage = false;
}

// STEP 2 - Output top of page?
if ($entirePage) {

}
?>
<link rel="stylesheet" type="text/css" href="http://yui.yahooapis.com/3.4.1/build/cssreset/cssreset-min.css">
<script type="text/javascript" src="../js/jquery-latest.js"></script>
</head>
<body>
Table created using &lt;div&gt;<br>

<br><br>

<div id="pageLinks">
<a href="asdfadfs">Click me pretty please!</a>
</div>


<!-- TODO: Need to figure out how many pages we have. -->
<div onClick="generateTable(0)">Generate table!</div>
<div onClick="generateTable(1)">Generate part of table!</div>

<!-- This is where we'll load the  table html into. -->
<div id="requestsContainer"></div>

<script type="text/javascript">
// Example of overriding links to prevent them from reloading entire page.
$(document).ready(function() {
   $('#pageLinks').click(function() {
      alert('Clicked!' + $(this).attr('href'));
      return false;
   }
   );
}); // end document ready


function generateTable(start) {
   if (null == start) {
      start = 0;
   }
   alert(start)
   $('#requestsContainer').load('div_table_generate.php?start=' + start);
   // If we want to keep all code in one document we could do this instead:
   // $('#requestsContainer').load('this_document.php #requestsContainer')
   // that would only replace the part of the document within #requestsContainer.
}

</script>

</body>