<html>
<head>
<title>Afsana's Winestore - Search Result</title>
</head>
<body>
<h2>Search Results</h2>
<br><br><a href="part-c.php">Back to Search Form</a>
	<br><table border=1>
	<tr><th>Wine Name</th>
		<th>Wine Variety</th>
		<th>Year</th>
		<th>Winery Name</th>
		<th>Region Name</th>
		<th>In Stock</th>
		<th>Cost</th>
		<th>No. of customers who purchased</th>
	</tr>
	<!-- BEGIN RESULT_DETAILS -->
	<tr>
		<td width=300 align="center">{WINENAME}</td>
		<td width=300 align="center">{WINE_VARIETY}</td>
		<td width=300 align="center">{YEAR}</td>
		<td width=300 align="center">{WINERY_NAME}</td>
		<td width=300 align="center">{REGION_NAME}</td>
		<td width=300 align="center">{ON_HAND}</td>
		<td width=300 align="center">{COST}</td>
		<td width=300 align="center">{NO_CUST}</td>
	</tr>
	<!-- END RESULT_DETAILS -->
	</table>
</body>
</html>