<?php
namespace app\controllers;

use Yii;
use yii\helpers\Json;


/**
 * 本地脚手架控制器
 */

class LocalController extends BaseController
{

    public function init(){
        return parent::init();
    }

    /**
     * 1.先建表oop
     * 2.请求方法 http://omsgj.test/index.php?r=local/generate-project&name=Oop
     * 3.name后面的参数为驼峰格式
     * 4.注意需在本地生成 , 不可提交脚手架
     * @author zat     2021-02-05
     * @throws \yii\db\Exception
     */

    public function actionGenerateProject()
    {
        $get = Yii::$app->request->get();
        $model_name = $get['name'];
        $sql = "select COLUMN_NAME from information_schema.COLUMNS where table_name = '".strtolower($model_name)."'";
        $result = Yii::$app->db->createCommand($sql)->queryAll();
        $column = '';
        foreach ( $result as $key => $value ) {
            $column .= "'".$value['COLUMN_NAME']."',";
        }

        $origin_dir = $_SERVER['DOCUMENT_ROOT'].'/mvcs';
        $model_dir = dirname(dirname(__FILE__)).'\\models\\'.$model_name.'.php';
        $controller_dir = dirname(dirname(__FILE__)).'\\controllers\\'.$model_name.'Controller.php';
        $service_dir = dirname(dirname(__FILE__)).'\\services\\'.$model_name.'Services.php';
        $view_dir = dirname(dirname(__FILE__)).'\\views\\'.strtolower($model_name);

        if (!is_dir($view_dir)) {
            @mkdir($view_dir);
        }else{
            echo $view_dir.'目录已存在!请核实';
            return;
        }

        file_put_contents($model_dir,str_replace('ApprovalManagement',$model_name,file_get_contents($origin_dir.'/ApprovalManagement.php')));
        file_put_contents($model_dir,str_replace('model_column',$column,file_get_contents($model_dir)));
        file_put_contents($controller_dir,str_replace('ApprovalManagement',$model_name,file_get_contents($origin_dir.'/ApprovalManagementController.php')));
        file_put_contents($service_dir,str_replace('ApprovalManagement',$model_name,file_get_contents($origin_dir.'/ApprovalManagementServices.php')));
        file_put_contents($view_dir.'\\index.php',file_get_contents($origin_dir.'/approval-management/index.php'));
        file_put_contents($view_dir.'\\view.php',file_get_contents($origin_dir.'/approval-management/view.php'));
        file_put_contents($view_dir.'\\create.php',file_get_contents($origin_dir.'/approval-management/create.php'));
        file_put_contents($view_dir.'\\update.php',file_get_contents($origin_dir.'/approval-management/update.php'));

        echo 'OK';
    }

}