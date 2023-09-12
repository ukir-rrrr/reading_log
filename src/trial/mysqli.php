<?php

$link = mysqli_connect('db', 'book_log', 'pass', 'book_log');

if (!$link) {
    echo 'Error: データベースに接続できませんでした' . PHP_EOL;
    echo 'Debugging error:' . mysqli_connect_error() . PHP_EOL;
    exit;
}

echo 'データベースに接続できました' . PHP_EOL;

$sql = 'SELECT name, founder FROM companies';
$results = mysqli_query($link, $sql);

while ($company = mysqli_fetch_assoc($results)) { //結果の行を連想配列で取得
    echo '会社名 :' . $company['name'] . PHP_EOL;
    echo '代表者名 :' . $company['founder'] . PHP_EOL;
}

mysqli_free_result($results);


/*$sql = <<<EOT
INSERT INTO companies (
    name,
    establishment_date,
    founder
) VALUES (
    'SmartHR Inc',
    '2013-01-23',
    'Shouji Miyata'
);
EOT;

if (mysqli_query($link, $sql)) {
    echo 'データを挿入しました' . PHP_EOL;
} else {
    echo 'Error: ' . mysqli_error($link) . PHP_EOL;
}
*/

mysqli_close($link);
echo 'データベースとの接続を切断しました' . PHP_EOL;
