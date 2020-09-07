<?php include('header.php'); ?>
<section class="topic">
  <div class="item">
    <p>Have you ever felt nostalgic for old, mechanical storage systems?</p>
    <p>Welcome to Floppier, the 3.5-inches online storage service, because 1.44MB are never too few!</p>
<?php if (!isset($_SESSION['user'])): ?>
    <a class="nes-btn is-primary" href="login.php">Login</a>
    <a class="nes-btn" href="register.php">Register</a>
<?php endif; ?>
  </div>
</section>
<?php include('footer.php'); ?>
