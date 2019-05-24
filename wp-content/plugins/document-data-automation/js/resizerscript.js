// Resizer
iFrameResize({
    log                     : false,                  // Enable console logging
    minHeight 		    : 500,
    enablePublicMethods     : true,                  // Enable methods within iframe hosted page
    resizedCallback         : function(messageData){ // Callback fn when resize is received
        //console.log("resizeCallback: "+messageData);
    },
    messageCallback         : function(messageData){ // Callback fn when message is received

        alert(messageData.message);
    },
    closedCallback         : function(id){ // Callback fn when iFrame is closed
        console.log("closedCallback");

    }
});