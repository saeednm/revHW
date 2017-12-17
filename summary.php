<?php
    
    require_once 'Data.php';
    $d = new Data();
    $summary = $d->getSummary();
    $projects = $d->getProjects();
    $customers = $d->getCustomers();
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <?php header('Access-Control-Allow-Origin: *'); ?>
    <meta charset="UTF-8">
    <title>Reviso HW</title>
    <!-- Latest compiled and minified CSS -->
    <link rel="stylesheet" href="./bower_components/bootstrap/dist/css/bootstrap.min.css">
    <link rel="stylesheet" href="./stylesheets/main.min.css">
    <link rel="stylesheet" href="./stylesheets/products.min.css">
    <script type="text/javascript" src="script.js"></script>
</head>

<body>
<?php include('menu.html'); ?>

<style>
body {font-family: Arial;}

/* Style the tab */
.tab {
    overflow: hidden;
    border: 1px solid #ccc;
    background-color: #f1f1f1;
}

/* Style the buttons inside the tab */
.tab button {
    background-color: inherit;
    float: left;
    border: none;
    outline: none;
    cursor: pointer;
    padding: 14px 16px;
    transition: 0.3s;
    font-size: 17px;
}

/* Change background color of buttons on hover */
.tab button:hover {
    background-color: #ddd;
}

/* Create an active/current tablink class */
.tab button.active {
    background-color: #ccc;
}

/* Style the tab content */
.tabcontent {
    display: none;
    padding: 6px 12px;
    border: 1px solid #ccc;
    border-top: none;
}
</style>

<div class="container">
    <div class="row">
        <div class="col-md-4">
            <h3 class="blue">Filters</h3>
            <div class="side-bar">
				
				<div class="tab">
  <button class="tablinks" onclick="openTab(event, 'Projects')">Projects</button>
  <button class="tablinks" onclick="openTab(event, 'Customers')">Customers</button>
</div>

<div id="Projects" class="tabcontent">
  
                <?php
                  #  $cgr = $_GET["category"];
                while ($row = $projects->fetch_assoc()) {
				#	echo $row["p_id"];
                    echo "<input type='checkbox' name='project' value='" . $row["p_id"] . "'><label>" . $row["p_name"] . "</label><br>";
                }
                ?>
                
</div>

<div id="Customers" class="tabcontent">
				
                <?php
                  #  $cgr = $_GET["category"];
                while ($row = $customers->fetch_assoc()) {
				#	echo $row["p_id"];
                    echo "<input type='checkbox' name='customer' value='" . $row["c_id"] . "'><label>" . $row["c_name"] . "</label><br>";
                }
                ?>
                
</div>



<script>
function openTab(evt, Name) {
    var i, tabcontent, tablinks;
    tabcontent = document.getElementsByClassName("tabcontent");
    for (i = 0; i < tabcontent.length; i++) {
        tabcontent[i].style.display = "none";
    }
    tablinks = document.getElementsByClassName("tablinks");
    for (i = 0; i < tablinks.length; i++) {
        tablinks[i].className = tablinks[i].className.replace(" active", "");
    }
    document.getElementById(Name).style.display = "block";
    evt.currentTarget.className += " active";
}
</script>
				
				
               
				<hr>
                <h4>Time period</h4>
					<label style="width:100px">from</label>
					<input type="date" ></input>
					<br>
					<label style="width:100px">to </label>
					<input type="date"></input>
                <hr>
                <input type="submit" value="apply"></input>
            </div>
			
        </div>
        <div class="col-md-8">
            <h3 class="blue">Results</h3>
            <div class="grid" id="display_info">
              
			 <h4>Projects</h4>
				<table border='2'> 
					<tr>
						
						<td>project</td>
						<td>hour(s)</td>
						<td>minute(s)</td>
					</tr>
                <?php
                  
                while ($row = $summary->fetch_assoc()) {
					echo "<tr>";
                   
					echo "<td> " . $row["project"] . "</td>";
					echo "<td> " . $row["hour"] . "</td>";
					echo "<td> " . $row["minute"] . "</td>";
					echo "</tr>";
                }
                ?>
				
                </table>
             
            </div>
        </div>
    </div>
</div>
<!-- scripts added last for faster loading -->
<script src="./bower_components/jquery/dist/jquery.min.js"></script>
<script src="./bower_components/bootstrap/dist/js/bootstrap.min.js"></script>
<script>
    function colorChecked() {
    }
</script>

</body>
</html>