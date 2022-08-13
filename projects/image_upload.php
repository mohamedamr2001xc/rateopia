<?php

require_once 'includes/header.php';
require_once 'includes/footer.php';

?>

<?php
if(isset($_GET['error']))
{
  echo $_GET['error'];
}
?>
<form action="includes/upload.php" method="post" enctype="multipart/form-data">
<input class="submitButton"style="float:left;"  type="file" name="my_image" id="fileToUpload">
  <input class="submitButton"style="float:right;" type="submit" value="Upload Image" name="submit">
</form>
