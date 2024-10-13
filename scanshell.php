<?php
//session_start();
$file_scanned = 0;
$forbiden_function = ["eval","system","create_function","assert"];
if(isset($_GET['act'])){
	if($_GET['act']=="scan"){
		set_time_limit(60*10);
		scan_file(".");
	}else 
	if($_GET['act']=="ready"){
		die("<center style='height:45vh;'><a href='?act=scan'>Start Scan</a></center>");
	} 
}else{
	?>
	<center>
		<h1>M Antivirus Scanner</h1>
		<table>
			<tr>
				<td>File Scanned</td><td>:</td><td id="f_scan">0</td>
			</tr>
			<tr>
				<td>File Infected</td><td>:</td><td id="f_inf">0</td>
			</tr>
			<tr>
				<td>Scan Speed</td><td>:</td><td id="f_speed">0</td>
			</tr>
		</table>
	<iframe id="scanconsole" src="?act=ready" style='width:100%;height:70vh;'></iframe>
	</centeR>
	<script>
		let total;
		setInterval(function () {
			
			let frm = (document.getElementById('scanconsole').contentWindow || document.getElementById('scanconsole').contentDocument);
			let scanned = frm.document.querySelectorAll('span').length;
			document.getElementById("f_scan").innerHTML= scanned;
			document.getElementById("f_inf").innerHTML= frm.document.querySelectorAll('.infected').length;
			//if(scanned>0)update(scanned,total);
			document.getElementById("f_speed").innerHTML =  `${(scanned-total)} File/Sec`;
			total = scanned;
		}, 1024);
		function update(scanned,total){	
			let nows = scanned;
			let upspeed = (1000/(total-scanned))
			let to = (total-scanned);
			let pluss = (upspeed<10)?(to/100):1;
			let hh = setInterval(function () {
				if(nows >= total){		
					clearInterval(hh);
				}
				nows+=pluss;
				document.getElementById("f_scan").innerHTML =  `${parseInt(nows)} File(s)`;
			},(upspeed<10)?10:upspeed);
			console.log((upspeed<10)?10:upspeed);
			
		}
	</script>
	<?php
}
function scan_file($path){
		global $forbiden_function;
		global $file_scanned;
		$files = scandir($path);
		$files = array_diff(scandir($path), array('.', '..'));
		foreach($files as $file){
			if (is_dir("$path/$file")) {
				scan_file("$path/$file");
			}else{
				$c = filecheck("$path/$file");
				$file_scanned+=1;
				
				//if($c)
				//$_SESSION["scanned"] = $file_scanned;
				//$_SESSION["detected"] = $path/$file;
				echo "<u>$path/$file</u> is ".($c?'<span class="infected" style="color:red;">Shell</span>':'<span class="hehe"  style="color:green;">safe</span>')."<br>\n";
				//if($c==true) die();
			
			}
		}
	}
	function filecheck($path){
		global $forbiden_function;
		if(!file_exists($path)) return;
		$handle = fopen($path, 'r');
		if($handle==null) return;
		$isphp = preg_match("/(\.php|\.phtml)/i", basename($path))?1:0;
		$valid = 0; // init as false
		$heuristic = 0;
		$heuristic_adv1= 0;
		if($isphp==0) return false;
		while (($buffer = fgets($handle)) !== false) {
			if(preg_match("/(\<\?php)/i", $buffer)){
				$isphp+=1;
			}
			if($isphp>1){
				if(preg_match("/(".implode("|",$forbiden_function).")/i", $buffer)){
					if(substr( trim($buffer), 0, 2 ) != "\\")
					$valid+=1;
					if($valid>0 && $isphp>1)break; // Once you find the string, you should break out the loop.
				}
			}
		}
		fclose($handle);
		if((filesize($path) < 1024 * 1024 * 10) && $isphp>1){
			$data = file_get_contents($path);
			$heuristic_adv1= has_bypass_function_concat($data);
			
			$h = is_virus($data);
			$heuristic = $h['point'];
			if((filesize($path)*(0.1/100))> count(explode("\n",$data))){
				$valid +=(($heuristic_adv1>1)?3:$heuristic_adv1);
			}
			$valid += $heuristic;
		}
		
		//echo "ret:$valid,$isphp,$heuristic,$heuristic_adv1		| ";
		return ($isphp>1 && ($valid>2));
	}
	function is_virus($data){
		global $forbiden_function;
		$check =0;
		$reason =[];
		foreach($forbiden_function as $fc){
			if(!preg_match("/(".$fc.")/i", $data))continue;
			$ehem = get_string_between($data,"$fc","(");
			if(($ehem=="MBOH")) continue;
			if(trim($ehem)=="") {
				$check+=1;
			}
		}
		$d = tag_contents($data,"/*","*/");
		foreach((($d!=null)?$d:[]) as $dat){
			$ehem = get_string_between($dat,"$fc","(");
			if(trim($ehem)=="") $check-=1;
		}
		return ["point"=>$check,'reason'=>$reason];
	}
	function has_bypass_function_concat($data){
		global $forbiden_function;
		$check =0;
		$weird =0;
		$cleardata = $data;
		foreach ([".",","] as $h){
			foreach ([" $h ","$h","$h "," $h"] as $val1){
				$weird += substr_count($data,"'$val1'");
				$weird += substr_count($data,"\"$val1\"");
				$cleardata = str_replace("'$val1'","",$cleardata);
				$cleardata = str_replace("\"$val1\"","",$cleardata);
			}
		}
		
		if(preg_match("/(".implode("|",$forbiden_function).")/i", $cleardata)) $check+=1;
		return $check+(($weird>40)?1:0);
	}

	function get_string_between($string, $start, $end){
		$string = ' ' . $string;
		$ini = strpos($string, $start);
		if ($ini == 0) return 'MBOH';
		$ini += strlen($start);
		$len = strpos($string, $end, $ini) - $ini;
		return substr($string, $ini, $len);
	}
	function get_string_between_all($string, $start, $end) {
		$start = ($start);
		$end = ($end);
		$pattern = "~$start\s*(.*?)$end\s*~";
		$match = preg_match_all($pattern, $string, $matches);
		if ($match) {
			return $matches[1];
		}
	}
	function tag_contents($string, $tag_open, $tag_close){
	   $result = [];
	   foreach (explode($tag_open, $string) as $key => $value) {
		   if(strpos($value, $tag_close) !== FALSE){
				$result[] = substr($value, 0, strpos($value, $tag_close));;
		   }
	   }
	   return $result;
	}
?>

