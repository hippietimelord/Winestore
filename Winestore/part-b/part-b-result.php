<html>
<head>
<title>Afsana's Winestore - Search Result</title>
</head>
<body>

<?php
	$con = mysql_connect("localhost","0326710","tanu181294")or die('Cannot connect :'. $mysql_error());
	mysql_select_db("winestore0326710",$con)or die('Cannot connect to database!');
	
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
	
	if($minYear > $maxYear){
		echo "<b>Error Message: Starting year cannot more than ending year.</b>";
		echo "<br><br><a href=\"part-b.php\">Back to Search Form</a>";
	}
	
	else if($minCost > $maxCost){
		echo "<b>Error Message: Minimum cost cannot be more than maximum cost.</b>";
		echo "<br><br><a href=\"part-b.php\">Back to Search Form</a>";
	}
	
	//run query on search page
	else{
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
		
		$result = mysql_query($query,$con);
		
		//Display output on answer page
		if(mysql_num_rows($result) == 0){
			echo "<h2>Search Results</h2>";
			echo "<br><br><p>No matching search result!</p>";
			echo "<br><br><a href=\"part-b.php\">Back to Search Form</a>";
		}
		
		else{
			echo "<h2>Search Results</h2>";
			echo "<a href=\"part-b.php\">Back to Search Form</a>";
			echo "<table border=1>";
			echo "<tr><th>Wine Name</th>
				<th>Wine Variety</th>
				<th>Year</th>
				<th>Winery Name</th>
				<th>Region Name</th>
				<th>In Stock</th>
				<th>Cost</th>
				<th>No. of customers who purchased</th></tr>";
				
			while($row = mysql_fetch_array($result)){
				echo "<tr><td width=300 align=center>".$row[0]."</td>";
				echo "<td width=300 align=center>".$row[1]."</td>";
				echo "<td width=300 align=center>".$row[2]."</td>";
				echo "<td width=300 align=center>".$row[3]."</td>";
				echo "<td width=300 align=center>".$row[4]."</td>";
				echo "<td width=300 align=center>".$row[5]."</td>";
				echo "<td width=300 align=center>".$row[6]."</td>";
				echo "<td width=300 align=center>".$row['No_Cust']."</td>";
				
				echo "\n";
			}
			
			echo "</td></tr>";
			echo "</table>";
			
		}
		
		mysql_close($con);
	}
?>
	
</body>
</html>
		
	