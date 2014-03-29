<?php
	
	///settings
	
	$_SET = array();
	
	include 'Variables.php';

	/////////////////////////////////////////////
	
	//make cache dir of not exist
	if(!file_exists(CACHEDIR)){mkdir(CACHEDIR);}
	
	//set the root
	$ROOT = str_replace($_SERVER['DOCUMENT_ROOT'],'',preg_replace('/\/[^\/]+$/','',$_SERVER['SCRIPT_FILENAME']));
	$ROOT = preg_replace('/\/+/','/','/'.$ROOT.'/');
	
	
	// see the path
	$path = cleanPath(explode('/',preg_replace('`^'.preg_quote($ROOT).'`','',rawurldecode($_SERVER['REQUEST_URI']))));	
	if(!sizeof($path)){
		send404('Bad syntax: you requested nothing');
	}
	
	//if maintenance
	if($path[0]=='cleancache'){
		include 'cleancache.php';
	}
	
	require_once 'process.php';
	
	
	function pp($str){
		die( "<pre>".print_r($str)."</pre>" );
	}
	
	function send404($str){
		header('HTTP/1.1 404 Not Found');
		header('Content-type: text/plain');
		die($str);
	}
	
	function send400($str){
		header('HTTP\1.1 400 Bad Request');
		header('Content-type:text/plain');
		die($str);
	}
	
	function dieOnNone($image){
		
		if(file_exists($image) && is_file($image)) return;
		send404("$image Not found");
		
	}
	
	function getFile($image){
		dieOnNone($image);
		
		header("Content-Type: image/jpg");
		header('Cache-Control: private, max-age='.CACHETIME);
		header('Expires: ' . gmdate('D, d M Y H:i:s', time() + CACHETIME) . ' GMT');
		$r = file_get_contents($image);
		header('Etag: ' . md5($r));
		
		die($r);
	}
	
	function getFilename($uri){
		$last = sizeof($uri) - 1;
		$name = array();
		foreach ($uri as $k=>$v) 
		{
			$k = $k*1;
			if($k==$last){
				$filename = explode('.',$uri[sizeof($uri)-1]);
				array_unshift($name,$filename[0]);
				$name[] = 'jpg';
				continue;
			}
			
			$name [] = $v;
		}
		
		return implode('.',$name);
	}
	
	function getCrop($par){
		$c = explode('|','center|top|left|right|bottom|left-top|left-bottom|right-top|right-bottom');
		if(in_array($par,$c) || preg_match('/\./',$par)){
			return $par;
		}
		
		return false;
	}
	
	function getDim($par){
		$regexSize = array(
			'/\d+\-w/',
			'/\d+\-h/',
			'/\d+x\d+/'
		);
		
		if(preg_match($regexSize[0],$par)){
			$w = preg_replace('/\-w/','',$par);
			return array($par,$w*1,false);
		}
		
		if(preg_match($regexSize[1],$par)){
			$h = preg_replace('/\-h/','',$par);
			return array($par,false,$h*1);
		}
		
		if(preg_match($regexSize[2],$par)){
			$h = explode('x',$par);
			return array($par,$h[0]*1,$h[1]*1);
		}
		
		return false;
	}
	
	function cleanPath($p){
		if(sizeof($p)&&!$p[0]) {
			array_shift($p);
			return cleanPath($p);
		}
		else return $p;
	}
	
	dieOnNone( IMAGEDIR . $path[sizeof($path)-1] );
	
	$filename = CACHEDIR . getFilename($path);
	if(file_exists($filename) && is_file($filename)) getFile($filename);
	
	$filter = array('dreamy','velvet','chrome','lift','canvas','vintage','monopin','antique','blackwhite','boost','sepia','blur');
	
	switch(sizeof($path)){
		
		case 1:
			getFile(IMAGEDIR . $path[0]);
			
		case 2:
			
			if(in_array($path[0],$filter)){
				getFile(gd_filter_image(IMAGEDIR . $path[1],$filename,$path[0]));
			}
			
			if($r = getDim($path[0])){
				getFile( gd_resize( IMAGEDIR . $path[1], $filename, $r[1], $r[2] ) );
			}
			
			send400('Bad syntax: you should use filter or size on first param');
			
		case 3:
			
			if(in_array($path[0],$filter)){
				if($r = getDim($path[1])){
					$n = gd_resize( IMAGEDIR . $path[2], $filename, $r[1], $r[2] );
					getFile(gd_filter_image($n,$n,$path[0]));
				}else{
					send400('Bad syntax: unknown size on second param');
				}
			}
			
			if($r = getDim($path[0])){
				$n = gd_crop( IMAGEDIR . $path[2], $filename, $r[1], $r[2], $path[1] );
				if(!$n){
					send400('Bad syntax: unknown crop value on second param');
				}
				
				getFile($n);
			}
			
			send400('Bad syntax: you should use filter or size on first param');
			
		default: // 4 or >4
			
			
			
			if(!in_array($path[0],$filter)){
				send400('Bad syntax: unknown filter on first param');
			}
			
			if(!getDim($path[1])){
				send400('Bad syntax: unknown size on second param');
			}
			
			if(!getCrop($path[2])){
				send400('Bad syntax: unknown crop value on third param');
			}
			
			$r = getDim($path[1]);
			$n = gd_crop( IMAGEDIR . $path[3], $filename, $r[1], $r[2], $path[2] );
			if(!$n){
				send400('Bad syntax: unknown crop value on third param');
			}
			
			getFile(gd_filter_image($n,$n,$path[0]));
			
	}
	
?>
