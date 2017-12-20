<?php
    $loginUser="Saeed";
	$userID=1;
    require_once 'Data.php';
    $d = new Data();
    $summary = $d->getTotal($userID);
    $projects = $d->getProjects($userID);
    $customers = $d->getCustomers($userID);
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <?php header('Access-Control-Allow-Origin: *'); ?>
    <meta charset="UTF-8">
    <title>Reviso HW</title>
    <!-- Latest compiled and minified CSS -->
    <link rel="stylesheet" href="/bower_components/bootstrap/dist/css/bootstrap.min.css">
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
	
	table, th, td {
    border: 1px solid black;
    border-collapse: collapse;
}
th, td {
    padding: 15px;
}

</style>

<div class="container">
    <div class="row">
		<?php echo "<h5> Hi ".$loginUser."! </h5>"; ?>
        <div class="col-md-4">
			
            <h3 class="blue">Filters</h3>
            <div class="side-bar">
				
				<div class="tab">
					<button class="tablinks" onclick="openTab(event, 'Projects')">Project</button>
					<button class="tablinks" onclick="openTab(event, 'Customers')">Customer</button>
					<button class="tablinks" onclick="openTab(event, 'None')">X</button>
				</div>
				<form>
				<input hidden name="type" value="filter"> </input>
				<input hidden id="filterType" name="filterType" value="none"> </input>
				<div id="Projects" class="tabcontent">
  
                <?php
                while ($row = $projects->fetch_assoc()) {
                    echo "<input type='checkbox' name='project' value='" . $row["p_id"] . "'><label>" . $row["p_name"] ." for ". $row["c_name"]. "</label><br>";
                }
                ?>
				</div>
				<input hidden id="projects" name="projects" value=""> </input>
				<div id="Customers" class="tabcontent">
				
                <?php

                while ($row = $customers->fetch_assoc()) {
                    echo "<input type='checkbox' name='customer' value='" . $row["c_id"] . "'><label>" . $row["c_name"] . "</label><br>";
                }
                ?>
                
				</div>
				<input hidden id="customers" name="customers" value=""> </input>
				<div id="None" class="tabcontent">
				</div>

<script>
function openTab(evt, Name) {
	document.getElementById('filterType').value=Name;
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
					<input type="date" name="fromDate"></input>
					<br>
					<label style="width:100px">to </label>
					<input type="date" name="toDate"></input>
                <hr>
                <input type="submit" value="apply"></input>
			</form>	
            </div>
			
        </div>
        <div class="col-md-8">
            <h3 class="blue">Results</h3>
            <div class="grid" id="display_info">
              
				<h4> </h4>
				<table border='2'> 
					<tr>
						<th> customer </th>
						<th> project </th>
						<th> date </th>
						<th> minute(s) </th>
						
					</tr>
                <?php
                  
                while ($row = $summary->fetch_assoc()) {
					echo "<tr>";
                    echo "<td> " . $row["customer"] . "</td>";
					echo "<td> " . $row["project"] . "</td>";
					echo "<td> " . $row["date"] . "</td>";
					echo "<td> " . $row["minute"] . "</td>";
					#echo "<td> " . $row["fee"] . "</td>";
					#echo "<td> " . number_format($row["wage"], 2, '.', ',') . "</td>";
					echo "</tr>";
                }
                ?>
				
                </table>
             
            </div>
        </div>
    </div>
</div>
<!-- scripts added last for faster loading -->
	<script src="/bower_components/jquery/dist/jquery.min.js"></script>
	<script src="/bower_components/bootstrap/dist/js/bootstrap.min.js"></script>
	<script>
      $(function () {

        $('form').on('submit', function (e) {

          e.preventDefault();
		  customers = document.getElementsByName('customer');
		  bf = '';
          for (i = 0; i < customers.length; i++) {
				if (customers[i].checked) {
					bf = bf + "," + customers[i].value ;
				}
			}
          bf = bf.slice(1);
		  
		  document.getElementById('customers').value=bf;
		  
		  projects = document.getElementsByName('project');
		  bf = '';
          for (i = 0; i < projects.length; i++) {
				if (projects[i].checked) {
					bf = bf + "," + projects[i].value ;
				}
			}
          bf = bf.slice(1);
		  document.getElementById('projects').value=bf;
		  
          $.ajax({
            type: 'post',
            url: 'post.php',
            data: $('form').serialize(),
            success: function (resp) {
              alert('form was submitted');
			  console.log( resp );
			  if(resp!= "none"){
				  document.getElementById("display_info").innerHTML =resp;
			  }
            }
          });

        });

      });
    </script>

</body>
</html>