<?php session_start();
$page_name = basename($_SERVER['PHP_SELF']);
$page_title = "Terms of Service";
$page_header = "456a403eab.jpg";
include("header.php"); ?>
<title><?php echo $company_name; ?> - <?php echo $page_title; ?></title>
<?php include("page_header.php"); ?>
<div class="container py-5">
  <h1 class="mb-4 text-center text-primary">Terms and Conditions</h1>
  <p class="text-muted text-center">Last updated: July 22, 2003</p>

  <div class="mt-5">
    <h4>1. Introduction</h4>
    <p>Welcome to our website. By accessing or using our services, you agree to comply with and be bound by the following terms and conditions. Please read them carefully.</p>
  </div>

  <div class="mt-4">
    <h4>2. Use of Our Services</h4>
    <p>You agree to use our website and services only for lawful purposes and in accordance with these terms. You must not misuse the website or interfere with its security or functionality.</p>
  </div>

  <div class="mt-4">
    <h4>3. Intellectual Property Rights</h4>
    <p>All content on this website, including text, graphics, logos, and images, is the property of our company or its content suppliers and is protected by applicable copyright and intellectual property laws. You may not reproduce, distribute, or exploit any content without written permission.</p>
  </div>

  <div class="mt-4">
    <h4>4. Service Availability</h4>
    <p>We strive to ensure uninterrupted access to our services, but we do not guarantee that the website or any service will be available at all times. Scheduled maintenance or unforeseen issues may cause temporary outages.</p>
  </div>

  <div class="mt-4">
    <h4>5. Limitation of Liability</h4>
    <p>We are not liable for any indirect, incidental, or consequential damages resulting from the use or inability to use our website or services, even if we were advised of the possibility of such damages.</p>
  </div>

  <div class="mt-4">
    <h4>6. Third-Party Links</h4>
    <p>Our website may contain links to third-party websites or services that are not owned or controlled by us. We are not responsible for the content, policies, or practices of any third-party sites.</p>
  </div>

  <div class="mt-4">
    <h4>7. Privacy Policy</h4>
    <p>Your use of this website is also governed by our <a href="privacy">Privacy Policy</a>. Please review it to understand how we collect, use, and protect your personal data.</p>
  </div>

  <div class="mt-4">
    <h4>8. Governing Law</h4>
    <p>These Terms and Conditions are governed by and construed in accordance with the laws of these United States of America, and you agree to submit to the exclusive jurisdiction of its courts.</p>
  </div>

  <div class="mt-4">
    <h4>9. Changes to Terms</h4>
    <p>We reserve the right to modify these terms at any time. Changes will be posted on this page with an updated revision date. Your continued use of the site after such changes indicates your acceptance of the new terms.</p>
  </div>

  <div class="mt-4">
    <h4>10. Contact Us</h4>
    <p>If you have any questions about these Terms and Conditions, please contact us at <a href=""><?= $company_email ?></a>.</p>
  </div>
</div>

<?php include("footer.php"); ?>