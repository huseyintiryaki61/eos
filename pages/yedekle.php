<?php
require 'class.php';
$backup = new Backup();
date_default_timezone_set('Europe/Istanbul');
if(isset($_POST["veri"])){
	$tarih=date("d-m-Y-H-i-s");
		$mysqlBackup = $backup->mysql([
    'host' => 'localhost',
    'user' => 'root',
    'pass' => 'emintiryaki61',
    'dbname' => 'eos',
    'file' =>   '../yedek/'.$tarih.'.sql'
]);
if ($mysqlBackup){
   $data['status']="success";
$data['message']="Veri Yedeklemesi Oldu";
echo json_encode($data);
}else{
	$data['status']="error";
$data['message']="Veri Yedeklenemedi";
echo json_encode($data);
}
				}
				
				
if(isset($_POST["klasor"])){
	$tarih=date("d-m-Y-H-i-s");
				$folderBackup = $backup->folder([
    'dir' => '../../eos',
    'file' => '../yedek/'.$tarih.'.zip',
    'exclude' => ['.idea', 'upload'] // bunlar hariç yedekle
]);
if ($folderBackup){
  $data['status']="success";
$data['message']="Klasor Yedeklemesi başarılı";
echo json_encode($data);
}else{
	$data['status']="error";
$data['message']="Klasor Yedeklenemedi";
echo json_encode($data);
}
	
}
if(isset($_POST["komple"])){
	$tarih=date("d-m-Y-H-i-s");
				$backup = new Backup([
    'db' => [
        'host' => 'localhost',
        'user' => 'root',
        'pass' => 'emintiryaki61',
        'dbname' => 'eos',
        'file' => '../yedek/'.$tarih.'.sql'
    ],
    'folder' => [
        'dir' => '../../eos',
       'file' => '../yedek/'.$tarih.'.zip',
        'exclude' => ['.idea', 'upload'] // bunlar hariç yedekle
    ]
]);
$yedekle = $backup->full();
if ($yedekle){
  $data['status']="success";
$data['message']="Sistem Yedeklemesi başarılı";
echo json_encode($data);
exit;

}else{
	$data['status']="error";
$data['message']="Sistem Yedeklenemedi";
echo json_encode($data);
exit;
}	
				}
				

