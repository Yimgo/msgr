<html>

<head>
    <title>Error</title>
</head>

<body>

    <h1>Error</h1>
    <p>
        <strong>
            <?php echo $message ?>
        </strong>
    </p>

    <p>Additional context details:</p>
    <pre>
<?php

    foreach($context_map as $key => $value)
    {
        echo "$key: $value\n";
    }

?>
    </pre>

</body>

</html>