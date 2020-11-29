<?php
$logined = false;
$password = "nifanfa1234";

if($_COOKIE['password'] == $password)
{
    $logined = true;
}else
{
    #例外
    if(strpos($_SERVER['HTTP_USER_AGENT'],"OPPO A57") != "")
    {
    setcookie("password",$password,time()+300);
    echo('<script>alert("欢迎回来，李红");document.location.href = document.referrer;</script>');
    }
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
        <script>
        function selectandupload()
        {
        var select = document.getElementById("select");
        select.click();
        select.addEventListener("change",function()
        {
            document.getElementById("submit").click();
        });
        }
        function createnewfolder()
        {
        var name = prompt("文件夹名称：");
        var select = document.getElementById("newfoldername");
        select.value = name;
        document.getElementById("createfolder").click();
        }
        </script>
    </head>
    <body>
        <label style="font-size: 24px; font-family: Microsoft YaHei;"><?php 
        $freespace = disk_free_space(getcwd())/(1024*1024*1024);
        $space = disk_total_space(getcwd())/(1024*1024*1024);
        echo(substr($freespace,0,strpos($freespace,"."))."GB"."/".substr($space,0,strpos($space,"."))."GB");
        $availablepercent = 100-(substr($freespace,0,strpos($freespace,".")) / substr($space,0,strpos($space,"."))*100);
        echo "<div style=\"width: 300px;height: 10px;background:rgb(227, 227, 227);border-radius: 5px;overflow: hidden;\"><div style=\"width: $availablepercent%;height: 10px;background: green\"></div></div>"
        ?></label>
        <br>

        <form hidden="true" action="" method="GET"> 
        <input id="newfoldername" type="text" name="folder">
        <input id="createfolder" type="submit" value="创建">
        </form>

        <form hidden="true" action="" method="POST" enctype="multipart/form-data">
        <input id="select" type="file" name="file">
        <input id="submit" type="submit" name="submit" value="上传">
        </form>
        
        <div style="position: fixed; right: 50px; bottom: 50px;">
        <button onclick="createnewfolder();" style="box-shadow: 4px 4px 12px rgb(136,136,136); float: left; width: 80px; height: 80px; border-radius: 50%; background-color: rgb(238,238,238); color: white; border: none; font-size: 250%; outline: none;"><img style="height: 50%;" src="/createfolder.svg"></button>
        <br>
        <button style="float: left; visibility: hidden; width: 25px; height: 25px;"></button>
        <br>
        <button onclick="selectandupload();" style="box-shadow: 4px 4px 12px rgb(136,136,136); float: left; width: 80px; height: 80px; border-radius: 50%; background-color: rgb(245,0,87); color: white; border: none; font-size: 250%; outline: none;">+</button>
        </div>

        <label style="font-size: 20px; font-family: Microsoft YaHei;"><?php echo($_GET['path'])?></label>

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
        $value == 'file.svg'||
        $value == 'folder.svg'||
        $value == 'createfolder.svg'||
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
            $bname = strtolower(substr($value,strrpos($value,".")));
            if(
                $bname == '.jpeg'||
                $bname == '.jpg'||
                $bname == '.png'||
                $bname == '.gif'||
                $bname == '.bmp'
                )
            {
                echo "<li>";
                echo "<a href=\"$pp1\">";
                echo "<img src=\"$pp1\"></a>";
                echo "<br><p class=\"c\" style=\"font-family: Microsoft YaHei;\">$value</p>";
                echo "</li>";
            }else
            {
                echo "<li>";
                echo "<a href=\"$pp1\">";
                echo "<img src=\"file.svg\"></a>";
                echo "<br><p class=\"c\" style=\"font-family: Microsoft YaHei;\">$value</p>";
                echo "</li>";
            }
        }else   
        {
            #echo("<img src=\"file.png\"><a href=\"?path=$pp\\\">$value</a> <a id=\"item\" href=\"?delete=$pp\">删除</a>");
            #echo("<div id=\"d\"><img src=\"folder.png\" id=\"ico\"><a href=\"?path=$pp\\\" id=\"lab\">$value</a> <a id=\"item\" href=\"?delete=$pp\">删除</a></div>");
            echo "<li>";
            echo "<a href=\"?path=$pp\\\">";
            echo "<img src=\"folder.svg\"></a>";
            echo "<br><p class=\"c\" style=\"font-family: Microsoft YaHei;\">$value</p>";
            echo "</li>";
        }   
        $c++;
        ?>
        <?php 
        endforeach;

        if($c == 0)
        {
            echo("<label>目录是空的</label>");
        }else
        {
            echo "<li style=\"height: 55%; position: relative;\"></li>";
        }

        echo "</div>"
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
<form hidden="true" style="text-align: center;" action="" method="POST">
    <input id="password" type="text" name="password">
    <input id="login" type="submit" value="登录">
</form>
<script>
    var name = prompt("密码：");
    var p = document.getElementById("password");
    p.value = name;
    document.getElementById("login").click();
</script>
</body>
</html>
<?php endif?>
