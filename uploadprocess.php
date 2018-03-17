<?php
	//其实我们在上传文件时，点击上传后，数据由http协议先发送到apache服务器那边，这里apache服务器已经将上传的文件存放到了服务器下的C:\windows\Temp目录下了。这时我们只需转存到我们需要存放的目录即可。

	//php中自身对上传的文件大小存在限制默认为2M

    error_reporting(0);

    include_once "common/ez_sql_mysql.php";

	//获取文件的大小
	$file_size=$_FILES['myfile']['size'];
	if($file_size>2*1024*1024) {
		echo "file size too big!";
		exit();
	}

	$filename=$_FILES['myfile']['name'];

	/* 设置允许上传文件的类型 */
    $type = array("csv");

    /* 获取文件后缀名函数 */
    function fileext($filename)
    {
        return substr(strrchr($filename, '.'), 1);
    }

    /* 判断上传文件类型 */
    if( !in_array( strtolower( fileext($filename) ),$type) )
     {
        die("It's not csv file!");      //类型不对
     }

	//判断是否上传成功（是否使用post方式上传）
	if(is_uploaded_file($_FILES['myfile']['tmp_name'])) {
		//把文件转存到你希望的目录（不要使用copy函数）
		$uploaded_file=$_FILES['myfile']['tmp_name'];

		//我们给每个用户动态的创建一个文件夹
		$user_path=getcwd();

		//$move_to_file=$user_path."/".$_FILES['myfile']['name'];
		$file_true_name=$_FILES['myfile']['name'];
		$move_to_file=$user_path."/".$file_true_name;
		//echo "$uploaded_file   $move_to_file";
		if(move_uploaded_file($uploaded_file,iconv("utf-8","gb2312",$move_to_file))) {
			echo $_FILES['myfile']['name']." upload OK</br>";

            $handle = fopen($move_to_file,"r");
            $num_row = 0;
            while ($data_csv = fgetcsv($handle, 1000, ",")) {
                if ($num_row > 3) {
                    $data_item[] = array(
                        'account'=>$data_csv[0],
                        'userId'=>$data_csv[1],
                        'uid'=>$data_csv[2],
                    );
                }
                $num_row++;
            }
            fclose($handle);

            $db = ezSQL_mysql::get_db("mpay");

            // 插入数据库
            foreach($data_item as $value)
            {
                $account = $value['account'];
                $accountid = $value['userId'];
                $uid = $value['uid'];
                $ret = $db->query("insert into account (`account`,`accountid`,`uid`) value ('$account', '$accountid', '$uid')");
            }
            $db->disconnect();
            echo "Database operate OK";

		} else {
			echo "upload fail";
		}
	} else {
		echo "upload fail";
	}
?>

