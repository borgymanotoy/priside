<?php
	/* 
	 * contactsbar.php
	 *
	 * Include this file to where the contactsbar HTML should be on your page.
	 * Include the contactsbar.css file inside your <head></head>.
	 * * For IE7 support also include contactsbar_ie7.css.
	 *
	 * Requires: jQuery. Tested with jQuery 1.7.2.
	 *
	 * TODO: Embedd everything within a PHP-function or similar so we
	 *       get a protected variable namespace and don't risk name collisions?
	 */

	// requires authentication
	require_once( $_SERVER['DOCUMENT_ROOT'] . "/libs/verify_session.php" );
	require_once( $_SERVER['DOCUMENT_ROOT']."/translations/contactsbar.php" );
?>

<script type="text/javascript">
<?php include( $_SERVER['DOCUMENT_ROOT'] . '/contactsbar/contactsbar.js'); ?>
</script>

<script type="text/javascript">
<?php include( $_SERVER['DOCUMENT_ROOT'] . '/contactsbar/chatwindow.js'); ?>
</script>

<script type="text/javascript">
<?php
include($_SERVER['DOCUMENT_ROOT'].'/js/messages.js');
?>
</script>

<script type="text/javascript">
$(document).ready(function() {
   // Handler for toggling contacts bar.
   $('#cb_header').toggle(
      function() {
         $('#cb_content').show();
      },
      function() {
         $('#cb_content').slideUp();
      });

   contactsBar.goOffline();
   contactsBar.loadAndDrawMessageList ();
   contactsBar.loadAndDrawOnlineContactList(); // Load contact list

   // Attach click handler for contacts
   $('#cb_contacts').off('click', '#cb_contacts div[id*="cb_contact_"]');
   $('#cb_contacts').on('click', '#cb_contacts div[id*="cb_contact_"]',
      function() {
         var userId = $(this).attr('id');
         userId = userId.substring('cb_contact_'.length);
         contactsBar.createChatWindowForUser(userId);
         contactsBar.setAllChatMessagesFromUserRead(userId);
         return false;

      });


   // TODO: Move this to the contact list generation.
   // Attach click handler for contact list navigation
   $('#cb_contacts_next').click(
      function() {
         contactsBar.setContactPage(contactsBar.getOnlineContactPage() + 1);
      });
   $('#cb_contacts_prev').click(
      function() {
         contactsBar.setContactPage(contactsBar.getOnlineContactPage() - 1);
      });

   // TODO: Debug code. Remove!
   // Online/offline button toggle.
   $('#cb_online_toggle_btn').toggle(
      function() {
         contactsBar.goOnline();
         $(this).html('Go offline');
      },
      function() {
         contactsBar.goOffline();
         $(this).html('Go online');
      });
});
</script>


<div id="cb_contactsbar">
   <!-- chat windows -->
   <div id="cb_chatwindows_spacer">
      <div id="cb_chatwindows_container"></div>
   </div>

   <!-- Header -->
   <div id="cb_header">
   <div class="cb_header_tl">
   <div class="cb_header_tr">
      <span id="cb_msgcount"></span><img src="/img/cb_msg.png" width="15" height="11">
      <span id="cb_contactsonlinecount"></span>
      <img src="/img/cb_contacts_icon.png" width="11" height="13">
      <?php // TODO: This button is for debugging only. Remove! ?>
      <button id="cb_online_toggle_btn" type="button" onClick="contactsBar.goOnline()"
        style="padding-top:0px; padding-bottom:0px;">
        Go online
      </button>
      <div id="cb_startchat"><?=$trans['cb_startchat']?></div>
   </div></div>
   </div>


   <!-- Content (messages and contacts) -->
   <div id="cb_content" style="display: none;">

      <!-- Messages -->
      <div id="cb_messages">

         <div id="cb_message_row_1" class="cb_message_row">
            <div class="cb_messages_prev"><img src="/img/cb_arrow_left.png"></div>
            <div id="cb_message_1" class="cb_message">
               <div class="cb_message_sender">
                  <span id="cb_contact_name_1" class="cb_contact_name">Marcus Weibull</span>
                  <span id="cb_contact_company_1" class="cb_contact_company">, Moducera AB</span>
               </div>
               <div class="cb_message_brief">
                  <span id="cb_message_hour_1" class="cb_message_hour">18:00</span>
                  <span id="cb_message_brief_text_1">N&auml;r &auml;r vi klara f&ouml;r lansering av...</span>
               </div>
            </div> <!-- cb_message -->
            <div class="cb_messages_next"><img src="/img/cb_arrow_right.png"></div>
         </div>

         <div id="cb_message_row_2" class="cb_message_row">
            <div class="cb_messages_prev"><img src="/img/cb_arrow_left.png"></div>
            <div id="cb_message_2" class="cb_message">
               <div class="cb_message_sender">
                  <span id="cb_contact_name_2" class="cb_contact_name">Marcus Weibull</span>
                  <span id="cb_contact_company_2" class="cb_contact_company">, Moducera AB</span>
               </div>
               <div class="cb_message_brief">
                  <span id="cb_message_hour_2" class="cb_message_hour">18:00</span>
                  <span id="cb_message_brief_text_2">N&auml;r &auml;r vi klara f&ouml;r lansering av...</span>
               </div>
            </div> <!-- cb_message -->
            <div class="cb_messages_next"><img src="/img/cb_arrow_right.png"></div>
         </div>
      </div>

      <!-- Contacts -->
      <div id="cb_contacts">
         <div id="cb_contacts_list">
            <!-- Anything placed here will be overwritten! -->
         </div>
         <div class="cb_contacts_nav">
            <img id ="cb_contacts_prev" src="/img/cb_arrow_left.png">
            <img id ="cb_contacts_next" src="/img/cb_arrow_right.png">
         </div>
      </div> <!-- Contacts -->
   </div> <!-- Content -->
</div> <!-- Contacts bar -->

<!-- Templates -->
<div id="cb_contact_template" class="template">
   <div class="cb_contacts_column"></div>

   <div id="cb_contact_" class="cb_contact">
      <span><img src="/img/cb_contactphoto.png" class="cb_contactphoto"></span>
      <span class="cb_contact_name"></span>
      , 
      <span class="cb_contact_company"></span>
   </div>
</div>


<div id="cb_chatwin_template" class="template">
   <div id="cb_chatwin_u" class="cb_chat_window">
      <div class="cb_chat_window_header">
         <img src="/img/cb_contactphoto.png" class="cb_contactphoto_header"> 
         <span class="cb_chat_window_title">&nbsp;</span>
         <div class="cb_chat_window_buttons">
            <button id="cb_chatwin_btn_min_" type="button" title="<?=$trans['cb_minimize']?>" style="font-size: 8px;">-</button>
            <button id="cb_chatwin_btn_close_" type="button" title="<?=$trans['cb_close']?>" style="font-size: 8px;">X</button>
         </div>
      </div>
      <div class="cb_chatwin_content">
         <div class="cb_chatwin_invite_container">
            <div class="cb_chatwin_invite_toggle">
               <img src="/img/cb_arrow_right.png">
               <?=$trans['cb_chat_invite']?>
            </div>
            <form style="display: none;">
               <input type="text">
            </form>
         </div>
         <div class="cb_chatwin_messages">
            <ul class="cb_chatwin_messages_container">
            </ul>
         </div>
         <div>
            <form class="cb_chatwin_newmsg_input">
               <div class="cb_roundborder_tl"><div class="cb_roundborder_br">
               <div class="cb_roundborder_tr"><div class="cb_roundborder_bl">
                  <input type="text" style="border: 0px;">
               </div></div></div></div>
            </form>
         </div>
      </div>
   </div>

   <div>
      <li id="cb_chatwin_message_template">
         <span class="cb_chatwin_sender"></span>
         <span class="cb_chatwin_message"></span>
      </li>
      <li id="cb_chatwin_message_sendfail_template" class="cb_chatwin_message_sendfail">
      </li>
      <li id="cb_chatwin_message_invited_template" class="cb_chatwin_invited">
      </li>
   </div>
</div>




<!-- TODO: DEBUG CODE. Remove! -->
<div style="border: 1px black solid; margin-top: 5px; padding: 5px; display:none;">
<form>

   <!-- Message debug -->
   <div>
   <button type="button" onClick="contactsBar.increaseUnreadMessages()">Receive message</button> 
   <button type="button" onClick="contactsBar.decreaseUnreadMessages()">Read message</button> 

   <!-- Contact actions debug -->
   <!--
   <script type="text/javascript">
      function dbg_receiveChatMsg() {
         var selectedUser = $('#dbg_usercontacts').val();
         // Deprecated. contactsBar.incomingChatMessage(selectedUser);
      }

      function dbg_readChatMsg() {
         var selectedUser = $('#dbg_usercontacts').val();
         contactsBar.setAllChatMessagesFromUserRead (selectedUser);
      }
   </script>
   <div style="margin-top: 15px;">
      <select id="dbg_usercontacts" name="usercontacts">
      </select>
      <button type="button" disabled="disabled">Set online</button>
      <button type="button" disabled="disabled">Set offline</button>
      <button type="button" onClick="dbg_receiveChatMsg()">Receive chat message</button>
      <button type="button" onClick="dbg_readChatMsg()">Chat read</button>
   </div>
   -->

   <!-- Misc contact debug -->
      <!--
         <button type="button" onClick="this.disabled='disabled'; contactsBar.startChatService()">Start chat service</button>
      -->
      <button type="button" onClick="contactsBar.goOnline()">Go online</button>
      <button type="button" onClick="contactsBar.goOffline()">Go offline</button>
   </div>
</form>
</div>
