<?php
header("HTTP/1.0 503 Service Unavailable");
header("Retry-After: 120");
?><!DOCTYPE html>
<html>
  <head>
    <title>GovHack: 503 Maintenance</title>
    <style type="text/css">
    body { padding: 0; margin: 0; height: 100vh; text-align: center; display: flex; align-items: center; justify-content: center; }
    .background { height: 100vh; opacity: .2; background: url('http://assets.govhack.org/img/logo/govhack-original-transp.png') center center / 200px 200px repeat fixed transparent; }
    .logo { display: inline-block; opacity: .3; max-height: 200px; }
    .message { font: italic bold 22px/22px sans-serif; color: #bbb; }
    </style>
  </head>
  <body>
    <div class="message">
        <img class="logo" src="http://assets.govhack.org/img/logo/govhack-original-transp.png" alt="GovHack"/>
        <div>503 Down For Maintenance</div>
        <div>#GovHackAU #BRB</div>
        <div>We are just performing some upgrades/maintenance and will be back very shortly.</div>
    </div>
  </body>
</html>
