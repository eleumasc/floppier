<?php if (isset($_SESSION['user'])): ?>
        <section class="topic">
          <section class="nes-container is-rounded">
            <div class="item">
              <p>You're logged in as <?= $_SESSION['user']['username'] ?></p>
              <form action="logout.php" method="post">
                <a href="floppy.php" class="nes-btn is-primary">Your floppy</a>
                <input type="submit" class="nes-btn" name="logout" value="Logout">
              </form>
            </div>
          </section>
        </section>
<?php endif; ?>
        <footer>
          <p>Copyright &copy; 2020 Madales. All rights reserved.</p>
        </footer>
      </main>
    </div>
  </div>
</body>
</html>
