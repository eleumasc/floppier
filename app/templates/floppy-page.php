<?php include('header.php'); ?>
<section class="topic">
  <section class="nes-container with-title">
    <h2 class="title">Upload</h2>
    <form action="upload.php" method="post" enctype="multipart/form-data">
      <div class="item">
        <div class="nes-field">
          <input type="file" id="uploaded-file" class="nes-input" name="uploaded-file">
        </div>
        <input type="submit" class="nes-btn" name="upload" value="Upload">
      </div>
    </form>
  </section>
</section>
<section class="topic">
  <section class="nes-container with-title">
    <h2 class="title">Your files</h2>
    <div class="item">
<?php if (count($files) > 0): ?>
      <div class="nes-table-responsive">
        <table class="nes-table is-bordered">
        <thead>
          <tr>
            <th>Name</th>
            <th>Size</th>
            <th>Actions</th>
          </tr>
        </thead>
        <tbody>
<?php foreach ($files as $file): ?>
          <tr>
            <td><?= $file['name'] ?></td>
            <td><?= $file['size'] ?></td>
            <td>
              <form action="delete.php" method="post">
                <a href="download.php?fileid=<?= $file['id']?>" class="nes-btn">Download</a>
                <input type="hidden" name="fileid" value="<?= $file['id'] ?>">
                <input type="submit" class="nes-btn is-error" name="delete" value="Delete">
              </form>
            </td>
          </tr>
<?php endforeach; ?>
        </tbody>
        </table>
      </div>
<?php else: ?>
      <p>Your floppy is empty.</p>
<?php endif; ?>
    </div>
  </section>
</section>
<section class="topic">
  <section class="nes-container with-title">
    <h2 class="title">Used space</h2>
    <div class="item">
      <progress class="nes-progress is-primary" value="<?= $used_space ?>" max="<?= FLOPPY_DISK_CAPACITY ?>"></progress>
      <span><?= floor($used_space * 100 / FLOPPY_DISK_CAPACITY) ?>%</span>
    </div>
  </section>
</section>
<section class="topic">
  <section class="nes-container with-title">
    <h2 class="title"><div class="nes-badge"><span class="is-warning">BETA</span></div> Download file list</h2>
    <div class="item">
      <p>This advanced feature allows you to download the list of your files.</p>
      <a href="list.php?userid=<?= $_SESSION['user']['id'] ?>" class="nes-btn">Download</a>
    </div>
  </section>
</section>
<?php include('footer.php'); ?>
