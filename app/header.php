<?php
  // Only connect to this site via HTTPS for the two years
  header("Strict-Transport-Security: max-age=63072000");
  // Only load resources from same origin and disable framing
  header("Content-Security-Policy: default-src 'self'; frame-ancestors 'none'");
  // Prevent browsers from incorrectly detecting non-scripts as scripts
  header("X-Content-Type-Options: nosniff");
?>
