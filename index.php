<?php
//database 
$server="localhost";
$username="root";
$password=" ";

//DataBase Connection
$link=mysqli_connect($server, $username, $password) or die ("Error connecting to mysql server: ".mysqli_error());

//Database name
$dbname = 'products';

//Table Connection
mysqli_select_db($link, $dbname) or die ("Error connecting to database: ".mysqli_error());   

//$query = "SELECT * FROM products";

//Query on table
$result = mysql_query("SELECT * FROM products") or die ("ErrorQuery failed: ".mysql_error());

//global variable
$average_price = " ";
$total_qty = " ";
$average_profit_margin = " ";
$complete_total_profit = " ";
$item_count = 0;

//Money format
setlocale(LC_MONETARY, 'en_US.UTF-8');

//header
$header_output .= "<table>
    				<tr>
        				<th align=\"center\">SKU</th>
            			<th align=\"center\">Cost</th>
            			<th align=\"center\">Price</th>
            			<th align=\"center\">QTY</th>
            			<th align=\"center\">Profit Margin</th>
						<th align=\"center\">Total Profit</th>
    				</tr>";
	
	//body
	//While pull main body data
    while ($row=mysql_fetch_array($result)) {
		
		//local variables
		$color = " ";
		$color2 = " ";
		$profit_margin = " ";
		$total_profit = " ";
        $sku = $row["sku"];
        $cost = $row["cost"];
        $price = $row["price"];
        $qty = $row["qty"];
		$profit_margin = $price - $cost;
		$total_profit = $profit_margin * $qty;
		
		//in the positiveor nagitive green for positive red for negative
		if($profit_margin > 0)
			{
				$color = "green";  
			}
			else
				{ 
				$color = "red"; 
			}
		if($total_profit > 0)
			{
				$color2 = "green";  
			}
			else 
				{ 
				$color2 = "red"; 
			}

		//Dollar format
		$m_cost = money_format('%.2n', $cost);
		$m_price = money_format('%.2n', $price);
		$m_profit_margin = money_format('%.2n', $profit_margin);
		$m_total_profit = money_format('%.2n', $total_profit);
		
		//Creating table rows
        $body_output .= "<tr>
							<td align=\"left\">$sku</td>
							<td align=\"left\">$m_cost</td>
							<td align=\"left\">$m_price</td>
							<td align=\"left\">$qty</td>
							<td align=\"left\"><font style=\"color: $color \">$m_profit_margin</\font></td>
							<td align=\"left\"><font style=\"color: $color2 \">$m_total_profit</\font></td>
        				</tr>"; 
						
		
						
		//calulate averages and totals				
		$item_count++;
		$average_price = $average_price + $price;
		$total_qty = $total_qty + $qty;
		$average_profit_margin = $average_profit_margin + $profit_margin;
		$complete_total_profit = $complete_total_profit + $total_profit;      
    }
	//Total Variable
	$average_price = $average_price/$item_count;
	$average_profit_margin = $average_profit_margin/$item_count;
	
	//Dollar Format
	$average_price = money_format('%.2n', $average_price); 
	$total2 = money_format('%.2n', $average_profit_margin);
	$total3 = money_format('%.2n', $complete_total_profit);
	
	//footer
	$footer_output .= "<tr>
						<td>Average Price: $average_price</td>
						<td>Total QTY: $total_qty</td>
						<td colspan=\"2\">Average Profit: $total2</td>
						<td colspan=\"2\">Total Profit: $total3</td>
					  </tr>
					</table>";
        
?>

<!doctype html>
<html>
	<head>
		<meta charset="UTF-8">
		<title>Product Table</title>
	</head>
	<body>
		<!-- table Out Put -->
		<?php
			echo $header_output;
			echo $body_output;
			echo $footer_output;
		?>  
	</body>
</html>
