var browser;

var scrayp = {
  pageLoaded: function(e){
    dumpr("loaded");

    document.getElementById("urlbar").value = browser.contentWindow.document.location.href;

    if (typeof scrayp.defs != "object")
      return;
    
    //var sandbox = new Components.utils.Sandbox(document.defaultView);
    var sandbox = scrayp.createSandbox();
    
    //Components.utils.evalInSandbox(loadjQuery(), sandbox);    
    Components.utils.evalInSandbox(getContent("chrome://scrayp/content/date.js"), sandbox);
    Components.utils.evalInSandbox(getContent("chrome://scrayp/content/inline.js"), sandbox);
    
    Components.utils.evalInSandbox(dumpr.toSource(), sandbox);
    Components.utils.evalInSandbox(odump.toSource(), sandbox);
    
    var result = Components.utils.evalInSandbox("scrayp.scrape(" + JSON.stringify(scrayp.defs) + ");", sandbox);

    dumpr("Finished scraping");
    
    var data;
    if (typeof result == "object")
      data = JSON.stringify(result);

    listen.send(data);
  },
  
  createSandbox: function(){
    var safeWindow = new XPCNativeWrapper(browser.contentWindow); 
    var sandbox = new Components.utils.Sandbox(safeWindow);
    
    sandbox.window = safeWindow;
    sandbox.document = sandbox.window.document;
    sandbox.navigator = sandbox.window.navigator;
    sandbox.document = sandbox.window.document;

    // from http://giantrobots.thoughtbot.com/2007/10/2/using-jester-from-inside-a-xpcom-component
    //sandbox.Element = sandbox.window.Element;
    //sandbox.Object = sandbox.window.Object;
    //sandbox.Number = sandbox.window.Number;
    //sandbox.String = sandbox.window.String;
    //sandbox.Enumerable = sandbox.window.Enumerable;
    //sandbox.Array = sandbox.window.Array;
    //sandbox.Hash = sandbox.window.Hash;
    //sandbox.HTMLElement = sandbox.window.HTMLElement;
    //sandbox.Event = sandbox.window.Event;
    //sandbox.XPathResult = sandbox.window.XPathResult;
    //sandbox.DOMParser = sandbox.window.DOMParser;

    sandbox.uri = sandbox.document.location.href;

    sandbox.getWindowObject = function(name) { return safeWindow.wrappedJSObject[name]; };
    sandbox.__proto__ = safeWindow;
    
    return sandbox;
  },


  windowLoaded: function() {
    browser = document.getElementById("browser");
    
    browser.removeEventListener("DOMContentLoaded", scrayp.pageLoaded, false);
    browser.addEventListener("DOMContentLoaded", scrayp.pageLoaded, false);
    
    listen.startListening();
  },

  windowUnloaded: function() {
    serverSocket.close();
    //browser.removeEventListener("load", scrayp.pageLoaded, false);
  },

};

window.addEventListener("load", scrayp.windowLoaded, false);
window.addEventListener("unload", scrayp.windowUnloaded, false);
