<?php 

    $servername = "localhost";
    $username = "superuser";
    $password = "heimer27";
    $db = "daytrade"; 
    $mysqli = null;


    // Check connection
    try {
        $mysqli = new mysqli($servername, $username, $password, $db);
    } catch (mysqli_sql_exception $e) {

    } 

    $result = $mysqli->query("SELECT sector, industry, count(*) as count FROM sector GROUP BY sector, industry");

    $html = "";

    if ($result->num_rows > 0) {

        $html = "<div><table style='border: 1px solid black !important;'><tbody>";

        $html .= "<tr style='font-size: 11px;'><td style='border: 1px solid black !important;'><b>Sector</b></td><td style='border: 1px solid black !important; width: 400px;'><b>Industry</b></td><td style='border: 1px solid black !important;'><b># of stocks<b></td></tr>"; 

        // output data of each row
        while($row = $result->fetch_assoc()) {
            $html .=  "<tr style='font-size: 11px;'><td style='border: 1px solid black !important;'>&nbsp;<b>" . $row["sector"] . "</b>&nbsp;</td><td style='border: 1px solid black !important; width: 400px;'>&nbsp;<b>" . $row["industry"] . "</b>&nbsp;</td><td style=\"border: 1px solid black !important; align='center';\">&nbsp;<b>" . $row["count"] . "<b>&nbsp;</td></tr>";
        }
        $html .= "</tbody><table></div>";
    } else {
        $html = "<span style='font-size: 15px>Nothing yet</span>";
    }

    echo $html;

?> 

