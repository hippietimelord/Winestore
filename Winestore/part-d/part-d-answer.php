<?php
	include "HTML/Template/IT.php";
	include "DB.php";
	include "db.inc";

	$template = new HTML_Template_IT(".");
	$template2 = new HTML_Template_IT(".");
	$template3 = new HTML_Template_IT(".");
	$template4 = new HTML_Template_IT(".");
	$template->loadTemplatefile("part-d-Template_Results.tpl",true,true);	
	$template2->loadTemplatefile("part-d-Template_No_Result.tpl",false,false);
	$template3->loadTemplatefile("part-d-Template_Year_Validation.tpl",false,false);
	$template4->loadTemplatefile("part-d-Template_Cost_Validation.tpl",false,false);
	
	$dsn = "mysql://{$username}:{$password}@{$hostname}/{$databaseName}";
	//open a connection to the DBMS
	$con = DB::connect($dsn);
	if(DB::isError($con))
		die($con->getMessage());
	//Run the query on the winestore through the connection
	$result = $con->query("SELECT * FROM wine");
	if(DB::isError($result))
		die($result->getMessage());
	
	$WineName = $_GET['WineName'];
	$region = $_GET['region'];
	$WineryName = $_GET['WineryName'];
	$minYear = $_GET['minYear'];
	$maxYear = $_GET['maxYear'];
	$WineStock = $_GET['WineStock'];
	$qty = $_GET['qty'];
	$minCost = $_GET['minCost'];
	$maxCost = $_GET['maxCost'];
	
	//Input validation
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
		AND (cost BETWEEN '".$minCost."' AND '".$maxCost."')				
		HAVING No_Cust >= '".$qty."'";
	
	@$result = $con->query($query);
		
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
	else if($result->numrows() == 0){
		$template2->setCurrentBlock("RESULT_Failed");
		$template2->parseCurrentBlock();
		$template2->show();
	}
	else{
		while($row = $result->fetchRow(DB_FETCHMODE_ASSOC)){
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
		$con->disconnect();
	}
?>