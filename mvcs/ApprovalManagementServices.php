<?php
/**
 * Create by PhpStorm
 * User: zat
 * Date: 2021-01-19
 * Time: 15:32
 */

namespace app\services;


use app\components\helpers\Helper;
use app\models\ApprovalManagement;
use app\models\User;
use app\models\RemarkLog;
use yii\data\Pagination;
use yii\helpers\Json;
use Yii;

class ApprovalManagementServices extends BaseService
{
    public $model;

    public function init ()
    {
        $this->model = new ApprovalManagement();
        return parent::init(); // TODO: Change the autogenerated stub
    }

    /**
     * 获取列表
     * @author zat     2021-01-22
     * @param $get
     */
    public function getIndexList($get)
    {
        $model = $this->model;
        $count = $model->getCount($get);
        $pages = new Pagination(['totalCount' => $count]);
        $pages->pageSize = 10;
        $list = $model->getList($get, $pages->limit, $pages->offset);
        $result = [$count,$pages,$list,$model];
        return $result;
    }

    /**
     * 获取详情
     * @author zat     2021-01-21
     * @param $id
     * @return ApprovalManagement|null
     */
    public static function getDetail($id)
    {
        $model =  ApprovalManagement::findOne($id);
        return $model;
    }

    /**
     * 获取日志
     * @author zat     2021-01-22
     * @param $id
     * @return array|\yii\db\ActiveRecord[]
     */
    public static function getRemarkLog($id)
    {
        $remarklog = RemarkLog::find()->where(['id' => $id,'status' => 0])->orderBy('createtime desc')->all();
        return $remarklog;
    }




    /**
     * dealCreate
     * @author zat     2021-01-23
     * @param $post
     * @return string|void
     * @throws \Throwable
     */
    public function dealCreate($post)
    {

        $transaction = Yii::$app->db->beginTransaction();
        try {
            $record = new ApprovalManagement();

            //生成单号
            $post['_no'] = self::getApprovalManagementNo();

            $_name = '';
            $post['_name'] = $_name;

            $record->setAttributes($post);
            if (!$record->validate()) {
                $error = $record->getErrors();
                $error = array_shift($error);
                return Helper::outJson('404', $error);
            }

            $record->save();

            $res = '';
            $data = '';
            ApprovalManagement::writeLog(''.$res.json_encode($data));

            RemarkLog::addRemarkLog($record->id, $record->id, '101001', $record->id, $post['remark']);

            //提交事务
            $transaction->commit();
            return Helper::outJson(8001,'Successfully',$record->id);
        } catch (\Exception $e) {
            //回滚
            $transaction->rollBack();
            return Json::encode(['code' => 404, 'msg' => '错误号:' . $e->getCode() . '<br>错误行:' . $e->getLine() . '<br>错误信息:' . $e->getMessage() . '全部信息' . $e]);
        }
    }


    /**
     * dealUpdate
     * @author zat     2021-02-04
     * @param $post
     * @return mixed
     */
    public function dealUpdate($post)
    {
        if($post['_id']){
            $model = ApprovalManagement::findOne(['_id'=>$post['_id']]);
            $model_arr = $model->toArray();
        }

        $model->setAttributes($post);
        if (!$model->validate()) {
            $error = $model->getErrors();
            $error = array_shift($error);
            return Json::encode(array('code' => 404, 'msg' => $error));
        }

        $model->save();

        if($post['_id']){
            ApprovalManagement::writeLog('修改 : '.'new data:'.json_encode($model->toArray() ).'old data:'.json_encode($model_arr) );
        }

        return Helper::outJson(8001,'successful','');
    }

    /**
     * dealDelete
     * @author zat     2021-02-04
     * @param $_id
     * @return mixed
     */
    public function dealDelete($_id)
    {
        ApprovalManagement::updateAll(['_visible'=>2],['_id'=>$_id]);

        ApprovalManagement::writeLog('删除 : '.'_id :'.$_id);

        return Json::encode(['code' => 8001, 'msg' => Yii::t('common','删除成功'), 'data' => []]);
    }


    /**
     * 生成单号
     * @author zat     2021-01-23
     * @return string
     */
    public static function getApprovalManagementNo()
    {
        //查询最近的一笔单子
        $number = '';
        $no = ''.date('Ymd',time());
        $ware = ApprovalManagement::find()->select('_no')->where(['like','_no',$no])->orderBy('_id desc')->asArray()->one();
        $num = $ware?substr($ware['_no'],10,4):'0000';
        for ($i=0;$i<4-strlen(intval($num)+1);$i++){
            $number .='0';
        }
        $wr_no = $number.intval($num+1);
        return $no.$wr_no;
    }



}