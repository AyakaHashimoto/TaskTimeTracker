<!DOCTYPE html>
<html lang="ja">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <!-- Bootstrap -->
    <link href="css/bootstrap.css" rel="stylesheet">
    <link href="css/style.css" rel="stylesheet">

    <title>Task Time Keeper</title>
</head>
<body>
    <header>
        <h1 class="fw-normal">menu</h1> 
        <div class="container w-80">内容</div>
    </header>
  
    <main>

        <h2 class="text-center text-info my-4">TASK TIME KEEPER</h2>
        <p class="lead text-muted">タスクの内容を変更しました。</p>
        
        <?php
    require('dbconnect.php');

    $statement = $db->prepare('UPDATE task SET task_name=? WHERE id=?');
    $statement->execute(array($_POST['task'], $_POST['id']));

    ?>


        <div class="container my-4">
            <div class="row align-items-start">
                <div class="col mt-4">
                    <?php print($_POST['task']); ?>
                </div>
                <div class="col mt-4">
                    <?php print("目標 : ".$_POST['target_time']." 分"); ?>
                </div>
                <div class="col mt-4">
                    <?php print("開始時刻 : ".date('H:i:s')."-"); ?>
                </div>
            </div>
        </div>
        

    </main>
</body>
</html>