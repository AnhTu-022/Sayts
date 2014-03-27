<?php include "header.php" ?>

<div class='title flex_centered'>CSV Importer</div>

<div id="loginBox">
    <form action="index.php?action=csvimport" method="POST" enctype="multipart/form-data"/>
    <div id="loginFields" class='flex_vert'>
        <?php if (isset($PAGEDATA["failed"])) { ?>
            <span class="errorMessage">Import failed.</span>
        <?php }
        if (isset($PAGEDATA["success"])) {
            ?>
            <span class="errorMessage">File successfully imported.</span>
        <?php } ?>
        <input type="file" id="csvfile" name="csvfile" required />
        <input type="text" id="name" name="name" placeholder="Name" required />
        <input type="url" id="originUrl" name="originUrl" placeholder="Url" />
        <input type="file" id="icon" name="icon" />
        <textarea id="description" name="description" rows="5" columns="75"></textarea>
        <!--<input type="text" id="username" name="username" placeholder="Username" required autofocus />
        <input type="password" id="password" name="password" placeholder="Passwort" required />-->
        <input type="submit" id="upload" name="upload" value="Upload" />

    </div>
</form>
</div>

<?php include "footer.php" ?>
