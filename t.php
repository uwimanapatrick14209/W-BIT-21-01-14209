<?php
session_start(); // Start session to track history

// Process calculation only if all inputs are set
if (isset($_GET["num1"], $_GET["num2"],$_GET["num3"], $_GET["operator"])) {
    $num1 = $_GET["num1"];
    $num2 = $_GET["num2"];
    $num3 =$_GET["num3"];
    $op = $_GET["operator"];
    $result = "";

    switch ($op) {
        case "add":
            $result = $num1 + $num2 +$num3;
            break;
        case "subtract":
            $result = $num1 - $num2 -$num3;
            break;
        case "multiply":
            $result = $num1 * $num2 *$num3;
            break;
        case "divide":
            $result = ($num2 != 0) ? $num1 / $num2 : "Error: Division by zero";
            break;
        case "percent":
            $result = ($num1 / 100) * $num2;
            break;
        case "exponent":
            $result = pow($num1, $num2);
            break;
        default:
            $result = "Invalid operation";
    }

    //Store in session history
    $expression = "$num1 " . operatorSymbol($op). "$num2" .operatorSymbol($op) . " $num3 = $result";
    $_SESSION['history'][] = $expression;
}

// Helper function to convert operator to symbol
function operatorSymbol($op) {
    return match ($op) {
        "add" => "+",
        "subtract" => "−",
        "multiply" => "×",
        "divide" => "÷",
        "percent" => "% of",
        "exponent" => "^",
        default => "",
    };
}
?>

<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>Advanced PHP Calculator</title>
    <style>
        body {
            background: linear-gradient(to right, #00c6ff, #0072ff);
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            display: flex;
            justify-content: center;
            align-items: center;
            height: 100vh;
            margin: 0;
        }

        .calculator {
            background-color: #ffffff;
            padding: 30px 40px;
            border-radius: 15px;
            box-shadow: 0 8px 20px rgba(0, 0, 0, 0.2);
            text-align: center;
            width: 380px;
        }

        h2 {
            color: #333;
            margin-bottom: 20px;
        }

        input[type="number"],
        select {
            width: 100%;
            padding: 10px;
            margin: 10px 0 20px;
            border: 1px solid #ccc;
            border-radius: 8px;
            font-size: 16px;
        }

        input[type="submit"],
        .clear {
            background-color: #28a745;
            color: white;
            padding: 10px;
            border: none;
            border-radius: 8px;
            font-size: 16px;
            cursor: pointer;
            width: 100%;
            transition: background-color 0.3s ease;
            margin-top: 10px;
        }

        input[type="submit"]:hover,
        .clear:hover {
            background-color: #218838;
        }

        .result {
            margin-top: 20px;
            padding: 15px;
            background-color: #e9f7ef;
            border-left: 4px solid #28a745;
            font-size: 18px;
            color: #333;
            border-radius: 8px;
        }

        .history {
            margin-top: 25px;
            text-align: left;
        }

        .history h3 {
            font-size: 16px;
            color: #555;
            margin-bottom: 10px;
        }

        .history ul {
            list-style-type: square;
            padding-left: 20px;
            color: #333;
        }
    </style>
</head>

<body>
    <div class="calculator">
        <h2>PHP Super Calculator</h2>
        <form method="get" action="">
            First Number:
            <input type="number" name="num1" required>

            Second Number:
            <input type="number" name="num2" required>
            Third Number:
            <input type="number" name="num3" required>

            Operation:
            <select name="operator" required>
                <option value="add">Add (+)</option>
                <option value="subtract">Subtract (−)</option>
                <option value="multiply">Multiply (×)</option>
                <option value="divide">Divide (÷)</option>
                <option value="percent">Percent (%)</option>
                <option value="exponent">Exponent (^)</option>
            </select>

            <input type="submit" value="Calculate">
        </form>

        <?php if (isset($result)) : ?>
            <div class="result">Result: <?php echo $result; ?></div>
        <?php endif; ?>

        <?php if (!empty($_SESSION['history'])) : ?>
            <div class="history">
                <h3>Calculation History:</h3>
                <ul>
                    <?php foreach (array_reverse($_SESSION['history']) as $entry) : ?>
                        <li><?php echo htmlspecialchars($entry); ?></li>
                    <?php endforeach; ?>
                </ul>
            </div>
        <?php endif; ?>

        <form method="post" action="">
            <button class="clear" name="clear" type="submit">Clear History</button>
        </form>

        <?php
        // Clear session history if requested
        if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['clear'])) {
            $_SESSION['history'] = [];
            header("Location: " . $_SERVER['PHP_SELF']); // Refresh to clean up
            exit();
        }
        ?>
    </div>
</body>
</html>