<?php
$logined = false;
$password = "nifanfa1234";

if($_POST['value'] != null)
{
    if(!$logined)
    {
        if($_POST['value'] !== $password)
        {
            echo '<script>alert("密码错误")</script>';
        }else
        {
            $logined = true;
        }
    }
}

move_uploaded_file($_FILES["file"]["tmp_name"], "upload/" . $_FILES["file"]["name"]);
?>

<html>
<head>
   <meta charset="utf-8">
</head>
<body>
    <?php if(!$logined):?>

    <form action="" method="POST">
        <labbel>密码：</label>
        <input type="text" name="value">
        <input type="submit" name="submit">
    </form>

    <?php endif;?>

    <?php if($logined):?>
        <h1><?php echo $_SERVER['REMOTE_ADDR']?></h1>
        <label><?php
        $freespc = disk_free_space(getcwd())/(1024*1024*1024);
        echo substr($freespc,0,strpos($freespc,'.')+3).'GB可用';
        ?></label>
        <hr>
        <form enctype="multipart/form-data" method="POST" action="">
        <input type="file" name="file" id="file">
        <input type="submit" name="submit" value="上传">   
        </form>

       <?php
       $Files = scandir(getcwd().'/upload');
       foreach ($Files as $value):
       ?>

       <?php 
       if($value!=="."&&$value!=="..")
       {
          echo"<a href=\"upload/$value\">$value</a><br>";
       }
       ?>
       <?php endforeach;?>
    <?php endif;?>
</body>
</html>