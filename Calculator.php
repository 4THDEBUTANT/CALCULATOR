<?php
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $expression = isset($_POST['expression']) ? $_POST['expression'] : '';
    $result = '';

    try {
        // Replace ^ with ** for exponentiation
        $expression = str_replace('^', '**', $expression);

        // Replace sqrt and log with PHP functions
        $expression = preg_replace('/sqrt\((.*?)\)/', 'sqrt($1)', $expression);
        $expression = preg_replace('/log\((.*?)\)/', 'log($1)', $expression);

        // Define a safe eval function for mathematical expressions
        $safe_eval = function ($expression) {
            // Allow only numbers and basic operators
            if (preg_match('/[^0-9\+\-\*\/\.\(\)\s\%\*\*]/', $expression)) {
                throw new Exception('Invalid characters in expression');
            }
            return eval('return ' . $expression . ';');
        };

        $result = $safe_eval($expression);
    } catch (Exception $e) {
        $result = 'Error';
    }

    header('Location: index.html?result=' . urlencode($result));
    exit();
} else {
    header('Location: index.html');
    exit();
}
?>
