//==============================================================================
//=                                                                            = 
//= ContactsBar class                                                          =
//=                                                                            = 
//==============================================================================

/*
 * Class for accessing the ContactsBar via JavaScript.
 *
 * Use the variable contactsBar to access the default instance.
 * It is not recommended to create other instances of this class.
 *
 * Needs the class ChatWindow to be available.
*/
var ContactsBar = function() {
   //================================================== 
   //= Private fields                                 = 
   //=   - Access without using "this.".              = 
   //================================================== 
   // "Constants"
   var COLUMNS_PER_PAGE = 2; // Max allowed number of  displayed columns for contacts.
   var CONTACTS_PER_COLUMN = 3; // Max contacts shown per column.
   
   // How often to refresh the list of online contacts from server (in milliseconds).
   var CFG_REFRESH_CONTACT_LIST_DELAY = 10000;

   // How often to check for new chat messages (in seconds).
   // When we've received a chat message the index will be set to 0 and after
   // each successive timeout it will be increased by one.
   var CFG_REFRESH_CHAT_MESSAGE_DELAY = [3, 3, 3, 3, 3, 3, 3, 3, 3, 3,
      5, 5, 5, 5, 5, 5, 5, 5, 5, 5, 5, 5, 10, 10, 10, 10, 10, 10, 
      10, 10, 10, 10, 10, 10, 10, 10, 10, 10, 10, 10, 20];


   // Non-constants below.
   
   //   -- Message variables
   var mnUnreadMessages = 0;
   
   //   -- Contacts variables
   var mUsers = new Array();   // Array of fetched users. This isn't the user's complete contact list.
                               // It's a cache of the users we've got from the server.
                               // They are not stored in any particular order.
   var mOnlineContacts = new Array(); // Array of online contacts.
   var mnOnlineContacts = 0;   // Number of online contacts.
   var mnContactsPages = 1;    // Number of pages requried to display all online contacts.
   var mCurrentContactsPage = 1;
   var mIsLoggedInToChat = false;  // If we are logged in to chat. 
   var mOnlineContactListRefreshTimer = null;  // Timer used to refresh list of online contacts.

   //   -- Chat variables
   var mChatServiceStarted = false; // If we've started the poll loop for new chat messages.
   var mChatWindows = new Array();
   var mLastChatMsg = 0; // Id of the last chat message we have received from
                         // the server (integer).
   var mChatUsers = new Array(); // Map from chat/conversation id to an array of user ids.
                                 // Used to know which users are part of a conversation.
                                 // A particular chat/conversation id always has the same set
                                 // of users.
   var mChatUsersRequestCallbacks = new Array(); // Callbacks we should call when we get a reply
                                                 // to our request for users in a chat.
   var mGetNewChatMessagesTimer;   // Timer used to fetch new messages.
   var mChatDelayIndex = 0;        // Index into CFG_REFRESH_CHAT_MESSAGE_DELAY 
                                   // specifying what delay to use for next fetch.

   //================================================== 
   //= Private methods                                = 
   //=   - Access without using this, eg do not call  =
   //=     "this.method()", just call "method()".     =
   //================================================== 
   //function example() {}
   /*
      * Binds a scope to a function call.
      *
      * This can be used to call a function with a specific scope.
      * For example if you have a callback function in an object, the "this"
      * object will normally not refer to your object:
      *
      *  registerCallback(onThingHappened);
      *
      * function onThingHappened() {
      *   this.doStuff(); // This isn't our "class" object that declares onThingHappened()!
      * }
      *
      * If you instead use this function when register your callback then whatever
      * you pass as "scope" will be your this.
      *
      * Example:
      *  registerCallback( bind(this, onThingHappened));
      *
      * function onThingHappened() {
      *   this.doStuff(); // This is our "class" object that declares onThingHappened()!
      * }
      *
      * @param scope   The scope to be used when func is called.
      * @param func    The function you want to pass as an argument to some method.
      * @return        A function that you can pass as a method to get func to be called with
      *                your scope.
      */
   function bind(scope, func) {
      return function() {
         func.apply(scope, arguments);
      };
   }

   /*
    * Stores user information in a cache.
    *
    * If id is already stored, this method will do nothing.
    */
   function cacheUser(id, name, company) {
      if (id < 0 || undefined == id || null == id ||
          undefined == name || null == name) {
         return;
      }

      for (var i=0; i<mUsers.length; i++) {
         if (mUsers[i].id == id) {
            return;
         }
      }

      if (undefined == company || company == null) {
         company = "";
      }
      mUsers.push({'id' : id, 'name' : name, 'company' : company});
   }

   /*
   * Callback method for when we receive an updated list of latest messages.
   *
   * Will redraw the messages list.
   */
   function onLatestMessagesLoaded(data, response) {
      // TODO: Check response for success.
      var nMessages = 2; // We only draw the two latest messages.
      for (var i=1; i<=nMessages && i<=data.length; i++) {
         var $message = $('#cb_message_' + i);
         $message.find('#cb_message_brief_text_' + i).text(data[i-1].getText());
         $message.find('#cb_contact_name_' + i).text(data[i-1].getSenderFullName());
         $message.find('#cb_contact_company_' + i).text(', ' + data[i-1].getSenderBusinessName());
      }
   }

   /*
   * Callback method for when we receive an updated list of online contacts.
   *
   * Will redraw the contacts list.
   */
   function onOnlineContactListLoaded(data, response) {
      // TODO: Check response for success.

      // Create an array of our JSON object.
      mOnlineContacts.length = 0;
      var contacts = $.parseJSON(data);

      for (var i=0; i<contacts.length; i++) {
         if (contacts[i].Id == <?=$_SESSION['User']['Id']?>) {
            continue; // Don't show ourselves in contact list
         }
         mOnlineContacts.push(
               {
                  'id' : contacts[i].Id,
                  'name' : contacts[i].Name,
                  'company' : contacts[i].BusinessName
               }
            );
         // Also save into our user cache.
         cacheUser(contacts[i].Id, contacts[i].Name);
      }

      mnOnlineContacts = mOnlineContacts.length;
      mnContactsPages = Math.ceil(mnOnlineContacts / (COLUMNS_PER_PAGE * CONTACTS_PER_COLUMN));

      // Update online contact list counter
      this.drawOnlineContactListCounter();
      this.drawOnlineContactList(this.getOnlineContactPage());
   }
 
   /*
     * Handles respose of getting the last read chat messages id from the server.
     *
     * Will start the message checking loop.
     *
     * @param data  A JSON object of chat messages.
     * @param response
    */
   function onGetLastReadChatMessageIdResponse(data, response) {
      var value = $.parseJSON(data);

      mLastReadMessageId =  mLastChatMsg = value;
      // TODO: If message checking loop is already started, don't start it.
      this.checkForNewChatMessages();
   }

   /*
     *
     * @param data  A JSON object of chat messages.
     * @param response
    */
   function onCheckForUnreadChatMessagesResponse(data, response) {
      var messages = $.parseJSON(data);
      if (messages.length == 0) {
         // We got no messages back.
         // Since we need a message id when we start polling for new messages
         // (otherwise we get ALL messages)) and we can't get one from our
         // empty message list, we'll have to ask for one.
         this.getLastReadChatMessageId();
         return;
      }

      for (var i=0; i<messages.length; i++) {
         // Cache user name.
         cacheUser(messages[i].FromUser_Id, messages[i].FromUser_Name);

         var msg = new ChatMessage(
               ChatMessage.Type.TEXT,
               messages[i].FromUser_Id,
               messages[i].Message);
         msg.setId(messages[i].MessageId);
         msg.setChatId(messages[i].Conversation_Id);
         handleNewChatMessage(msg);
      }

      // Start the message poll loop
      this.checkForNewChatMessages();
   }

   /*
     * Handles new chat messages received from the server.
     *
     * @param data  A JSON object of chat messages.
     * @param response
    */
   function onCheckForNewChatMessagesResponse(data, response) {
      var messages = $.parseJSON(data);
      for (var i=0; i<messages.length; i++) {
         // Cache user name.
         cacheUser(messages[i].FromUser_Id, messages[i].FromUser_Name);

         var msg = new ChatMessage(
               ChatMessage.Type.TEXT,
               messages[i].FromUser_Id,
               messages[i].Message);
         msg.setId(messages[i].MessageId);
         msg.setChatId(messages[i].Conversation_Id);
         handleNewChatMessage(msg);
      }

      // If we got a message, start checking for messages more frequently.
      if (messages.length > 0) {
         mChatDelayIndex = 0;
         this.checkForNewChatMessages();
      }
   }

   /*
    * message {
    *   "Conversation_Id":"42","MessageId":31,"FromUser_Id":"1",
    *   "FromUser_Name":"Some Person","DateSent":"2012-04-18 17:58:24",
    *   "Message":"Hello, there!"
    * }
    *
    * @param message  ChatMessage object.
    */
   function handleNewChatMessage(message) {
      if (undefined == message || null == message) {
         return;
      }

      if (message.getId() > mLastChatMsg) {
         mLastChatMsg = message.getId();
      }


      // Check if we have a window open already.
      var chatWindow = null;
      for (var i = 0; i<mChatWindows.length; i++) {
         if(mChatWindows[i].getChatId() == message.getChatId()) {
            // Yep, we already have one. Use that.
            chatWindow = mChatWindows[i];
            chatWindow.printMessage(message);
            // We might have more than one window for the same conversation,
            // so let the window loop continue.
         }
      }

      // If we didn't have a window open for that chat already, open one unless
      // this user was the one that sent the message (user has always read
      // his/her own messages).
      if (null == chatWindow && message.getSenderId() != <?=$_SESSION['User']['Id']?> ) {
         chatWindow = new ChatWindow(
               <?=$_SESSION['User']['Id']?>,
               message.getSenderId(),
               message.getChatId(),
               contactsBar);
         mChatWindows.push(chatWindow);
         chatWindow.printMessage(message);
      }

      // TODO: Highlight contact in contact list if chat is only with that contact.
      //       When we get the message we might not now if we're the only recipient.
      //       Because we might not have the list of users in the chat/conversation.
      $('#cb_contact_' + message.FromUser_Id).addClass('cb_unread_message');
   }

   //================================================== 
   //= Public members                                 =
   //=   - Access internally using "this.method()"    = 
   //================================================== 
   return {


      // ====== Contact list ======================

      /*
       * Get name of user.
       *
       * Returns empty string if name of user is not yet known.
       */
      getUserName : function (userid) {
         var len = mUsers.length;
         for (var i=0; i < len; i++) {
            var user = mUsers[i];
            if (user.id == userid) {
               return user.name;
            }
         }

         return "";
      },


      /*
      * Loads and draws the list of online contacts from the server.
      *
      * The load and draw is done asynchronously, so when this method returns it is quite likely
      * that the contact list hasn't been reloaded or redrawn yet.
       */
      loadAndDrawOnlineContactList : function() {
         if (mOnlineContactListRefreshTimer != null) {
            clearTimeout(mOnlineContactListRefreshTimer);
         }

         mOnlineContactListRefreshTimer = setTimeout(
               bind(this, this.loadAndDrawOnlineContactList), CFG_REFRESH_CONTACT_LIST_DELAY);
         
         // Don't update contact list if we're not online
         if (!mIsLoggedInToChat) {
            return;
         }

         $.ajaxSetup ({ cache: false});
         $.ajax( {
            type: 'POST',
            url:  '/contactsbar/chat.php',
            data:
               {
                  'ChatAction' : 'GetOnlineUsers',
               },
            success: bind(this, onOnlineContactListLoaded)
            // TODO: error: bind(this, this.onChatUsersReceivedError),
         });
      },

      drawOnlineContactListCounter : function() {
         var text;

         if (this.isOnline()) {
            if (mnOnlineContacts == 1) {
               text = "" + mnOnlineContacts +
                  " <?=$trans['cb_contactsonlinecount_single']?>";
            } else {
               text =  "" + mnOnlineContacts +
                  " <?=$trans['cb_contactsonlinecount']?>";
            }
         } else {
            text = "<?=$trans['cb_contactsonlinecount_offline']?>";
         }

         $('#cb_contactsonlinecount').html(text);
      },

      /*
      * Draws a specific page of the online contact list.
      *
      * The contacts will be placed inside the element with id #cb_contacts_list which must
      * exist already. Its old content will be removed by this method.
      *
      * @param  page  The index of the page to draw. 1 is the first page.
       */
      drawOnlineContactList : function(page) {
         // Check inparameters
         if (undefined == page || page < 1) {
            page = 1;
         } else if (page > mnContactsPages) {
            page = mnContactsPages;
         }

         var $container =$('#cb_contacts_list');
         $container.empty();

         // If we have no contacts or if we're offline, don't draw.
         if (mnOnlineContacts == 0 || !this.isOnline()) {
            return;
         }


         // Fetch templates and template info.
         var $contactTemplate = $('#cb_contact_template > #cb_contact_').clone();
         var idPrefix = $contactTemplate.attr('id');
         var $contactColumnTemplate = $('#cb_contact_template > .cb_contacts_column');

         // Calculate index range for the contacts to display.
         var startContactIdx = (page-1) * COLUMNS_PER_PAGE * CONTACTS_PER_COLUMN;
         var lastContactIdx = page * COLUMNS_PER_PAGE * CONTACTS_PER_COLUMN - 1;
         lastContactIdx = Math.min(lastContactIdx, mnOnlineContacts - 1);
         lastContactIdx = Math.max(lastContactIdx, 0); // If we have no online contacts previous row will give us index -1.

         // Column to which we're currently adding contacts.
         var $contactColumn = null; 
         var columnNbr = 0;

         var contactIndex = 0; // TODO: Rename

         for (var i=startContactIdx; i <= lastContactIdx; i++) {
            var contact = mOnlineContacts[i];

            // Time to add new column?
            if (contactIndex % CONTACTS_PER_COLUMN == 0) {
               $contactColumn = $contactColumnTemplate.clone();
               $container.append($contactColumn);
               columnNbr++;
               if (columnNbr > COLUMNS_PER_PAGE) {
                  // Reached max columns. Don't show any more contacts.
                  return false; // break the loop 
               }
            }

            // Add contact to column and insert contact info.
            var $contact = $contactTemplate.clone(false);
            $contactColumn.append($contact);
            $contact.children('.cb_contact_name').html(contact.name);
            $contact.children('.cb_contact_company').html(contact.company);

            // Setting name and id in IE7 doesn't work well with new versions of jQuery
            if ($.browser.msie === true) {
               $contact.each(function() {
                  this.setAttribute('id', idPrefix + contact.id);
               });
            } else {
               $contact.attr('id', idPrefix + contact.id);
            }

            contactIndex++;
         }
      },

      /*
      * Gets the currently displayed contacts page.
      *
      * Since not all contacts might fit onto the screen at once, we sometimes
      * have to display them in separate pages. */
      getOnlineContactPage : function() {
         return mCurrentContactsPage;
      },

      /*
      * Returns the number of pages required to display all online contacts.
      *
      * This is a cached value. To get it updated call TODO: state method name. 
       */
      getNumberOfContactPages : function() {
         return mnContactsPages;
      },

      /*
      * Changes displayed page of contact list
       */
      setContactPage : function(page) {
         if (page < 1) {
            page = 1;
         } else if (page > this.getNumberOfContactPages()) {
            page = this.getNumberOfContactPages();
         }
         
         mCurrentContactsPage = page;
         this.drawOnlineContactList(mCurrentContactsPage);
      },

      /*
       * Checks if current user is online in chat.
       *
       * @returns  true  if user is online
       */
      isOnline : function() {
         return mIsLoggedInToChat;
      },

      /*
       * Set logged in user to online in chat.
       */
      goOnline : function() {
         $.ajax( {
            type: 'POST',
            url:  '/contactsbar/chat.php',
            data:
               {
                  'ChatAction' : 'SetUserOnline',
                  'User_Id' : <?=$_SESSION['User']['Id']?>,
                  'CheckSum' : '<?=CheckSum($_SESSION['User']['Id']);?>'
               },
            success: function()
               {
                  mIsLoggedInToChat = true;
                  contactsBar.loadAndDrawOnlineContactList();
                  contactsBar.startChatService(); 
               }
         });
      },


      /*
       * Set logged in user to offline in chat.
       */
      goOffline : function() {
         $.ajax( {
            type: 'POST',
            url:  '/contactsbar/chat.php',
            data:
               {
                  'ChatAction' : 'SetUserOffline',
                  'User_Id' : <?=$_SESSION['User']['Id']?>,
                  'CheckSum' : '<?=CheckSum($_SESSION['User']['Id']);?>'
               },
            success: bind(this, function()
               { 
                  mIsLoggedInToChat = false;
                  // Clear contact list so that we don't show old contacts
                  // when we go online again.
                  mnOnlineContacts = 0;
                  mOnlineContacts.length = 0;
                  this.drawOnlineContactListCounter();
                  this.drawOnlineContactList(contactsBar.getOnlineContactPage());
               })
         });
      },

      // ====== Messages ==========================
      
      /**
       * Retrieves the latest messages from the server and draws them in
       * the contactsbar.
       *
       * The retrieval is done in an asynchronous call so when this function
       * returns the draw has likely not been performed yet.
       */
      loadAndDrawMessageList : function() {
         //if (mOnlineContactListRefreshTimer != null) {
         //   clearTimeout(mOnlineContactListRefreshTimer);
         //}

         //mOnlineContactListRefreshTimer = setTimeout(
         //      bind(this, this.loadAndDrawOnlineContactList), CFG_REFRESH_CONTACT_LIST_DELAY);
         

         priside.messageManager.getLatestMessages({
                  'ajaxRequest' : 'getLatestMessages',
                  'userId'   : <?=$_SESSION['User']['Id']?>,
                  'checksum' : '<?=CheckSum($_SESSION['User']['Id']);?>',
                  'offset'   : 0,
                  'max'      : 2,
                  'success'   : onLatestMessagesLoaded
               });
      },

      /*
         * Increase number of unread messages counter with one.
         *
         * Updates the display.
       */
      increaseUnreadMessages : function()  {
         mnUnreadMessages++;
         this.updateMessageCountDisplay();
      },

      /*
         * Decreases number of unread messages counter.
         *
         * Updates the display.
         *
         * @param count Number of messages to decrease unread count with (integer).
         *              If not supplied will default to 1.
       */
      decreaseUnreadMessages : function(count) {
         if (undefined == count) {
            count = 1;
         }

         mnUnreadMessages -= count;
         if (mnUnreadMessages < 0) {
            mnUnreadMessages = 0;
         }
         this.updateMessageCountDisplay();
      },

      getNumberOfUnreadMessages : function() {
         return mnUnreadMessages;
      },

      /*
      * Updates the number/count of unread messages displayed in the header.
       */
      updateMessageCountDisplay : function() {
         var count = this.getNumberOfUnreadMessages();
         var text;
         if (count == 1) {
            text = "" + count +
               " <?=$trans['cb_msgcount_single']?>";
         } else {
            text =  "" + count +
               " <?=$trans['cb_msgcount']?>";
         }
         $('#cb_msgcount').html(text);
      },


      // ====== Chat ==========================

      startChatService : function() {
         if (mChatServiceStarted) {
            return; // Already started
         }

         mChatServiceStarted = true;
         contactsBar.checkForUnreadChatMessages();
      },

      /**
       * Callback when server returns chat/conversation id to us.
       */
      onChatIdReceived : function(data, response) {
         // TODO: Verify response for success.


         var id = data.Conversation_Id;
         var participants = data.User_Id_Array;

         // Store participant list
         if (undefined == mChatUsers[id]) {
            mChatUsers[id] = participants.slice(0); // Copy participants
         }

         // Inform each window of the conversation id.
         // Multiple windows could wait for the same id:
         //   1. User opens window for Contact A.
         //   2. User opens window for Contact B.
         //   3. User invites Contact B to first window.
         //   4. User invites Contact A to second window.
         for (var i = 0; i< mChatWindows.length; i++) {
            mChatWindows[i].onChatIdReceived(id, participants);
         }
      },


      /*
       * Gets the user ids for a chat/conversation.
       *
       * Might cause a calls to the server to get chat/conversation id.
       *
       * @param participants   Users in the chat (array of integers).
       * @returns  The id (integer) or null. If null then onChatIdReceived will be called
       *           for this Contactsbar and all ChatWindows when the id is returned from
       *           the server.
       */
      getChatId : function(participants) {
         // First check if we know the list already.

         // We don't know the list, ask server.
         $.getJSON( '/contactsbar/chat.php',
               {
                  'ChatAction' : 'CreateConversationId', 
                  'User_Id' : <?=$_SESSION['User']['Id']?>,
                  'User_Id_Array' : participants,
                  'CheckSum' : '<?=CheckSum($_SESSION['User']['Id']);?>'
               },
               bind(this, this.onChatIdReceived));
      },

      /*
       * Gets the users for a chat/conversation.
       *
       * Might cause a call to the server to get the list of users.
       *
       * @param   chatid     the chat/conversation for which we want a list of users
       * @param   callback   function to call when list of users is retrieved from server.
       *                     Only called if getChatUsers needs to call the server to get the list
       *                     of users. Should have signature function(id, users).
       *                     users is an array of {Id : string, Name : string}.
       *                     If callback is null, no call to the server will be made.
       * @returns An array of user ids (integers) or null.
       *          This will be a copy of the array so it can safely be modified if desired.
       *          If null then the list of users will be provided in a callback.
       */
      getChatUsers : function(chatid, callback) {
         if (mChatUsers[chatid] != undefined) {
            return mChatUsers[chatid].slice(0);
         }

         if (null == callback) {
            return;
         }

         mChatUsersRequestCallbacks.push([chatid, callback]);
         $.ajax( {
            type: 'POST',
            url:  '/contactsbar/chat.php',
            data:
               {
                  'ChatAction' : 'GetUsersInConversation',
                  'Conversation_Id' : chatid
               },
            success: bind(this, this.onChatUsersReceived),
            error: bind(this, this.onChatUsersReceivedError)
         });

         return null;
      },

      onChatUsersReceivedError : function(jqXHR, textStatus, errorThrown) {
         // TODO: We need to figure out which request went wrong and remove the
         //       corresponding callback from our callback list!
         alert('ERROR! Got back textStatus:\n' + textStatus + '\nerrorThrown:\n' + errorThrown);
      },

      /**
       * Callback when server returns chat/conversation users to us.
       */
      onChatUsersReceived : function(data) {
         // TODO: Verify response for success.
         data = $.parseJSON(data);

         var id = data.Conversation_Id;
         var participants = data.User_Array;

         // Call all registered callbacks
         var orgLen = mChatUsersRequestCallbacks.length;
         var nRemovedCallbacks = 0;
         // i will be index of an element before we started removing elements.
         // j will be index of the same element but taking removed elements into
         // consiteration.
         for (var i=0; i<orgLen - nRemovedCallbacks; i++) {
            var j = i - nRemovedCallbacks;
            if (mChatUsersRequestCallbacks[j][0] == id) {
               mChatUsersRequestCallbacks[j][1](id, participants); // Call the callback
               mChatUsersRequestCallbacks.splice(j, 1);
               nRemovedCallbacks++;
            }
         }

         // Store participant list
         if (undefined == mChatUsers[id]) {
            // Cache the chat user ids. We could also cache user names here.
            var userIds = new Array();
            for (var i=0; i<participants.length; i++) {
               userIds.push(participants[i].Id);
            }
            mChatUsers[id] = userIds;
         }
      },

      /*
      * Queries the database for the id of the last message we've read. 
      *
      */
      getLastReadChatMessageId : function() {
         $.ajax( {
            type: 'POST',
            url:  '/contactsbar/chat.php',
            data:
               {
                  'ChatAction' : 'GetLastReadMessageId',
                  'User_Id' : '<?=$_SESSION['User']['Id'];?>',
                  'CheckSum' : '<?=CheckSum($_SESSION['User']['Id']);?>'
               },
            success: bind(this, onGetLastReadChatMessageIdResponse)
            // TODO: error: bind(this, this.onChatUsersReceivedError),
         });
      },

      /*
      * Checks if user has any unread chat messages.
      *
      */
      checkForUnreadChatMessages : function() {
         $.ajax( {
            type: 'POST',
            url:  '/contactsbar/chat.php',
            data:
               {
                  'ChatAction' : 'GetUnreadMessages',
                  'User_Id' : '<?=$_SESSION['User']['Id'];?>',
                  'CheckSum' : '<?=CheckSum($_SESSION['User']['Id']);?>'
               },
            success: bind(this, onCheckForUnreadChatMessagesResponse)
            // TODO: error: bind(this, this.onChatUsersReceivedError),
         });
      },

      /*
      * Checks if user has received any new chat messages.
      *
      * Will also schedule another check.
      */
      checkForNewChatMessages : function() {
         if (null !=  mGetNewChatMessagesTimer) {
            clearTimeout(mGetNewChatMessagesTimer);
         }
         mGetNewChatMessagesTimer = setTimeout(bind(this, this.checkForNewChatMessages),
               CFG_REFRESH_CHAT_MESSAGE_DELAY[mChatDelayIndex]*1000);
         if (mChatDelayIndex < CFG_REFRESH_CHAT_MESSAGE_DELAY.length - 1) {
            mChatDelayIndex++;
         }

         // Don't check for messages if we're not logged in.
         if (!mIsLoggedInToChat) {
            return;
         }

         $.ajax( {
            type: 'POST',
            url:  '/contactsbar/chat.php',
            data:
               {
                  'ChatAction' : 'GetMessages',
                  'LastMessageId' : mLastChatMsg,
                  'User_Id' : '<?=$_SESSION['User']['Id'];?>',
                  'CheckSum' : '<?=CheckSum($_SESSION['User']['Id']);?>'
               },
            success: bind(this, onCheckForNewChatMessagesResponse)
            // TODO: error: bind(this, this.onChatUsersReceivedError),
         });
      },

      /*
       * Sends a chat message.
       *
       * @param conversationId   If of chat/conversation to send message to.
       * @param text             Text of the message.
       */
      sendChatMessage : function(msg) {
         // TODO: Add callback to see if send went ok.
         $.ajax( {
            type: 'POST',
            url:  '/contactsbar/chat.php',
            data:
               {
                  'ChatAction' : 'SendMessage',
                  'Conversation_Id' : msg.getChatId(),
                  'Message'	: msg.getText(),
                  'User_Id' : <?=$_SESSION['User']['Id']?>,
                  'CheckSum' : '<?=CheckSum($_SESSION['User']['Id']);?>'
               },
            success: function() {},
            error: bind(this, this.onSendChatMessageError(msg))  
         });

         // Increase chat message checking frequency.
         mChatDelayIndex = 0;
         this.checkForNewChatMessages();
      },

      /**
       * Called when sending a chat message failed.
       *
       * @param msg  ChatMessage that couldn't be sent.
       */
      onSendChatMessageError : function(msg) {
         // jQuery ajax requires a callback with three particular parameters
         // but we want to add some of our own, so we do this:
         return function(jqXHR, textStatus, errorThrown) {
            msg.setType(ChatMessage.Type.SEND_FAILED);
            handleNewChatMessage(msg);
            alert('textStatus: ' + textStatus + '\nerrorThrown: ' + errorThrown);
         }
      },


      /*
         * Call this to inform that user has read all chat messages from
         * a specific contact.
       */
      setAllChatMessagesFromUserRead : function(contactId) {
         $('#cb_contact_' + contactId).removeClass('cb_unread_message');
         this.setChatWindowHasNoUnreadMessage(this.getUserChatWindowId(contactId));
      },


      /*
      * Modifies chat window to indicate that there are unread messages.
      * TODO: This belongs in ChatWindow.
       */
      setChatWindowHasNoUnreadMessage : function(windowId) {
         $('#' + windowId +' .cb_chat_window_header').removeClass('cb_unread_message');
      },


      /*
      * Creates a chat window for chatting with specified user.
      *
      * @param  userid  database user identifier
       */
      createChatWindowForUser : function(userid) {
         // Check inparameters
         if (undefined == userid || userid < 0) {
            return;
         }
         
         // Check if we have a window open already.
         var chatWindow = null;
         for (var i = 0; i< mChatWindows.length; i++) {
            if(mChatWindows[i].isForUser(userid)) {
               // Yep, we already have one.
               chatWindow = mChatWindows[i];
               break;
            }
         }

         if (null == chatWindow) {
            chatWindow = new ChatWindow(<?=$_SESSION['User']['Id']?>, userid, null, this);
            chatWindow.addParticipant(userid);
            mChatWindows.push(chatWindow);
         } else {
            // Existing window might be minimized or closed and/or hidden. Restore it.
            chatWindow.restore();
            chatWindow.show();
         }
      },



      /*
      * Returns a string with the css id that a chat window initially opened
      * for supplied user should have.
      * TODO: Delete!
      */
      getUserChatWindowId : function(userId) {
         return 'cb_chatwin_u' + userId;
      },

   }; // End of public members
}


var contactsBar = ContactsBar(); // Instance to use to access the contacts bar.
//=============== End of ContactsBar =================================== 
