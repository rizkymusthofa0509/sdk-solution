<?php
	$get = json_decode(file_get_contents('http://localhost/absensi/json_crone.php'));
	if ($get->status == 200){
		foreach ($get->data as $data){
			if ($data->pin!=''){
				echo $data->id."<br>";
				echo $data->pin."<br>";
				echo $data->waktu."<br>";
				echo $data->verified."<br>";
				echo $data->status."<br>";
				echo "========================<br>";
			}
		}
	}
?>
