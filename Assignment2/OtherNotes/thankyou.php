<?php
/*******w******** 
    
    Name: Al Hochbaum
    Date: 9/13/2023
    Description: Validation and sanitization of a form.

****************/
define("ITEM_DESCRIPTION_AND_PRICE", ['qty1' => (['description' => "MacBook", 'price' => 1899.99]), 
                                      'qty2' => (['description' => "The Razer", 'price' => 79.99]),
                                      'qty3' => (['description' => "WD My Passport", 'price' => 179.99]),
                                      'qty4' => (['description' => "Nexus 7", 'price' => 249.99]),
                                      'qty5' => (['description' => "DD-45 Drums", 'price' => 119.99])]);

    $GLOBALS['valid_elements'] = [];
    $GLOBALS['invalid_elements'] = [];
    $cost_totals = 0.00;

    // This associated array defines the behaviors of the credit card
    $GLOBALS['credit_card_validators'] =
    [
         //  must equal 10 characters.
        'cardnumber' => function($attribute_value){
            return strlen($attribute_value) === 10;
        },
        // The credit card year must be an integer with a minimum value of the current year
        // and a maximum value of five years from the current year.
        'year' => function($attribute_value){
            $current_year = (Integer)date("Y");
            $max_year = $current_year + 5;

            return $current_year <= $attribute_value && $attribute_value <= $max_year;
        },
        // The value for the month can't be pasted the current month
        'month' => function($attribute_value){
            $valid_month = true;

            if ((Integer)date("Y") >= $_POST['year']) 
                $valid_month = $attribute_value  >= (Integer)date("m");
    
            return $valid_month;
        },
    ];

     /**
     * Filtering for correct formating to match, 
     * fullname, city, cardname, postal code, and province code.
     * 
     */
    function pattern_matching($current_name, string $current_value) 
    {
    
        $match_found = false;

        switch ($current_name) 
        {

            case 'fullname':
                $match_found = preg_match( "/^[A-Za-z]+((\s)?([A-Za-z])+)+((\s)?([A-Za-z])+)*$/", $current_value);
            break;

            case 'city':
                $match_found = preg_match( "/^[A-Za-z]+((\s)?([A-Za-z])+)*$/", $current_value);
            break;

            case 'cardname':
                $match_found = preg_match(  "/^[A-Za-z]+((\s)?([A-Za-z])+)+((\s)?([A-Za-z])+)*$/", $current_value);
            break;

            case 'address':
                $match_found = preg_match( "/^([0-9]+ )?[a-zA-Z ]+$/", $current_value);
            break;

            case 'province':
                $match_found = preg_match( "/^(?:AB|BC|MB|N [BLTSU]|ON|PE|QC|SK|YT)*$/", $current_value);
            break;

            case 'postal':
                $match_found = preg_match("/^[A-Za-z]\d[A-Za-z][ -]?\d[A-Za-z]\d$/",  $current_value);
            break;
        }

        return $match_found;
    }

     /**
     * 
     * 
     * 
     */
    function validation_and_Sanitization() 
    {
        // Looping through all the elements within the global POST array
        foreach($_POST as $element_name => $element_value) 
        {
            $is_an_integer = false;
            $element_value = filter_input(INPUT_POST, $element_name, FILTER_SANITIZE_FULL_SPECIAL_CHARS);

            // If any of the element quanitity tags "qty" didn't reseive user input
            // we want to remove them from the forms validation
            if(substr($element_name, 0, 3) === 'qty' && empty($element_value))
            {
                unset($element_value);
            }

            // Checking The elements name and value attributes to see if they're set
            if (!empty($element_value)) 
            {
                // The code below is validation only for elements that
                // have a value attribute which is set to an integer
                if (filter_input(INPUT_POST, $element_name, FILTER_VALIDATE_INT))
                {

                    $is_an_integer = true;

                    if (array_key_exists($element_name ,$GLOBALS['credit_card_validators']) &&
                        $GLOBALS['credit_card_validators'][$element_name]($element_value)) 
                    {
                        $GLOBALS['valid_elements']["{$element_name}"] =  $element_value;
                    }
                    elseif (substr($element_name, 0, 3) === 'qty') 
                    {
                        $GLOBALS['valid_elements']["{$element_name}"] =  $element_value;
                    }
                    else 
                    {
                        $GLOBALS['invalid_elements']["{$element_name}"] = 
                            empty($element_value) ? "No entry was made": $element_value;
                    }
                }

                if (!$is_an_integer) 
                {
                    if (filter_input(INPUT_POST, $element_name, FILTER_VALIDATE_EMAIL)) 
                    {
                        $GLOBALS['valid_elements']["{$element_name}"] =  $element_value;

                    // Checking to see if the radio button's state is checked.
                    }
                    elseif ($element_value === 'on') 
                    {
                        $GLOBALS['valid_elements']["{$element_name}"] =  $element_value;
                    }
                    elseif (pattern_matching($element_name, $element_value)) 
                    {
                        $GLOBALS['valid_elements']["{$element_name}"] =  $element_value;
                    }
                    else 
                    {
                        $GLOBALS['invalid_elements']["{$element_name}"] = 
                            empty($element_value) ? "No entry was made": $element_value;
                    }
                }     
            }
            elseif (substr($element_name, 0, 3) !== 'qty')
            {
                $GLOBALS['invalid_elements']["{$element_name}"] = 
                    empty($element_value) ? "No entry was made": $element_value;
            }
            /* 
            * Because radio buttons won't show up within the $_POST
            * array because of their unchecked state 
            * we need to add them to the error list.
            */ 
            elseif (!isset($_POST['cardtype'])) 
            {
                $GLOBALS['invalid_elements']["cardtype"] = 
                    "You did not select a credit card type.";
            }
        }

        return  $GLOBALS['invalid_elements'];
    }
    
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Thanks for your order!</title>
    <meta http-equiv="X-UA-Compatible" main_header="IE=edge" content="IE=edge">
    <meta name="viewport" main_header="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" type="text/css" href="thankyou.css"/>
</head>
<body>
   <!-- Remember that alternative syntax is good and html inside php is bad -->
   <?php if(!validation_and_Sanitization()): ?>
    <header><h1> Thank you for your order <?= $GLOBALS['valid_elements']['fullname'] ?></h1></header>
    <table>
        <tbody>
            <tr>
                <td colspan="4">Address Information</td>
            </tr>
            <tr>
                <td>Address:</td>
                <td ><?= $GLOBALS['valid_elements']['address']?></td>
                <td>City:</td>
                <td><?= $GLOBALS['valid_elements']['city']?></td>
            </tr>
            <tr>
                <td>Province:</td>
                <td><?= $GLOBALS['valid_elements']['province']?></td>
                <td>Postal Code:</td>
                <td><?= $GLOBALS['valid_elements']['postal']?></td>
            </tr>
            <tr>
                <td colspan="2">Email:</td>
                <td colspan="2"><?= $GLOBALS['valid_elements']['email']?></td>
            </tr>
        </tbody>
    </table>

    <table>
        <tbody>
            <tr >
                <td colspan="4">Order Information</td>
            </tr>
            <tr>
                <td>Quantity</td>
                <td>Description</td>
                <td>Cost</td>
            </tr>
            <?php foreach($GLOBALS['valid_elements'] as $attribute_name => $attribute_value): ?>
                <?php if(substr($attribute_name , 0, 3) === 'qty'): ?>
                        <tr>
                            <td><?= $attribute_value ?></td>
                            <td><?= ITEM_DESCRIPTION_AND_PRICE[$attribute_name]['description'] ?></td>
                            <td><?= ITEM_DESCRIPTION_AND_PRICE[$attribute_name]['price'] ?></td>
                            <?php $cost_totals += $attribute_value * ITEM_DESCRIPTION_AND_PRICE[$attribute_name]['price'] ?>
                            <?php if($cost_totals > 10000.00): ?>
                                <?= header("Location: https://www.youtube.com/watch?v=Aq5WXmQQooo&t=9s") ?>
                            <?php endif?>
                        </tr>
                        <?php endif ?>
            <?php endforeach ?>
            <tr>
                <td colspan="2">Totals</td>
                <td colspan="2"><?= '$' . $cost_totals ?></td>
            </tr>
        </tbody>
    </table>
    <?php else: ?>
            <table>
                <tbody>
                    <tr><th>Sorry but some of your submissions were invalid</th></tr>

                    <?php foreach($GLOBALS['invalid_elements'] as $element_name => $element_value): ?>
                        <tr>
                            <td><?= $element_name ?></td>
                            <td><?= $element_value ?></td>
                        </tr>
                    <?php endforeach?>
                </tbody>
            </table>
    <?php endif ?>
</body>
</html>

    
