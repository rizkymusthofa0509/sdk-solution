<?php 

$IP     = '192.168.5.221'; //Masukan IP Mesin
$ComKey = '0'; //Masukan ComKey

	$Connect = fsockopen($IP, "80", $errno, $errstr, 1);
	if($Connect){
		$soap_request='<GetAttLog><ArgComKey xsi:type=\"xsd:integer\">'.$ComKey.'</ArgComKey><Arg><PIN xsi:type=\"xsd:integer\">All</PIN></Arg></GetAttLog>';
		$newLine="\r\n";
		fputs($Connect, "POST /iWsService HTTP/1.0".$newLine);
	    fputs($Connect, "Content-Type: text/xml".$newLine);
	    fputs($Connect, "Content-Length: ".strlen($soap_request).$newLine.$newLine);
	    fputs($Connect, $soap_request.$newLine);
		$buffer="";
		while($Response=fgets($Connect, 1024)){
			$buffer=$buffer.$Response;
		}
	}else echo "Koneksi Gagal";
	
	function Parse_Data($data,$p1,$p2){
		$data=" ".$data;
		$hasil="";
		$awal=strpos($data,$p1);
		if($awal!=""){
			$akhir=strpos(strstr($data,$p1),$p2);
			if($akhir!=""){
				$hasil=substr($data,$awal+strlen($p1),$akhir-strlen($p1));
			}
		}
		return $hasil;	
	}

	$buffer=Parse_Data($buffer,"<GetAttLogResponse>","</GetAttLogResponse>");
	$buffer=explode("\r\n",$buffer);
	$no=1;

	$test['status']  = 200;
	$test['message'] = 'Success';
	$test['data']    = array();

	$no = 1;
	if (count($buffer) > 0){
		for ($a=0;$a<count($buffer);$a++) { 
			$data=Parse_Data($buffer[$a],"<Row>","</Row>");
			$res['id']        = $no++;
			$res['pin']       = Parse_Data($data,"<PIN>","</PIN>");
			$res['waktu']  = Parse_Data($data,"<DateTime>","</DateTime>");
			$res['verified']  = Parse_Data($data,"<Verified>","</Verified>");
			$res['status']    = Parse_Data($data,"<Status>","</Status>");

			array_push($test['data'],$res);
		}
	}else{
		$test['status']  = 404;
		$test['message'] = 'Failed';
		$test['data']    = array();
	}

	

	echo (json_encode($test));

	?>
