 <?php
/* This script calculates the amount of change needed in EUR. 
It also specifies which bills and coins are needed and how many of them. 
Bills or coins that are not needed are not printed on the screen.  */

session_start();

// Serverside input validation
if (isset($_POST['calculate']) ) {
	// Prevent input inaccuracies
	$owed = str_replace(',', '.',($_POST['owed']));
	$paid = str_replace(',', '.', ($_POST['paid'])); 
	// Validate if there is input at all
	 if (strlen($owed) < 1 || strlen($paid) < 1) {
		$_SESSION['error'] = "Please insert the amount owed and the amount paid";
		header("location: calculator.php");
		return;
	}
	// Validate that the input is a number 
	if (! is_numeric($owed) || ! is_numeric($paid) ) {
		$_SESSION['error'] = "Please insert a number";
		header("location: calculator.php");
		return;
	}
	// Multiply the change by 100 to prevent float inaccuracies
	$change = ($paid  - $owed) * 100;	
}
?>

<!DOCTYPE html>
<html>
<head>
	<meta charset="utf-8">
  	<meta name="viewport" content="width=device-width, initial-scale=1">
  	<title>Spare change calculator</title>
</head>
<body>
	<div class="container text-center">
		<p><h1 class="py-4 bg-warning text-light rounded"><i class="fas fa-comments-dollar"></i> Spare change calculator</h1>
		</p>
		<p><h5>This web app takes as input the amount of money owed and the amount paid in euros. 
    It then prints on the screen exactly how much bills or coins are needed of each denomination (which bill or coin). 
    Denominations that are not used are not printed.</h5>
		</p>
		</div>
	<div class="container">
		<div class="row">
		<div class="col-6">
			<form method="POST">
				<?php
				// Display errors 
				if (isset($_SESSION['error'])	) {
					echo('<p style="color: red;">'.htmlentities($_SESSION['error'])."</p>\n");
  					unset($_SESSION['error']);
  				}
				?>
				<label for="owed">Owed: </label>
				<input type="text" class="form-control" name="owed">
				<label for="paid">Paid: </label>
				<input type="text" class="form-control" name="paid">
				<br>
				<button name="calculate" class="btn btn-primary"><i class="fas fa-calculator"></i> Calculate</button>
			</form>	
			<br>
			<br>
				<?php 
					if (isset($paid) ) {
					  	echo "Paid: €" . htmlentities($paid) . "<br>";
					} 
					if (isset($owed) ) {
						echo "Owed: €" . htmlentities($owed) . "<br>";
					 }
					if (isset($change) ) {
						echo "Change: € " . htmlentities($change / 100);
					}
				?>
		</div>
		<div class="col-6 text-center">
		<?php 
			if (isset($change) ) {
				// The elements represent denominations (bills or coins) of 500 EUR through 0.01 EUR
				$denominations = array(50000, 20000, 10000, 5000, 2000, 1000, 500, 200, 100, 50, 20, 10, 5, 2, 1);
				foreach ($denominations as $denomination) {
					if ($change >= $denomination) {
					// Calculate the amount of coins needed of each respective element
					$coins = $change / $denomination;
					// Use the modulus operator to calculate the remaining change
					$change = $change % $denomination;
					// Use the floor function to prevent inaccuracies: e.g. you can't have 1.2 coins 
					echo floor($coins);
						// Print the number of denominations needed
						if ($denomination >= 500) {
						echo " bill(s) of  €" . htmlentities($denomination / 100) . ".-";	
						}
						if ($denomination == 200 || $denomination == 100) {
							echo " coin(s) of  €" . htmlentities($denomination / 100) . ".-";
						}
						if ($denomination <= 50 && $denomination  >= 10 ) {
						 	echo " coin(s) of  €" . htmlentities($denomination / 100) . "0";
						}
						if ($denomination <= 5 && $denomination >= 1) {
						echo " coin(s) of  €" . htmlentities($denomination / 100);
						}
						echo "<br>";
						}
					} 
			}
		?>
		</div>
	</div>
<script src="https://kit.fontawesome.com/e011ef49ec.js" crossorigin="anonymous"></script>
<link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.4.1/css/bootstrap.min.css" integrity="sha384-Vkoo8x4CGsO3+Hhxv8T/Q5PaXtkKtu6ug5TOeNV6gBiFeWPGFN9MuhOf23Q9Ifjh" crossorigin="anonymous">
<script src="https://code.jquery.com/jquery-3.4.1.slim.min.js" integrity="sha384-J6qa4849blE2+poT4WnyKhv5vZF5SrPo0iEjwBvKU7imGFAV0wwj1yYfoRSJoZ+n" crossorigin="anonymous"></script>
<script src="https://cdn.jsdelivr.net/npm/popper.js@1.16.0/dist/umd/popper.min.js" integrity="sha384-Q6E9RHvbIyZFJoft+2mJbHaEWldlvI9IOYy5n3zV9zzTtmI3UksdQRVvoxMfooAo" crossorigin="anonymous"></script>
<script src="https://stackpath.bootstrapcdn.com/bootstrap/4.4.1/js/bootstrap.min.js" integrity="sha384-wfSDF2E50Y2D1uUdj0O3uMBJnjuUD4Ih7YwaYd1iqfktj0Uod8GCExl3Og8ifwB6" crossorigin="anonymous"></script>
</body>
</html>
