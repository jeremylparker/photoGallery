
   window.location.querystring = (function() {
 
    // by Chris O'Brien, prettycode.org
 
    var collection = {};
 
    // Gets the query string, starts with '?'
 
    var querystring = window.location.search;
 
    // Empty if no query string
 
    if (!querystring) {
        return { toString: function() { return ""; } };
    }
 
    // Decode query string and remove '?'
 
    querystring = decodeURI(querystring.substring(1));
 
   // Load the key/values of the return collection
 
    var pairs = querystring.split("&");
 
    for (var i = 0; i < pairs.length; i++) {
 
        // Empty pair (e.g. ?key=val&&key2=val2)
 
        if (!pairs[i]) {
            continue;
        }
 
        // Don't use split("=") in case value has "=" in it
 
        var seperatorPosition = pairs[i].indexOf("=");
 
        if (seperatorPosition == -1) {
            collection[pairs[i]] = "";
        }
        else {
            collection[pairs[i].substring(0, seperatorPosition)] 
                = pairs[i].substr(seperatorPosition + 1);
        }
    }
 
    // toString() returns the key/value pairs concatenated
 
    collection.toString = function() {
        return "?" + querystring;
    };
 
    return collection;
})();

   document.onkeydown = HandleEvent;

   function HandleEvent( event )
   {
      if ( window.event ) event = window.event;

      var eventCode = event.keyCode;
      var newLink = "../index.html";
 
      // alert( eventCode );

      if ( eventCode == 72 ) 			        		// H key
      {
         window.location = newLink;
      }
      else if ( eventCode == 39 || eventCode == 32 )         		// right arrow key or spacebar
      {
         newLink = get_next_link();
         window.location = newLink;
      }
      else if ( eventCode == 37 || eventCode == 8 )     		// left arrow key or backspace
      {
         newLink = get_prev_link();
         window.location = newLink;
      }
   }

   function get_current_idx()
   {
	  var querystring = window.location.querystring;
      var current_idx = parseInt(querystring["i"]);

	  return current_idx;
   }

   function get_prev_link() 
   {
      var str = document.location.href;
      var current_idx = get_current_idx();
 
      var pageVal = current_idx - 1;
      if ( pageVal < 1 )
		  return str;

	  var first_idx = str.indexOf('=');

	  var second_idx = str.indexOf('&');

	  var prevLink = str.substring(0,first_idx+1) + pageVal + str.substr(second_idx);

      return prevLink;
   }

   function get_next_link() 
   {
      var current_idx = get_current_idx();
 
      var pageVal = current_idx + 1;

	  // calculate our nextLink
      var str = document.location.href;
	  
	  var first_idx = str.indexOf('=');

	  var second_idx = str.indexOf('&');

	  var nextLink = str.substring(0,first_idx+1) + pageVal + str.substr(second_idx);

      return nextLink;
   }
