<?php
	require_once "HTML/Template/IT.php";

	$con = mysql_connect("localhost","0326710","tanu181294")or die('Cannot connect :'. $mysql_error());
	mysql_select_db("winestore0326710",$con)or die('Cannot connect to database!');
	
	$template = new HTML_Template_IT(".");
	$template2 = new HTML_Template_IT(".");
	$template3 = new HTML_Template_IT(".");
	$template4 = new HTML_Template_IT(".");
	$template->loadTemplatefile("part-c-Template_Results.tpl",true,true);	
	$template2->loadTemplatefile("part-c-Template_No_Result.tpl",false,false);
	$template3->loadTemplatefile("part-c-Template_Year_Validation.tpl",false,false);
	$template4->loadTemplatefile("part-c-Template_Cost_Validation.tpl",false,false);
	
	$WineName = $_GET['WineName'];
	$region = $_GET['region'];
	$WineryName = $_GET['WineryName'];
	$minYear = $_GET['minYear'];
	$maxYear = $_GET['maxYear'];
	$WineStock = $_GET['WineStock'];
	$qty = $_GET['qty'];
	$minCost = $_GET['minCost'];
	$maxCost = $_GET['maxCost'];
	
	//Input
	if($WineName == NULL){
		$WineName = "";
	}
	
	if($region == "All"){
		$region = "";
	}
	
	if($WineryName == NULL){
		$WineryName = "";
	}
	
	if($minYear == NULL){
		$minYear = 1900;
	}
	
	if($maxYear == NULL){
		$maxYear = 2000;
	}
	
	if($WineStock == NULL){
		$WineStock = 0;
	}
	
	if($qty == NULL){
		$qty = 0;
	}
	
	if($minCost == NULL){
		$minCost = 0;
	}
	
	if($maxCost == NULL){
		$maxCost = 999;
	}

	$query = "SELECT wine_name,variety,year,winery_name,region_name,on_hand,cost,
		(SELECT COUNT(items.cust_id) 
		FROM items 
		WHERE items.wine_id = wine.wine_id) AS No_Cust
		FROM wine,winery,region,wine_variety,grape_variety,inventory
		WHERE wine.winery_id=winery.winery_id
		AND winery.region_id=region.region_id
		AND wine_variety.wine_id=wine.wine_id
		AND wine_variety.variety_id=grape_variety.variety_id
		AND wine.wine_id=inventory.wine_id
		
		AND wine_name LIKE '%".$WineName."%'
		AND region_name LIKE '%".$region."%'
		AND winery_name LIKE '%".$WineryName."%' 
		AND on_hand >= '".$WineStock."'
		AND (year BETWEEN '".$minYear."' AND '".$maxYear."')
		AND (cost BETWEEN '".$minCost."' AND '".$maxCost."')";
		
	$result = mysql_query($query,$con);	
		
	//year validation
	if($minYear > $maxYear){
		$template3->setCurrentBlock("YEAR_VALIDATION");
		$template3->show();
	}
	//cost validation
	else if($minCost > $maxCost){
		$template4->setCurrentBlock("COST_VALIDATION");
		$template4->show();
	}
	//no result validation
	else if(mysql_num_rows($result) == 0){
		$template2->setCurrentBlock("RESULT_Failed");
		$template2->parseCurrentBlock();
		$template2->show();
	}
	else{
		while($row = mysql_fetch_array($result)){
			$template->setCurrentBlock("RESULT_DETAILS");
			
			$template->setVariable("WINENAME", $row["wine_name"]);
			$template->setVariable("WINE_VARIETY", $row["variety"]);
			$template->setVariable("YEAR", $row["year"]);
			$template->setVariable("WINERY_NAME", $row["winery_name"]);
			$template->setVariable("REGION_NAME", $row["region_name"]);
			$template->setVariable("ON_HAND", $row["on_hand"]);
			$template->setVariable("COST", $row["cost"]);
			$template->setVariable("NO_CUST", $row["No_Cust"]);
			
			$template->parseCurrentBlock();
		}
		
		$template->show();
		mysql_close($con);
	}
?>