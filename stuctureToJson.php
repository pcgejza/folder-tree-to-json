<?php

/**
 * 
 * Ez a script feltérképezi a mappák teljes struktúráját és egy json fájlba menti el
 * Futtatásra példa: php stuctureToJson.php testfolder test.json 'http://users.iit.uni-miskolc.hu/~huzynets/download.php?file='
 */



	function folderIterator($folder, $preUrl){

		$ret = [];
		$folderOpen = opendir($folder);

		while ($data = readdir($folderOpen)) {
			if ($data != '.' && $data != '..') {
				$path = $folder.'/'.$data;

				if(is_dir($path)){
					$ret[] = [
						'title' => $data,
						'files' => folderIterator($path, $preUrl)
					];
				}else{
					if ($data == 'linkek'){
						$fl = fopen($path, 'r') ;
						while (!feof($fl)){
							$l = fgets($fl, 1024);
							$a_href = substr($l,0,strpos($l, ' '));
							$a_str = substr($l,strpos($l, ' ')+1);
							$ret[] = [
								'title' => $a_str,
								'url' => $a_href
							];
						}
						fclose($fl);
					}else{
						$ret[] = [
							'title' => $data,
							'url' => $preUrl.$path
						];
					}
				}

			}
		}

		return $ret;
	}

	try{
		if(!isset($argv[1])){
			throw new Exception("Első argumentként kérjük adja meg a mappa elérési útját!");
		}

		if(!is_dir($argv[1])){
			throw new Exception("A megadott argumentum nem mappa!");
		}

		if(!isset($argv[2])){
			throw new Exception("A második paraméterként kérjük adja meg a kimeneti fájlt");
		}

		if(!isset($argv[3])){
			throw new Exception("Harmadik paraméterként kérjük adja meg az elérési URL-t");
		}

		$folder = $argv[1];
		$outFile = $argv[2];
		$preUrl =  $argv[3]; 
		$tree = folderIterator($folder, $preUrl);

		$fp = fopen($outFile, 'w');
		fwrite($fp, json_encode($tree, JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE));
		fclose($fp);

		echo "\n> $outFile fájl generálása sikeres!\n"; 
	}catch(Exception $e){
		echo "\n> ERROR: ".$e->getMessage();
	}