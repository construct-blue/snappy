<?php

declare(strict_types=1);

$exampleEnv = <<<EOF
<?php

declare(strict_types=1);

return [
  "pdo_dsn"     => "mysql:host=the_db_hostname;port=3306;dbname=insert_your_db_name",
  "pdo_username"=> "insert_your_db_user",
  "pdo_password"=> "insert_a_secure_pw",
  "pdo_prefix"  => "blue_",
];
EOF;
$exampleEnv = highlight_string($exampleEnv, true);
?>

<html lang="en">
<head>
    <title>Welcome to Snappy</title>
    <meta name="viewport" content="width=device-width, initial-scale=1"/>
    <meta charset="UTF-8">
    <link rel="stylesheet" href="/styles.css">
</head>
<body>
<h1>Welcome to Snappy</h1>
<p>In order to continue, please create a <code>env.php</code> file with the following content in the root folder.
</p>
<p>It should be where the public, src and vendor folders are.</p>
<p>Change the values to match your database configuration.</p>
    <pre>
        <?= $exampleEnv ?>
    </pre>
</body>
</html>
