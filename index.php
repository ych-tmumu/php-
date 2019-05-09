<?php 

@header('Content-type: text/html;charset=UTF-8');
//php 7
//实现文件的写入操作
//http:/ /test.tmumu.cn/?bookName=西游记&author=吴承恩&introduct=这是一部很不错的小说
//遇到了一个问题，就是在google浏览器上刷新的时候,会看到你的txt文件里面插入了两条数据
//可能是安装了某些插件，他又刷新了一遍造成的
//json_encode 需要用 JSON_UNESCAPED_UNICODE才能使汉字正常
try{
    $dataPath = "data.txt";
    $file = fopen($dataPath, "a+");
    $data = new \stdClass();
    $data->bookName = $_GET['bookName'] ?? "空书名";
    $data->author = $_GET['author'] ?? "空作者";
    $data->date = date("Y-m-d H:i:s", time());
    $introduct = $_GET['introduct'] ?? '';
    if (strlen($introduct) > 10){
        //限制字数，注意使用mb_substr
        $introduct = mb_substr($introduct, 0, 10);
    }
    $data->introduct = $introduct;
    $msg = json_encode($data, JSON_UNESCAPED_UNICODE) . PHP_EOL;
    $result = fwrite($file, $msg);
    
    if(gettype($result)== "boolean"){//写入失败
        throw new \Exception("告诉你一声：文件写入失败哟");
    }else{
        echo "成功写入 </br>";
       
    }
    fclose($file);
    //这里实现以下读文件
    $file = fopen($dataPath, "a+");
    while(($row=fgets($file)) != false ){ //按行读,直到结束
        $readData = json_decode($row);
        echo "书名：$readData->bookName 作者：$readData->author </br>";
    }

    //记得关闭
    fclose($file);
     
}
catch(Exception $e){
    echo $e->getMessage();
}
?>