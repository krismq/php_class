<?php
/**
 * @application : 检测上传文件的真实类型
 * @description : 在验证文件上传步骤中，应是表单中file提交后，后端验证上传时产生的临时文件(即$_FILES信息中的'tmp_name'),若通过类型验证，则允许上传到特定上传目录中(即调用move_uploaded_file函数)
 * @return array
 */
class FileType
{
    public function checkFileHeader($filename) 
    {
        try {
            $file = @fopen($filename, "rb");
            $byte = @fread($file, 2); //只读2字节
            if ( empty($byte) ) {
                return [
                    'status' => 'fail',
                    'msg'    => 'file does not exist',  //文件不存在
                ];
            }
            @fclose($file);
            $info = @unpack("C2chars", $byte);
            $typeCode = intval($info['chars1'] . $info['chars2']);
            /**
             * switch中的case值对应的文件类型应自行验证，不确保准确性(输出typeCode试验)。
             */
            switch ( $typeCode ) {
                case 208207: {
                        $fileType = 'doc';  //excel也是208207
                        break;
                    }
                case 8075: {
                        $fileType = 'docx'; //zip,xlsx文件也是8075,docx格式的文件本质上是一个ZIP文件
                        break;
                    }
                case 255216: {
                        $fileType = 'jpg';  //
                        break;
                    }
                case 13780: {
                        $fileType = 'png';  //
                        break;
                    }
                case 3780: {
                        $fileType = 'pdf';  //
                        break;
                    }
                case 7173: {
                        $fileType = 'gif';  //
                        break;
                }
                case 5666: {
                        $fileType = 'psd';  //
                        break;
                }
                case 210187: {
                        $fileType = 'txt';  //此条验证不符合,测试发现不同txt的typeCode不相同
                        break;
                }
                case 8297: {
                        $fileType = 'rar';  //
                        break;
                }
                default : {
                    $fileType = '';
                    break;
                }
            }
        } catch (Exception $ex) {
            $fileType = '';
        }
        /**
         * 返回文件信息
         */
        if ( !empty($fileType) ) {
            return [
                'status'   => 'success',    //验证成功
                'fileType' => $fileType,    //文件类型
                'fileCode' => $typeCode,    //类型值
            ];
        } else {
            return [
                'status'   => 'fail',               //验证失败
                'msg'      => 'Unknown File Type',  //未知的文件类型
            ];
        }
    }
}
/**
 * 调用
 */
$fileUrl = 'NBB2.jpg';  //要验证的文件路径
$fileObj = new FileType();
$type = $fileObj->checkFileHeader($fileUrl);

var_dump($type);