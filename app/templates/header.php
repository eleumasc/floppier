<!DOCTYPE html>
<html>
<head>
  <title>Floppier</title>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <link rel="stylesheet" href="assets/css/nes.min.css">
  <link rel="stylesheet" href="assets/css/style.css">
</head>
<body>
  <div id="nescss">
    <header>
      <div class="container">
        <div class="nav-brand">
          <a href="index.php"><h1><div class="brand-logo"></div>Floppier</h1></a>
          <p>90's style storage on the Internet</p>
        </div>
      </div>
    </header>
    <div class="container">
      <main class="main-content">
<?php while ($flash = next_flash_message()): ?>
        <section class="topic">
          <section class="nes-container with-title">
            <h3 class="title">Message</h3>
            <div class="item">
              <span class="nes-text is-<?= $flash['type'] ?>"><?= htmlentities($flash['text']) ?></span>
            </div>
          </section>
        </section>
<?php endwhile; ?>
