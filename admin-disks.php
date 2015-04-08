<?php

function getDisks(){
    if(php_uname('s')=='Windows NT'){
        // windows
        $disks=`fsutil fsinfo drives`;
        $disks=str_word_count($disks,1);
        if($disks[0]!='Drives')return '';
        unset($disks[0]);
        foreach($disks as $key=>$disk)$disks[$key]=$disk.':\\';
        return $disks;
    }else{
        // unix
        $data=`mount`;
        $data=explode(' ',$data);
        $disks=array();
        foreach ($data as $token) {
//		if(substr($token,0,5)=='/dev/') $disks[]=$token;
		if(substr($token,0,7)=='/media/') $disks[]=$token;
	}
        return $disks;
    }
} 



/* $disks = getDisks();

$counter = 0;
foreach($disks as $disk) {
	echo "<br/>$counter [" . $disk . "]";
	$counter++;
}
*/


?>
