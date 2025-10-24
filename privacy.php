<?php session_start();
$page_name = basename($_SERVER['PHP_SELF']);
$page_title = "Privacy Policy";
$page_header = "456a403eab.jpg";
include("header.php"); ?>
<title><?php echo $company_name; ?> - <?php echo $page_title; ?></title>
<?php include("page_header.php"); ?>



    <div class="container py-4">
  <h4 class="mb-3">Privacy Policy</h4>

  <p class="mb-4">
    This Privacy Policy outlines how we collect, use, disclose, and safeguard your information when you visit our website. We are committed to protecting your personal data and your right to privacy.
  </p>

  <h5 class="mb-3">Information We Collect</h5>
  <p class="mb-3">
    We may collect personal information that you voluntarily provide to us when you register on the site, fill out a form, or contact us. This may include your name, email address, phone number, and any other information you choose to provide.
  </p>
  <p class="mb-4">
    We also automatically collect certain information about your device and browsing activity using cookies and similar technologies.
  </p>

  <h5 class="mb-3">How We Use Your Information</h5>
  <p class="mb-3">
    We use the information we collect to:
  </p>
  <ul class="mb-4">
    <li>Improve user experience and site functionality</li>
    <li>Respond to inquiries and provide customer support</li>
    <li>Send important updates or promotional content</li>
    <li>Ensure compliance with legal obligations</li>
  </ul>

  <h5 class="mb-3">Sharing of Information</h5>
  <p class="mb-4">
    We do not sell or rent your personal data to third parties. However, we may share information with trusted service providers who assist us in operating the website or conducting business, as long as they agree to keep this information confidential.
  </p>

  <h5 class="mb-3">Data Security</h5>
  <p class="mb-4">
    We implement appropriate technical and organizational measures to protect your personal data from unauthorized access, disclosure, alteration, or destruction.
  </p>

  <h5 class="mb-3">Your Rights</h5>
  <p class="mb-3">
    You have the right to access, correct, or delete your personal information. You may also object to or restrict certain types of data processing.
  </p>
  <p class="mb-4">
    To exercise these rights, please contact us using the details provided at the end of this policy.
  </p>

  <h5 class="mb-3">Changes to This Policy</h5>
  <p class="mb-4">
    We may update this Privacy Policy from time to time. Any changes will be posted on this page with an updated revision date. We encourage you to review it regularly.
  </p>

  <h5 class="mb-3">Contact Us</h5>
  <p>
    If you have any questions or concerns about this Privacy Policy, please contact us at: <br>
    <strong>Email:</strong> <?= $company_email ?>
  </p>
</div>


    

<?php include("footer.php"); ?>