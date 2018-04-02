# boomerang-client-builder
PHP CLI builder for Boomerang JS client. Bundles and minifies a JS package of Boomerang JS and list of Boomerang JS plugins.

Usage: `php boomerang-build.php https://www.mydomain.com/boomerang/catcher.php`

This builder prepares everything needed to start using Boomerang JS and deploy it to your visitors browsers.

**boomerang-build.php** accepts one parameter and this parameter specifies the address where collected beacon data is being send/catched.

**How to catch Boomerang JS beacon:**
 * **PHP based catcher:** https://github.com/revampix/boomerang-beacon-catcher
 * **Node.js based catcher:** https://github.com/springernature/boomcatch

**How to load Boomergan JS in client browser:**
You need to include the script below and modify it in order to start loading your Boomerang JS build.
You need to change "https://www.absolute-path-to-build-result.com/boomerang.js" to url where you host your Boomerang JS build result.
```
<script>
(function(){
  var dom,doc,where,iframe = document.createElement('iframe');
  iframe.src = "javascript:void(0)";
  (iframe.frameElement || iframe).style.cssText = "width: 0; height: 0; border: 0";
  var where = document.getElementsByTagName('script')[0];
  where.parentNode.insertBefore(iframe, where);

  try {
    doc = iframe.contentWindow.document;
  } catch(e) {
    dom = document.domain;
    iframe.src="javascript:var d=document.open();d.domain='"+dom+"';void(0);";
    doc = iframe.contentWindow.document;
  }
  doc.open()._l = function() {
    var js = this.createElement("script");
    if(dom) this.domain = dom;
    js.id = "boomr-if-as";
    js.src = 'https://www.absolute-path-to-build-result.com/boomerang.js';
    this.body.appendChild(js);
  };
var gtmWorkaround = 'bo'  + 'dy';
  doc.write('<' + gtmWorkaround + ' onload="document._l();">');
  doc.close();
})();
</script>
```
*More info: https://github.com/SOASTA/boomerang*

**Currently the client builder includes:**

 * boomerang.js
 * google-analytics-customised.js
 * guid-customised.js
 * mobile.js
 * navtiming.js
 * restiming.js
 
**For basic ussage it is enough to include only:**

 * boomerang.js
 * navtiming.js
 * restiming.js
 
 
