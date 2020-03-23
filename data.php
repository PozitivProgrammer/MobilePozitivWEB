<?php
	require('web_service.php');
	require('config.php');
	session_start();
	
	if (isset($_GET["action"]))
	{
		switch($_GET["action"]){
			case "datalist":
			{
				
				$soapResult = null;
				if ( isset($_GET['groupref']) ){
					$soapResult = $ws_client->call("GetParentList", array("Ref" => $_GET['ref'], "ParentRef" => $_GET['groupref']) );
				} else {
					$soapResult = $ws_client->call("GetList", array("Ref" => $_GET['ref']));
				}
				
				if ($soapResult['result'])
				{
					$jsonResult = json_decode($soapResult["data"]->return);
					
					$pageData = "<div class=\"list-group\">";
					foreach ($jsonResult->Data as $jsonItem)
					{
						$name = $jsonItem->Name;
						$description = $jsonItem->Description == ""? "": " (".$jsonItem->Description.")";
						$ref = $jsonItem->Ref;
						
						$isGroup = false;
						if (isset($jsonItem->IsGroup)){
							$isGroup = $jsonItem->IsGroup;
						}
						
						$lref = $_GET['ref'];
						if( property_exists($jsonItem,'Image' ) ){
							switch ($jsonItem->Image)
							{
								case "Document":
								$image = "ic_document.png";
								break;
								case "DocumentDeleted":
								$image = "ic_document_deleted.png";;
								break;
								case "DocumentAccept":
								$image = "ic_document_accept.png";;
								break;
								case "Folder":
								$image = "ic_folder.png";;
								break;
							}
						} else {
							
							$image = "ic_document.png";
						}
						$pageData .= "<a href=\"#\" class=\"list-group-item list-group-item-action datalist_item\" id=\"datalist_item\" value=\"$ref\" isGroup=\"$isGroup\"><img width=\"32\" height=\"32\" src=\"res/$image\">&nbsp;<b>$name</b>&nbsp;$description</a>";
					}
					$pageData .= "</div>";
				}
				else
				{
					//return $soapResult['data'];
				}
			}
			break;
			
			
			case "multidatalist":
			{
				
				$soapResult = $ws_client->call("GetList", array("Ref" => $_GET['ref']));
				if ($soapResult['result'])
				{
					$jsonResult = json_decode($soapResult["data"]->return);
					
					$pageData = "<div class=\"list-group\">";
					
					$pageData .= "<table>";
					
					$multi_elems_count = count($jsonResult->Data);
					
					$pageData .= "<tr><td colspan=\"5\"><input name=\"n_mmu_00\" id=\"mmu_00\" type=\"hidden\" value=\"$multi_elems_count\"/><button  name=\"n_mmu_0\" type=\"submit\" class=\"btn btn-primary\" style=\"width: 200px;\" id=\"mmu_0\" onclick=\"clear_multi_elems($multi_elems_count);\">Очистить</button></td></tr><tr><td colspan=\"5\"><button  name=\"n_mmu_1\" type=\"submit\" class=\"btn btn-primary\" style=\"width: 200px;\" id=\"mmu_1\" onclick=\"accept_multi_elems();\">Выбрать</button></td></tr>";
					
					
					
					
					$sch = 2;
					foreach ($jsonResult->Data as $jsonItem)
					{
						$name = $jsonItem->Name;
						$description = $jsonItem->Description == ""? "": " (".$jsonItem->Description.")";
						$ref = $jsonItem->Ref;
						$lref = $_GET['ref'];
						if( property_exists($jsonItem,'Image' ) ){
							switch ($jsonItem->Image)
							{
								case "Document":
								$image = "ic_document.png";
								break;
								case "DocumentDeleted":
								$image = "ic_document_deleted.png";;
								break;
								case "DocumentAccept":
								$image = "ic_document_accept.png";;
								break;
							}
						} else {
							
							$image = "ic_document.png";
						}
						$sch_first = $sch + 1000;
						$sch_second = $sch + 2000;
						$pageData .= "<tr><td><img width=\"32\" height=\"32\" src=\"res/$image\"/></td><td><b>$name</b></td><td><div id=\"mmu_$sch\">0</div><input id=\"code_mmu_$sch\" type=\"hidden\" value=\"$ref\"/><input id=\"odines_name_mmu_$sch\" type=\"hidden\" value=\"$name\"/></td><td><button  name=\"n_mmu_$sch_first\" type=\"submit\" class=\"btn btn-primary\" style=\"width:50px;\" id=\"mmu_$sch_first\" onclick=\"increase_element_count($sch);\">+</button></td><td><button  name=\"n_mmu_$sch_second\" type=\"submit\" class=\"btn btn-primary\" style=\"width:50px;\" id=\"mmu_$sch_second\" onclick=\"decrease_element_count($sch);\">-</button></td></tr>";
						$sch++;
					}
					$pageData .= "</table>";
					$pageData .= "</div>";
					
					
				}
				else
				{
					//return $soapResult['data'];
				}
			}
			break;
		}
		echo $pageData;
	}
	else
	{
		//Если не передаем никаких данных то переходим в корень сайта.
		if (count($_POST)==0)
		{
			header("HTTP/1.1 200 OK");
			header("Location: $site_path");		
			exit();
		}
		
		$id = 1;
		$dataArray = array();
		foreach ($_POST as $key=>$value)
		{
			$name = substr($key, 4);
			if (substr($key, 0, 4) == "btn_")
			{
				$dataArray["0"] = array("Name" => $name, "Data" => $value);
			}
			elseif (substr($key, 0, 4) == "val_")
			{
				$dataArray[$id] = array("Name" => $name, "Data" => $value);
			}
			else continue;
			$id++;
		}
		$jsonString = json_encode($dataArray, JSON_UNESCAPED_UNICODE);	
		$soapResult = $ws_client->call("SetData", array("RefListMod" => $_POST['sys_lref'], "Ref" => $_POST['sys_ref'], "Data" => $jsonString));	
		if ($soapResult["result"])
		{
			$soapString = str_replace("\n", "", $soapResult["data"]->return);
			$jsonData = json_decode($soapString);
			switch ($jsonData->Result)
			{
				case "CompletedClose":
				goToList();
				break;
				case "Completed":
				goBack();
				break;
				case "ErrorClose":
				goToList();
				break;
				case "Error":
				goBack();
				break;
				case "ReportGenerated":
				echo base64_decode($jsonData->Data);
				break;
			}
		}
		else
		{
			//если ошибка
		}
	}
	
	function goBack()
	{
		header('HTTP/1.1 200 OK');
		header('Location: '.$_SERVER['HTTP_REFERER']);
	}
	
	function goToList()
	{
		header('HTTP/1.1 200 OK');
		header('Location: '.$_POST["sys_pref"]);
	}
?>