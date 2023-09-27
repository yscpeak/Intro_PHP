<?php

/*******w********

Name: Yi Siang Chang
Date: 2023-09-20
Description: Thank-you page

 ****************/

/* <?= ?> 與相同 <?php echo ?> */

/*phpinfo();*/

$products = [  // Use List tostore the name of products
	'qty1' => 'MacBook',
	'qty2' => 'Razer',
	'qty3' => 'WD HDD',
	'qty4' => 'Nexus',
	'qty5' => 'Drums',
];

$price = [ // Use List to store the price of products
	'qty1' => '1899.99',
	'qty2' => '79.99',
	'qty3' => '179.99',
	'qty4' => '249.99',
	'qty5' => '119.99',
];

$total = 0;  // Initialize the total cost of products

#print_r($_POST); exit(0);


function filterinput(){  // Responsible for validating and filtering input data from a form

	// Initialize validation status
	/*$return_status = 1;*/

	// Name validation
	$namevalidation = filter_input(INPUT_POST, 'fullname', FILTER_SANITIZE_STRING) &&
		(isset($_POST['fullname']));
	if(!$namevalidation){  //If the name input is invalid
		echo "Please enter your name.";  // displays this error message
		/*$return_status = 0;*/ exit(0);


		/*
		 * $return_status = 0 and exit(0) both indicate there is a validation error.
		 * $return_status = 0; doesn't necessarily terminate the script immediately.
		   It potentially checking other conditions or performing additional actions.
		 * exit(0); statement is used to terminate the execution of the PHP script.
		 */

	}
	/*
	* FILTER_SANITIZE_STRING filter to sanitize the input, removing any potential malicious characters.
	* isset function (isset()) checks if the "fullname" field is set in the POST data.
	* The result is stored in the $namevalidation variable.
	*/


	$addressvalidation = filter_input (INPUT_POST, 'address', FILTER_SANITIZE_STRING) &&
		(isset($_POST['address']));
	if(!$addressvalidation){
		echo "Please enter your address.";
		$return_status = 0;
	}

	$cityvalidation = filter_input (INPUT_POST, 'city', FILTER_SANITIZE_STRING) &&
		(isset($_POST['city']));
	if(!$cityvalidation){
		echo "Please enter your city.";
		$return_status = 0;
	}

	$emailvalidation = filter_input (INPUT_POST, 'email', FILTER_SANITIZE_EMAIL) &&
		(isset($_POST['email']));  // FILTER_SANITIZE_EMAIL ensures basic email format.
	if(!$emailvalidation){
		echo "Please enter a valid email.";
		$return_status = 0;
	}

	/*
	 * FILTER_VALIDATE_REGEXP is a PHP filter flag used to validate data
	   by using regular expression pattern.
	 */
	$postalvalidation = filter_input(INPUT_POST, 'postal', FILTER_VALIDATE_REGEXP,
		array("options" => array("regexp" => "/^[a-zA-Z]\d[a-zA-Z]\d[a-zA-Z]\d$/")));
	if (!$postalvalidation) {
		echo "Please enter a valid postal code.";
		$return_status = 0;
	}

	// FILTER_VALIDATE_INT to check if a value is a valid integer
	$cardnumbervalidation = filter_input(INPUT_POST, 'cardnumber', FILTER_VALIDATE_INT) &&
		filter_input(INPUT_POST, 'cardnumber', FILTER_VALIDATE_REGEXP,
			array("options" => array("regexp" => "/^\b\d{10}\b$/")));

	if (!$cardnumbervalidation) {
		echo "Please enter a valid card number.";
		$return_status = 0;
	}

	$monthvalidation = filter_input(INPUT_POST, 'month', FILTER_VALIDATE_INT) &&
		($_POST['month'] >= 1 && $_POST['month'] <= 12);
	if (!($monthvalidation)) {
		echo "Please select a month.";
		$return_status = 0;
	}

	$yearvalidation = filter_input(INPUT_POST, 'year', FILTER_VALIDATE_INT) &&
		($_POST['year'] >= 2023 && $_POST['year'] <= 2026);
	if (!($yearvalidation)) {
		echo "Please select a year.";
		$return_status = 0;
	}

	// To check if $_POST['province'] is one of the specified CA province codes in the array.
	$provincevalidation = in_array($_POST['province'],
		['ON', 'QC', 'BC', 'AB', 'MB', 'NB', 'NL', 'NS', 'PE', 'SK', 'NT', 'NU','YT']);

	// To check if the qty1 input field in a form contains a valid integer value or empty.
	$qty1validation = filter_input(INPUT_POST, 'qty1', FILTER_VALIDATE_INT) ||
		empty($_POST['qty1']);

	$qty2validation = filter_input(INPUT_POST, 'qty2', FILTER_VALIDATE_INT) ||
		empty($_POST['qty2']);

	$qty3validation = filter_input(INPUT_POST, 'qty3', FILTER_VALIDATE_INT) ||
		empty($_POST['qty3']);

	$qty4validation = filter_input(INPUT_POST, 'qty4', FILTER_VALIDATE_INT) ||
		empty($_POST['qty4']);

	$qty5validation = filter_input(INPUT_POST, 'qty5', FILTER_VALIDATE_INT) ||
		empty($_POST['qty5']);

	if(!($qty1validation && $qty2validation && $qty3validation && $qty4validation && $qty5validation))
    {
        echo "Please enter a valid number of items in the cart.";
    }

	// To check if all the specified validation conditions for various input fields are met.
	return ($namevalidation && $addressvalidation && $cityvalidation &&
		$emailvalidation && $postalvalidation && $cardnumbervalidation &&
		$monthvalidation && $yearvalidation && $provincevalidation &&
		$qty1validation && $qty2validation && $qty3validation && $qty4validation &&
		$qty5validation);

	// return the status (if needed)
	/*return $return_status;*/

}

?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="thankyouphp.css">
    <title>Thanks for your order!</title>

    <!-- CSS Template -->
    <style>
        table {
            border-collapse: collapse;
            width: 70%;
            margin: 2em auto;
        }

        table, th, td {
            border: 1px solid black;
        }

        th, td {
            padding: 15px;
            text-align: left;
        }

        th {
            background-color: #f2f2f2;
        }

        tr:nth-child(even) {
            background-color: #f5f5f5;
        }

        tr:hover {
            background-color: #e0e0e0;
        }

        td.alignright {
            text-align: right;
        }

        h2, h3 {
            text-align: center;
        }

        .content-wrapper {
            border: 2px solid black;
            padding: 20px;
            margin: 50px auto;
            width: 80%;
            box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
        }
    </style>

</head>

<body>
<!-- Remember that alternative syntax is good and html inside php is bad -->
<div class="content-wrapper">
<?php if(filterinput() && isset($_POST['fullname']) && isset($_POST['address'])): ?>

    <h2>Thanks for your order <?= $_POST['fullname'] ?>.</h2>
    <h3>Here's a summary of your order:</h3>
    <!--<table>
        <tr>
            <td colspan="4"> Address Information </td>
        </tr>
        <tr>
            <td> Address: </td>
            <td><?/*= $_POST['address'] */?></td>
        </tr>
        <tr>
            <td> City: </td>
            <td><?/*= $_POST['city'] */?></td>
        </tr>
        <tr>
            <td> Province: </td>
            <td><?/*= $_POST['province'] */?></td>
        </tr>
        <tr>
            <td> Postal Code: </td>
            <td><?/*= $_POST['postal'] */?></td>
        </tr>
        <tr>
            <td colspan="2"> Email: </td>
            <td colspan="2"><?/*= $_POST['email'] */?></td>
        </tr>
    </table>

    <table>
        <tr>
            <td colspan="2"> Email: </td>
            <td colspan="2"><?/*= $_POST['email'] */?></td>
        </tr>
    </table>-->

    <!--<table>
        <tr>
            <td colspan="3"> Order Infomation </td>
        </tr>
        <tr>
            <td> Quantity </td>
        </tr>
        <tr>
            <td> Description </td>
        </tr>
        <tr>
            <td> Cost </td>
        </tr>

        <?php /*if(($_POST['qty1']) > 0): */?>
        <tr>
            <td><?/*= $_POST['qty1'] */?></td>
        </tr>
        <tr>
            <td><?/*= $products['qty1'] */?></td>
        </tr>
        <tr>
            <?php /*$cost = ($price['qty1']) * ($_POST['qty1']) */?>
            <td>
	            <?/*= $cost */?>
                <?php /*$total += $cost */?>
            </td>
        </tr>
        <?php /*endif; */?>

	    <?php /*if(($_POST['qty2']) > 0): */?>
            <tr>
                <td><?/*= $_POST['qty2'] */?></td>
            </tr>
            <tr>
                <td><?/*= $products['qty2'] */?></td>
            </tr>
            <tr>
			    <?php /*$cost = ($price['qty2']) * ($_POST['qty2']) */?>
                <td>
				    <?/*= $cost */?>
				    <?php /*$total += $cost */?>
                </td>
            </tr>
	    <?php /*endif; */?>

	    <?php /*if(($_POST['qty3']) > 0): */?>
            <tr>
                <td><?/*= $_POST['qty3'] */?></td>
            </tr>
            <tr>
                <td><?/*= $products['qty3'] */?></td>
            </tr>
            <tr>
			    <?php /*$cost = ($price['qty3']) * ($_POST['qty3']) */?>
                <td>
				    <?/*= $cost */?>
				    <?php /*$total += $cost */?>
                </td>
            </tr>
	    <?php /*endif; */?>

	    <?php /*if(($_POST['qty4']) > 0): */?>
            <tr>
                <td><?/*= $_POST['qty4'] */?></td>
            </tr>
            <tr>
                <td><?/*= $products['qty4'] */?></td>
            </tr>
            <tr>
			    <?php /*$cost = ($price['qty4']) * ($_POST['qty4']) */?>
                <td>
				    <?/*= $cost */?>
				    <?php /*$total += $cost */?>
                </td>
            </tr>
	    <?php /*endif; */?>

	    <?php /*if(($_POST['qty5']) > 0): */?>
            <tr>
                <td><?/*= $_POST['qty5'] */?></td>
            </tr>
            <tr>
                <td><?/*= $products['qty5'] */?></td>
            </tr>
            <tr>
			    <?php /*$cost = ($price['qty5']) * ($_POST['qty5']) */?>
                <td>
				    <?/*= $cost */?>
				    <?php /*$total += $cost */?>
                </td>
            </tr>
	    <?php /*endif; */?>

        <tr>
            <td colspan="2" class='alignright'>Totals</td>
            <td class='alignright'>
                $ <?/*= $total */?>
            </td>
        </tr>

    </table>-->

    <table border="1">
        <tr>
            <th colspan="2">Address Information</th>
        </tr>
        <tr>
            <td>Address</td>
            <td><?= $_POST['address'] ?></td>
        </tr>
        <tr>
            <td>City</td>
            <td><?= $_POST['city'] ?></td>
        </tr>
        <tr>
            <td>Province</td>
            <td><?= $_POST['province'] ?></td>
        </tr>
        <tr>
            <td>Postal Code</td>
            <td><?= $_POST['postal'] ?></td>
        </tr>
        <tr>
            <td>Email</td>
            <td><?= $_POST['email'] ?></td>
        </tr>
    </table>

    <!-- Order Information Table -->
    <table border="1">
        <tr>
            <th colspan="3">Order Information</th>
        </tr>
        <tr>
            <th>Quantity</th>
            <th>Description</th>
            <th>Cost</th>
        </tr>


        <!--
        Use a foreach loop to simplify codes by encapsulating and
        generate table rows within a single loop.
        (makes the code easier to read and maintain)
        -->

		<?php
		/* Loop through the $products array.
		 * $key is the product identifier (e.g., 'qty1').
		 * $product is the product name (e.g., 'MacBook').
		 */
		foreach($products as $key => $product) {

			// Check if the user ordered a quantity greater than 0 for this product.
			if(intval($_POST[$key]) > 0) {

				$cost = $price[$key] * $_POST[$key];

				// Update the total cost by adding the cost of this product.
				$total += $cost;

				// Output a table row for this product
				echo "<tr>";
				echo "<td>{$_POST[$key]}</td>";  // Display the quantity ordered by the user.
				echo "<td>{$product}</td>";  // Display the product name
				echo "<td>$" . number_format($cost, 2) . "</td>"; // Display the calculated cost formatted.
				echo "</tr>";
			}
		}
		?>

        <tr>
            <td colspan="2" class='alignright'>Totals</td>
            <td class='alignright'>$<?= number_format($total, 2) ?></td>
        </tr>
    </table>

<?php endif; ?>

</div>

</body>
</html>