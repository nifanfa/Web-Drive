<?php
$logined = false;
$password = "nifanfa1234";
if($_COOKIE['password'] == $password)
{
    $logined = true;
}

if(isset($_POST['password']))
{
    #默认密码
    if($_POST['password']==$password)
    {
        setcookie("password",$_POST['password'],time()+300);
        echo('<script>alert("登录成功");document.location.href = document.referrer;</script>');
    }else
    {
        echo('<script>alert("登录失败");document.location.href = document.referrer;</script>');
    }
}

if(isset($_GET['folder']))
{
    exec("md ".$_GET['folder']);
    echo('<script>alert("创建成功");document.location.href = document.referrer;</script>');
}

if(!isset($_GET['path']) || $_GET['path'][strpos($_GET['path'],":")+1]!="\\")
{
    $_GET['path'] = getcwd();
    #return;
}

if($_FILES['file']['name']!=null)
{
    #document.location.href = document.referrer 防止重新提交表单 
    if($_FILES['file']['error']>0)
    {
        echo('<script>alert("上传失败");document.location.href = document.referrer;</script>');
    }else
    {
        move_uploaded_file($_FILES['file']['tmp_name'],$_GET['path'].$_FILES['file']['name']);
        echo('<script>alert("上传成功");document.location.href = document.referrer;</script>');
    }
}
?>

<?php if($logined):?>
<html>
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width">
    </head>
    <body>
        <label style="font-size: 24px;"><?php 
        $freespace = disk_free_space(getcwd())/(1024*1024*1024);
        $space = disk_total_space(getcwd())/(1024*1024*1024);
        echo(substr($freespace,0,strpos($freespace,"."))."GB"."/".substr($space,0,strpos($space,"."))."GB")
        ?></label>
        <br>
        <br>

        <form action="" method="GET"> 
        <label>创建文件夹：</label>
        <input type="text" name="folder">
        <input type="submit" value="创建">
        </form>

        <form action="" method="POST" enctype="multipart/form-data">
        <input type="file" name="file">
        <input type="submit" name="submit" value="上传">
        </form>

        <label style="font-size: 20px;"><?php echo($_GET['path'])?></label>

        <?php
        $d = scandir($_GET['path']);
        $c = 0;

        echo "<div>";

        foreach($d as $value):
        ?>
        <?php
        if(
        $value == '$RECYCLE.BIN' ||
        $value == 'System Volume Information' || 
        $value == 'Thumbs.db'||
        $value == 'index.php'||
        $value == 'file.png'||
        $value == 'folder.png'||
        $value == '.'||
        $value == '..'
        )
        {
            continue;
        }
        $pp = $_GET['path'].$value;
        $pp1 = str_replace(getcwd(),"",$pp);

        if(strpos($value,'.')!="")
        {
            #echo("<img src=\"file.png\"><a href=\"$pp1\">$value</a> <a id=\"item\" href=\"?delete=$pp1\">删除</a>");
            #echo("<div id=\"d\"><img src=\"file.png\" id=\"ico\"><a href=\"$pp1\" id=\"lab\">$value</a> <a id=\"item\" href=\"?delete=$pp1\">删除</a></div>");
            $bname = substr($value,strrpos($value,"."));
            if($bname == '.jpg')
            {
                echo "<li>";
                echo "<a href=\"$pp1\">";
                echo "<img src=\"$pp1\"></a>";
                echo "<br><p class=\"c\">$value</p>";
                echo "</li>";
            }else
            {
                echo "<li>";
                echo "<a href=\"$pp1\">";
                echo "<img src=\"file.svg\"></a>";
                echo "<br><p class=\"c\">$value</p>";
                echo "</li>";
            }
        }else   
        {
            #echo("<img src=\"file.png\"><a href=\"?path=$pp\\\">$value</a> <a id=\"item\" href=\"?delete=$pp\">删除</a>");
            #echo("<div id=\"d\"><img src=\"folder.png\" id=\"ico\"><a href=\"?path=$pp\\\" id=\"lab\">$value</a> <a id=\"item\" href=\"?delete=$pp\">删除</a></div>");
            echo "<li>";
            echo "<a href=\"?path=$pp\\\">";
            echo "<img src=\"folder.svg\"></a>";
            echo "<br><p class=\"c\">$value</p>";
            echo "</li>";
        }   
        $c++;
        ?>
        <?php 
        endforeach;

        if($c == 0)
        {
            echo("<label>目录是空的</label>");
        }
        ?>

        <style>
        .c
        {
            width: 100%;
            overflow: hidden;
            text-overflow: ellipsis;
            text-align: center;
            white-space: nowrap;
        }
        li
        {
            list-style: none;
            float: left;
            width: 60px;
            height: 100px;
            overflow: hidden;
            padding: 10px
        }
        li img
        {
        width: 100%;
        height: 50%;
        object-fit: contain;
        overflow: hidden;
        }
        </style>
    </body>
</html>
<?php endif?>

<?php if(!$logined):?>
<html>
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width">
</head>
<body>
    <div style="text-align: center; top: 50%; position: absolute; left: 50%; margin-left: -167.39px; margin-top: -60.5px;">
        <label style="font-family: Microsoft YaHei; font-size: 48px;">Fanfa Ni的网盘</label>
        <hr style="visibility: hidden;">
        <form style="text-align: center;" action="" method="POST">
            <input type="password" name="password">
            <input type="submit" value="登录">
        </form>
    </div>
</body>
</html>
<?php endif?>