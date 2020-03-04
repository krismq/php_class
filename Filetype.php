<?php
/**
 * @application : ����ϴ��ļ�����ʵ����
 * @description : ����֤�ļ��ϴ������У�Ӧ�Ǳ���file�ύ�󣬺����֤�ϴ�ʱ��������ʱ�ļ�(��$_FILES��Ϣ�е�'tmp_name'),��ͨ��������֤���������ϴ����ض��ϴ�Ŀ¼��(������move_uploaded_file����)
 * @return array
 */
class FileType
{
    public function checkFileHeader($filename) 
    {
        try {
            $file = @fopen($filename, "rb");
            $byte = @fread($file, 2); //ֻ��2�ֽ�
            if ( empty($byte) ) {
                return [
                    'status' => 'fail',
                    'msg'    => 'file does not exist',  //�ļ�������
                ];
            }
            @fclose($file);
            $info = @unpack("C2chars", $byte);
            $typeCode = intval($info['chars1'] . $info['chars2']);
            /**
             * switch�е�caseֵ��Ӧ���ļ�����Ӧ������֤����ȷ��׼ȷ��(���typeCode����)��
             */
            switch ( $typeCode ) {
                case 208207: {
                        $fileType = 'doc';  //excelҲ��208207
                        break;
                    }
                case 8075: {
                        $fileType = 'docx'; //zip,xlsx�ļ�Ҳ��8075,docx��ʽ���ļ���������һ��ZIP�ļ�
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
                        $fileType = 'txt';  //������֤������,���Է��ֲ�ͬtxt��typeCode����ͬ
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
         * �����ļ���Ϣ
         */
        if ( !empty($fileType) ) {
            return [
                'status'   => 'success',    //��֤�ɹ�
                'fileType' => $fileType,    //�ļ�����
                'fileCode' => $typeCode,    //����ֵ
            ];
        } else {
            return [
                'status'   => 'fail',               //��֤ʧ��
                'msg'      => 'Unknown File Type',  //δ֪���ļ�����
            ];
        }
    }
}
/**
 * ����
 */
$fileUrl = 'NBB2.jpg';  //Ҫ��֤���ļ�·��
$fileObj = new FileType();
$type = $fileObj->checkFileHeader($fileUrl);

var_dump($type);