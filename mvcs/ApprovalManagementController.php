<?php
/**
 * Create by PhpStorm
 * User: zat
 * Date: 2021-02-03
 * Time: 10:02
 */

namespace app\controllers;
use app\services\ApprovalManagementServices;
use Yii;


/**
 * ApprovalManagement
 * @package app\controllers
 */
class ApprovalManagementController extends BaseController
{
    public $layout;
    public $_uid;
    public $_username;
    public $service;

    public function init ()
    {
        $this->layout = 'iframe';
        $this->service = new ApprovalManagementServices();
        $this->_uid = Yii::$app->session->get('ur_uid');
        $this->_username = Yii::$app->session->get('username');
        return parent::init(); // TODO: Change the autogenerated stub
    }


    public function actionIndex()
    {
        $get = Yii::$app->request->get();
        //获取列表信息
        $result = $this->service->getIndexList($get);

        if(Yii::$app->request->isGet){
            return $this->render('index',[
                'count' => $result[0],
                'pages' => $result[1],
                'list' => $result[2],
                'model' => $result[3],
                'option' => $get,
                'ur_uid' => $this->_uid,
                'username' => $this->_username,
            ]);
        }
    }


    public function actionView()
    {
        $id  = Yii::$app->request->get('id');

        if(!$id) {
            return false;
        }

        //获取详情
        $model = ApprovalManagementServices::getDetail($id);

        //操作日志
        $remarklog = ApprovalManagementServices::getRemarkLog($id);

        return $this->render('view', [
                'model' => $model,
                'remarklog' => $remarklog,
                'ur_uid' => $this->_uid,
                'username' => $this->_username,
            ]
        );
    }


    public function actionCreate()
    {
        if(Yii::$app->request->isGet){
            return $this->render('create');
        }

        if(Yii::$app->request->isPost){
            $post = Yii::$app->request->post();
            return $this->service->dealCreate($post);
        }
    }


    public function actionUpdate()
    {
        if (Yii::$app->request->isGet) {
            $get = Yii::$app->request->get();
            $model = ApprovalManagementServices::getDetail($get['id']);
            return $this->render('update', [
                'model' => $model
            ]);

        }
        if (Yii::$app->request->isPost) {
            $post = Yii::$app->request->post();
            return $this->service->dealUpdate($post);
        }
    }


    public function actionDelete()
    {
        $id = Yii::$app->request->post('id');
        return $this->service->dealDelete($id);
    }




}