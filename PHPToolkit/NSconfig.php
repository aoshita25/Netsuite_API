<?php
define("NS_ENDPOINT", "2022_1");
define("NS_HOST", "https://7275554-sb1.suitetalk.api.netsuite.com"); // Web Services URL - The service URL can be found in Setup -> Company -> Company Information -> Company URLs under SUITETALK (SOAP AND REST WEB SERVICES). E.g. https://ACCOUNT_ID.suitetalk.api.netsuite.com
define("NS_ACCOUNT", "7275554_SB1");

// Token Based Authentication data
define("NS_CONSUMER_KEY", "c4944dd45716d9ed51f0508010a3e8608a9ad47ccb6faf0c7c95b7b79b55b0a9"); // Consumer Key shown once on Integration detail page
define("NS_CONSUMER_SECRET", "560ea9e54e30b98d396e30c9726e87cf0605996b0cf7be1664d2eba0a1c1acde"); // Consumer Secret shown once on Integration detail page
// following token has to be for role having those permissions: Log in using Access Tokens, Web Services
define("NS_TOKEN", "60b7f6613f451a49153ab2ae567ce8021602639959c284b57f9b3206ea734282"); // Token Id shown once on Access Token detail page
define("NS_TOKEN_SECRET", "2eba1fb419846070d3ae4885e7635fedc12db5136c9d3f36b5b733293a1d9377"); // Token Secret shown once on Access Token detail page
?>