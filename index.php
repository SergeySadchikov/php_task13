<?php 
$errors = "";

$username = 'sadchikov';
$password = 'neto1734';

$db = new PDO('mysql:host=localhost;dbname=sadchikov;charset=utf8', $username, $password);
//add   
if (isset($_POST['save'])) {
    if (empty($_POST['description'])) {
        $errors = "Задание отсутствует!";
    } else {
        $sql_query = 'INSERT INTO tasks (description, is_done, date_added) VALUES (?, 0, now())';
        $rows = $db->prepare($sql_query);
        $rows->execute([$_POST['description']]);  
    }
}
 //done
if (isset($_GET['action'])) {
    if ($_GET['action'] === 'done') {
        $sql_query = 'UPDATE tasks SET is_done = 1 WHERE id = ?';
        $rows = $db->prepare($sql_query);
        $rows->execute([$_GET['id']]);
    }
//edit
    if ($_GET['action'] === 'edit') {
        $sql_query = 'SELECT * FROM tasks WHERE id = ?';
        $rows = $db->prepare($sql_query);
        $rows->execute([$_GET['id']]);
        $description = $rows->fetch()['description'];
    }
//del
    if ($_GET['action'] === 'delete') {
        $sql_query = "DELETE FROM tasks WHERE id = ?";
        $rows = $db->prepare($sql_query);
        $rows->execute([$_GET['id']]);
    }
}
 ?>

<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>to-do-list</title>
</head>
<body>

<style>
    table { 
        border-spacing: 0;
        border-collapse: collapse;
    }

    table td, table th {
        border: 1px solid #ccc;
        padding: 5px;
    }
    
    table th {
        background: #eee;
    }
</style>

<h1>Список дел на сегодня</h1>
<div style="float: left">
    <form method="POST">
        <?php if (isset($errors)) { ?>
        <p><?php echo $errors; ?></p>
        <?php } ?>
        <input type="text" name="description" placeholder="Описание задачи" value="<?php if (isset($description)) {echo($description);} ?>"/>
        <input type="submit" name="save" value="Добавить" />
    </form>
</div>
<!-- <div style="float: left; margin-left: 20px;">
    <form method="POST">
        <label for="sort">Сортировать по:</label>
        <select name="sort_by">
            <option value="date_created">Дате добавления</option>
            <option value="is_done">Статусу</option>
            <option value="description">Описанию</option>
        </select>
        <input type="submit" name="sort" value="Отсортировать" />
    </form>
</div> -->
<div style="clear: both"></div>

<table>
    <tr>
        <th>Описание задачи</th>
        <th>Дата добавления</th>
        <th>Статус</th>
        <th></th>
    </tr>
    <?php
    $result = $db->query("SELECT * FROM tasks");
    while ($row = $result->fetch()) { ?>
    <tr>
        <td><?php echo $row['description']; ?></td>
        <td><?php echo $row['date_added']; ?></td>
        <td>
            <?php  
            if ($row['is_done'] == 1) {
                echo '<span style="color: darkgreen">Выполнено</span>';
            } elseif ($row['is_done'] == 0) {
                echo '<span style="color: darkorange">В процессе</span>';
            }
            ?>
                 
        </td>
        <td>
            <a href='index.php?id=<?php echo($row['id'])?>&action=edit'>Изменить</a>
            <a href='index.php?id=<?php echo($row['id'])?>&action=done'>Выполнить</a>
            <a href='index.php?id=<?php echo($row['id'])?>&action=delete'>Удалить</a>
        </td>
    </tr>
    <?php } ?>

</table>

</body>
</html>

