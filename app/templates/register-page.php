<?php include('header.php'); ?>
<section class="topic">
  <section class="nes-container with-title">
    <h2 class="title">Register</h2>
    <form action="register.php" method="post">
      <div class="item">
        <div class="nes-field">
          <label for="username">Username</label>
          <input type="text" id="username" class="nes-input" name="username">
        </div>
        <div class="nes-field">
          <label for="password">Password</label>
          <input type="password" id="password" class="nes-input" name="password">
        </div>
        <div class="nes-field">
          <label for="confirm-password">Confirm password</label>
          <input type="password" id="confirm-password" class="nes-input" name="confirm-password">
        </div>
        <input type="submit" class="nes-btn" name="register" value="Register">
      </div>
    </form>
  </section>
</section>
<?php include('footer.php'); ?>
