<?php
$redis = new Redis();    
$redis->connect('127.0.0.1', 6379);   

//��ȡ�ͻ�����ʵip��ַ  
function get_real_ip(){  
    static $realip;  
    if(isset($_SERVER)){  
        if(isset($_SERVER['HTTP_X_FORWARDED_FOR'])){  
            $realip=$_SERVER['HTTP_X_FORWARDED_FOR'];  
        }else if(isset($_SERVER['HTTP_CLIENT_IP'])){  
            $realip=$_SERVER['HTTP_CLIENT_IP'];  
        }else{  
            $realip=$_SERVER['REMOTE_ADDR'];  
        }  
    }else{  
        if(getenv('HTTP_X_FORWARDED_FOR')){  
            $realip=getenv('HTTP_X_FORWARDED_FOR');  
        }else if(getenv('HTTP_CLIENT_IP')){  
            $realip=getenv('HTTP_CLIENT_IP');  
        }else{  
            $realip=getenv('REMOTE_ADDR');  
        }  
    }  
    return $realip;  
}  

//���key��¼��ip�ķ��ʴ��� Ҳ�ɸĳ��û�id   
$key = get_client_ip();  //��Key��¼���ʵĴ�����Ŀǰ����IPΪ����Ҳ���԰��û�id��Ϊkey����userid_123456
  
//���ƴ���Ϊ3�Ρ�  
$limit = 3;
$time_slot = 60; //ʱ��� 
  
$check = $redis->exists($key);  
if($check){  
    $redis->incr($key);  
    $count = $redis->get($key);  
    if($count > $limit){
        //return json_encode(['flag'=>0,'msg'=>'�������ƴ���']);
        exit('�Ѿ����������ƴ���');  
    }  
}else{  
    $redis->incr($key);  
    //����ʱ��Ϊ60��   
    $redis->expire($key,$time_slot);  
}  

//debug
$count = $redis->get($key);  
echo '�� '.$count.' ������';