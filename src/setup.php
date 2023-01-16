<?php

declare(strict_types=1);

$exampleEnv = <<<EOF
<?php

const MYSQL_HOST = 'the_db_hostname';
const MYSQL_PORT = '3306'; // typically no change needed
const MYSQL_DATABASE = 'insert_your_db_name';
const MYSQL_USER = 'insert_your_db_user';
const MYSQL_PASSWORD = 'insert_a_secure_pw';
const MYSQL_TABLE_PREFIX = 'blue_';
EOF;
$exampleEnv = highlight_string($exampleEnv, true);
?>

<html lang="en">
<head>
    <title>Welcome to Snappy</title>
    <meta name="viewport" content="width=device-width, initial-scale=1"/>
    <meta charset="UTF-8">
</head>
<body>
<h1>Welcome to Snappy</h1>
<p>In order to continue, please create a <code>env.php</code> file with the following content in the root folder.
</p>
<p>It should be where the data, public, src and vendor folders are.</p>
<p>Change the values to match your database configuration.</p>
<pre>
        <?= $exampleEnv ?>
    </pre>
</body>
</html>
