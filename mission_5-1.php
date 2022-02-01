<?php

$dsn = 'mysql:dbname=tb******db;host=localhost';
$user = 'tb-******';
$password = 'PASSWORD';
$pdo = new PDO($dsn, $user, $password, array(PDO::ATTR_ERRMODE => PDO::ERRMODE_WARNING));
$sql = "CREATE TABLE IF NOT EXISTS tbm5"
." ("
. "id INT AUTO_INCREMENT PRIMARY KEY,"
. "name char(32),"
. "comment TEXT,"
. "date char(32),"
. "pass TEXT"
.");";
$stmt = $pdo->query($sql);

$f = "";
$edit_name = "";
$edit_come = "";
$edit_pass = "";
    
if(!empty($_POST["edit"])){
    $edit = $_POST["edit"];
    $e_pass = $_POST["e_pass"];
    $f = $edit;
    
    $id = $edit;   
    $sql = 'SELECT * FROM tbm5 WHERE id=:id ';
    $stmt = $pdo->prepare($sql);                  // ←差し替えるパラメータを含めて記述したSQLを準備し、
    $stmt->bindParam(':id', $id, PDO::PARAM_INT); // ←その差し替えるパラメータの値を指定してから、
    $stmt->execute();                             // ←SQLを実行する。
    $results = $stmt->fetchAll(); 
    foreach ($results as $row){
        if(strcmp($row['pass'],$e_pass) == 0){
            $edit_name = $row['name'];
            $edit_come = $row['comment'];
            $edit_pass = $row['pass'];
            echo "編集内容を送信してください。";
        //$rowの中にはテーブルのカラム名が入る
        }
    }
        
    if(empty($edit_name)){
        $f = "";
        echo "パスワードが異なります。編集できません。";
    }
}

?>

<html lang="ja">
<head>
    <meta charset="UTF-8">
    <title>mission_5-1</title>
</head>
<body>
    <form action="" method="post">
        <input type="text" name="name" placeholder="名前" value =<?= $edit_name ?>>
        <input type="text" name="come" placeholder="コメント" value =<?= $edit_come ?>>
        <input type="hidden" name="flag" value =<?= $f ?>>
        <input type="text" name="pass" placeholder="パスワード" value =<?= $edit_pass ?>>
        <input type="submit" name="submit">
    </form>
    <form action="" method="post">
        <input type="number" name="del" placeholder="削除対象番号">
        <input type="text" name="d_pass" placeholder="パスワード">
        <input type="submit" name="submit" value="削除">
    </form>
    <form action="" method="post">
        <input type="number" name="edit" placeholder="編集対象番号">
        <input type="text" name="e_pass" placeholder="パスワード">
        <input type="submit" name="submit" value="編集">
    </form>

<?php    
    if(empty($_POST["del"]) && empty($_POST["edit"])){
    
    $date_in = date("Y/m/d H:i:s");
    
    if(!empty($_POST["flag"])){
        $id = $_POST["flag"]; //変更する投稿番号
        $name = $_POST["name"];
        $comment = $_POST["come"];
        $date = $date_in;
        $pass = $_POST["pass"];
        $sql = 'UPDATE tbm5 SET name=:name,comment=:comment,date=:date,pass=:pass WHERE id=:id';
        $stmt = $pdo->prepare($sql);
        $stmt->bindParam(':pass', $pass, PDO::PARAM_STR);
        $stmt->bindParam(':date', $date, PDO::PARAM_STR);
        $stmt->bindParam(':name', $name, PDO::PARAM_STR);
        $stmt->bindParam(':comment', $comment, PDO::PARAM_STR);
        $stmt->bindParam(':id', $id, PDO::PARAM_INT);
        $stmt->execute();
    }
    
    if(!empty($_POST["name"]) && !empty($_POST["come"])){
        if(empty($_POST["flag"]) && !empty($_POST["pass"])){
            $sql = $pdo -> prepare("INSERT INTO tbm5 (name, comment, date, pass) VALUES (:name, :comment, :date, :pass)");
            $sql -> bindParam(':name', $name, PDO::PARAM_STR);
            $sql -> bindParam(':comment', $comment, PDO::PARAM_STR);
            $sql -> bindParam(':date', $date, PDO::PARAM_STR);
            $sql -> bindParam(':pass', $pass, PDO::PARAM_STR);
            $name = $_POST["name"];
            $comment = $_POST["come"];
            $date = $date_in;
            $pass = $_POST["pass"];
            $sql -> execute();
        }
    }
    
}elseif(!empty($_POST["del"]) && empty($_POST["edit"])){
    $id = $_POST["del"];   
    $sql = 'SELECT * FROM tbm5 WHERE id=:id ';
    $stmt = $pdo->prepare($sql);                  // ←差し替えるパラメータを含めて記述したSQLを準備し、
    $stmt->bindParam(':id', $id, PDO::PARAM_INT); // ←その差し替えるパラメータの値を指定してから、
    $stmt->execute();                             // ←SQLを実行する。
    $results = $stmt->fetchAll(); 
    foreach ($results as $row){
        if(strcmp($row['pass'],$_POST["d_pass"]) == 0){
            $id = $_POST["del"];
            $sql = 'delete from tbm5 where id=:id';
            $stmt = $pdo->prepare($sql);
            $stmt->bindParam(':id', $id, PDO::PARAM_INT);
            $stmt->execute();
        }else{
            echo "パスワードが異なります。削除できません。<br>";
        }
    }
}

$sql = 'SELECT * FROM tbm5';
$stmt = $pdo->query($sql);
$results = $stmt->fetchAll();
foreach ($results as $row){
    //$rowの中にはテーブルのカラム名が入る
    echo $row['id'].',';
    echo $row['name'].',';
    echo $row['comment'].',';
    echo $row['date'].'<br>';
    echo "<hr>";
}
?>
                
</body>
</html>