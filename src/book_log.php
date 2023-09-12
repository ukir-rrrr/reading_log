<?php

function validate($review)
{
    $errors = [];

    // 書籍名が正しく入力されているかチェック
    if (!mb_strlen($review['title'])) { // $erview[title]の文字数をチェック　何も入力されていない場合は条件分岐
        $errors['title'] = '書籍名を入力してください';
    } elseif (mb_strlen($review['title']) > 255) {
        $errors['title'] = '書籍名は255文字以内で入力してください';
    }

    //著者名が正しく入力されているかチェック
    if (!mb_strlen($review['author'])) {
        $errors['author'] = '著者名を入力してください';
    } elseif (mb_strlen($review['author']) > 100) {
        $errors['author'] = '著者名は100文字以内で入力してください';
    }

    // 読書状況が正しく入力されているかチェック
    if (!in_array($review['status'], ['未読', '読んでる', '読了'], true)) {
        $errors['status'] = '読書状況は「未読」「読んでる」「読了」のいずれかを入力してください';
    }

    //評価が正しく入力されているかチェック
    if ($review['score'] < 1 || $review['score'] > 5) { //1よりも小さいか5よりも大きい
        $errors['score'] = '評価は1~5の整数を入力してください';
    }

    // 感想が正しく入力されているかチェック
    if (!strlen($review['summary'])) {
        $errors['summary'] = '感想を入力してください';
    } elseif (strlen($review['summary']) > 1000) {
        $errors['summary'] = '感想は1,000文字以内で入力してください';
    }

    return $errors;
}


function createReview($link)
{
    $review = [];

    // 読書ログを登録する
    echo '読書ログを登録してください' . PHP_EOL;
    echo '書籍名:';
    $review['title'] = trim(fgets(STDIN));  //標準入力の値を取得するfgets(STDIN)を$titleに代入 trim=空白削除

    echo '著者名:';
    $review['author'] = trim(fgets(STDIN));

    echo '読書状況（未読,読んでる,読了):';
    $review['status'] = trim(fgets(STDIN));

    echo '評価（5点満点の整数):';
    $review['score'] = trim(fgets(STDIN));

    echo '感想:';
    $review['summary'] = (int) trim(fgets(STDIN));

    $validated = validate($review);
    if (count($validated) > 0) { //バリデーションがあった場合
        foreach ($validated as $error) {
            echo $error . PHP_EOL;
        }
        return;
    }

    $sql = <<<EOT
INSERT INTO reviews (
    title,
    author,
    status,
    score,
    summary
) VALUES (
    "{$review['title']}",
    "{$review['author']}",
    "{$review['status']}",
    "{$review['score']}",
    "{$review['summary']}"
)
EOT;

    $result = mysqli_query($link, $sql);
    if ($result) {
        echo '登録が完了しました' . PHP_EOL . PHP_EOL;
    } else {
        echo 'Error: データの追加に失敗しました' . PHP_EOL;
        echo 'Debugging Error: ' . mysqli_error($link) . PHP_EOL . PHP_EOL;
    }

}

function listReviews($link)
{
    echo '登録されている読書ログを表示します' . PHP_EOL;

    $sql = 'SELECT id, title, author, status, score, summary FROM reviews';
    $results = mysqli_query($link, $sql);

    while ($review = mysqli_fetch_assoc($results)) {
    echo '書籍名:' . $review['title'] . PHP_EOL;
    echo '著者名:' . $review['author']. PHP_EOL;
    echo '読書状況:' . $review['status']. PHP_EOL;
    echo '評価:' . $review['score'] . PHP_EOL;
    echo '感想:' . $review['summary'] . PHP_EOL;
    echo '-------------' . PHP_EOL;

    }
    mysqli_free_result($results);
}
// データベース接続　関数定義
function dbConnect()
{
    $link = mysqli_connect('db', 'book_log', 'pass', 'book_log');
    if (!$link) {
        echo 'Error: データベースに接続できません' . PHP_EOL;
        echo 'Debugging error: ' . mysqli_connect_error() . PHP_EOL;
        exit;
    }



    return $link; // DB切断　データ取得・登録する際に接続情報を使用するのでreturnで返す
}



$link = dbConnect(); // 登録でも表示でもデータベースと接続するので、事前にデータベースと接続しておく

while (true) {

    echo '1. 読書ログを登録' . PHP_EOL;
    echo '2. 読書ログを表示' . PHP_EOL;
    echo '9. アプリケーションを終了' . PHP_EOL;
    echo '番号を選択してください (1,2,9) :';
    $num = trim(fgets(STDIN));

    if ($num === '1') {
        // 読書ログを登録する関数を呼び出す
        createReview($link);

    } elseif ($num === '2') {
        // 読書ログを表示する関数を呼び出す
        listReviews($link);

    }   elseif ($num === '9') {
        // アプリケーション終了時にデータベースとの接続を切断
        mysqli_close($link);
        // アプリケーションを終了
        break;
    }

}
