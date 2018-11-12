<html>
<?php
	$con = mysql_connect("localhost","0326710","tanu181294")or die('Cannot connect :'. $mysql_error());
	mysql_select_db("winestore0326710",$con)or die('Cannot connect to database!');
?>

<head>
<title>Afsana's Winestore - Search</title>
</head>

<h2>Search Engine</h2>

<form method="GET" action="part-d-answer.php">
<table border=1>

<tr>
	<td bgcolor=#cccccc width=300>Wine name</td>
	<td width=300 align="center"><input type="text" name="WineName">
	</td>
</tr>

<tr>
	<td bgcolor=#cccccc width=300>Region</td>
	<td width=300 align="center"><select name="region">
	<?php
		$region=mysql_query("SELECT region_name FROM region",$con);

		if(mysql_num_rows($region)){
			while($row=mysql_fetch_array($region))
			{
			echo "<option>".$row['region_name']."</option>";
			}
		}
	?>
	</select>
	</td>
</tr>

<tr>
	<td bgcolor=#cccccc width=300>Winery name</td>
	<td width=300 align="center"><input type="text" name="WineryName"></td>
</tr>

<tr>
	<td bgcolor=#cccccc width=300>Range of years</td>
	<td width=300 align="center"><input type="number" min="1900" max="2000" name="minYear">
	<input type="number" min="1900" max="2000" name="maxYear"></td>
</tr>

<tr>
	<td bgcolor=#cccccc width=300>Stock</td>
	<td width=300 align="center"><input type="text" name="WineStock"></td>
</tr>

<tr>
	<td bgcolor=#cccccc width=300>Customer ordered</td>
	<td width=300 align="center"><input type="text" name="qty"></td>
</tr>

<tr>
	<td bgcolor=#cccccc width=300>Cost range</td>
	<td width=300 align="center"><input type="number" min="0" max="999" name="minCost">
	<input type="number" min="0" max="999" name="maxCost"></td>
</tr>

<tr>
	<td colspan="2" align="center"><input type="reset" value="Reset">
	<input type="submit" value="Search"></td>
</tr>

</table>
</form>
</html>