<?php
use app\components\helpers\Helper;

?>
<style type="text/css">
    .table tbody tr:hover td, .table tbody tr:hover th {
        background: none;
    }
    h2{
        margin-bottom: 10px;
        font-family: monospace;
        color: #7f7f7f;
        font-size: 20px;
        font-weight: bold;
    }
    .title{
        margin-bottom: 15px;
        margin-top: 15px;
        font-size: 16px;
        height: 50px;
        line-height: 50px;
        margin-left: 15px;
    }
    .label_one{
        position: absolute;
        width: 35px;
        height: 35px;
        display: inline-block;
        background-size: 35px 35px;
        background-image: url("<?php echo Yii::$app->params['staticUrl'];?>img/img/label_one.png");
        margin-left: -15px;
        margin-top: 8px;
    }
    table tr td{
        font-size: 13px;
        height: 35px;
    }
    .row_desc{
        margin-top: 0px;
        margin-bottom: 10px;
    }
    .btnConfirm{
        margin-left: 20px;
        background-color: white;
        color: #505050;
        border-color: #bfbfbf;
    }
    .tip_img{
        width: 36px;
    }
    .img_arrow{
        width: 60px;
        height: 15px;
        margin-left: 30px;
        margin-right: 30px;
        margin-top: 13px;
    }
    .timeline{
        float: left;
        text-align: center;
    }
    .tip_arrow{
        margin-top: 10px;
        margin-bottom: -20px;
    }

</style>
<div class="container">
    <div class="row span24 doc-content">
        <h2><?php echo Yii::t('common','审批单详情')?></h2>
        <div class="row_desc">
            <span class="label_one"></span><div class="title"><?php echo Yii::t('common','审批单详情')?></div>
            <table cellspacing="0" class="table table-bordered">
                <tr>
                    <td style="width:12%;"><?php echo Yii::t('common','审批编号'); ?></td>
                    <td style="width:12%;" class="td-content"><?php echo  $model->am_no; ?></td>
                    <td style="width:12%;"><?php echo Yii::t('common','类型'); ?></td>
                    <td style="width:12%;" class="td-content"><?php echo  $model->at_name; ?></td>
                    <td style="width:12%;"><?php echo Yii::t('common','申请人'); ?></td>
                    <td style="width:12%;" class="td-content"><?php echo  $model->am_applicant_name; ?></td>
                    <td style="width:12%;"><?php echo Yii::t('common','提交时间'); ?></td>
                    <td style="width:12%;" class="td-content"><?php echo date('Y-m-d H:i:s',$model->am_submission_time); ?></td>
                </tr>
                <tr>
                    <td><?php echo Yii::t('common','状态'); ?></td>
                    <td class="td-content"><?php echo ($model->am_visible == 1) ?  $model->am_statuss[  $model->am_status ] : 'Revoked'; ?></td>
                    <td><?php echo Yii::t('common','审批人'); ?></td>
                    <td class="td-content"><?php echo  $model->am_approve_now; ?></td>
                    <td><?php echo Yii::t('common','审批时间'); ?></td>
                    <td class="td-content" colspan="3"><?php  echo  $model->am_lastupdatime ? date('Y-m-d H:i:s',$model->am_lastupdatime) : '';?></td>
                </tr>
                <tr>
                    <td><?php echo Yii::t('common','审批内容'); ?></td>
                    <td class="td-content" colspan="7"><?php echo  $model->am_detail; ?></td>
                </tr>
                <tr>
                    <td><?php echo Yii::t('common','审批原因'); ?></td>
                    <td class="td-content" colspan="7"><?php echo  $model->am_remark; ?></td>
                </tr>
                <tr>
                    <td><?php echo Yii::t('common','附件信息'); ?></td>
                    <td class="td-content" colspan="7">
                        <div class="files" id="files">
                            <span><?php echo Yii::t('common','图片加载中，请稍后')?>...</span>
                            <?php foreach ($model->attach as $value) { ?>
                                <p>
                                    <a <?php if(preg_match('/.*(\.png|\.jpg|\.jpeg|\.gif)$/', $value['at_filepath'])){ echo 'data-magnify="gallery"';}else{ echo 'target="_black"';} ?> href="<?php echo $value['at_filepath']?>" ><?php echo $value['at_filename'];?></a>
                                    <?php echo $value['at_filesize']; ?>KB <?php echo $value['at_create_username']; ?>
                                    <?php echo $value['at_createtime']; ?>
                                    <a href="/index.php?r=common/download&at_id=<?php echo $value['at_id']; ?>">Download</a>
                                </p>
                            <?php } ?>
                    </td>
                </tr>
            </table>
        </div>

        <!--操作按钮-->
        <div class="row show-grid">
            <div class="form-actions">
                <?php if ( in_array($model->am_visible,[1]) ) { ?>
                    <?php if(Helper::hasAccess('approval-management', 'approve', Yii::$app->user->identity->getPrivilege()) && Helper::hasAccess('approval-management', 'reject', Yii::$app->user->identity->getPrivilege()) && in_array($username,[$model->am_approve_now]) && in_array($model->am_status,[1])  ) { ?>
                        <button class="button button-primary" onclick=" approve(<?php echo $model->am_id; ?>) " type="button">Approve</button>
                        <button class="button button-danger" onclick=" reject(<?php echo $model->am_id; ?>) " type="button">Reject</button>
                    <?php }?>
                    <?php if(Helper::hasAccess('approval-management', 'revoke', Yii::$app->user->identity->getPrivilege()) && in_array($ur_uid,[$model->am_applicant_id]) && in_array($model->am_status,[1])  ) { ?>
                        <button class="button " onclick=" revoke(<?php echo $model->am_id; ?>) " type="button">Revoke</button>
                    <?php }?>
                <?php } ?>
            </div>
        </div>

        <!--审批时间轴-->
        <div class="row_desc">
            <span class="label_one"></span><div class="title"><?php echo Yii::t('common','审批流程')?></div>
            <br>
            <div class="timeline">
                <img class="img_wait tip_img" src="/static/img/img/pass.png" alt=""> <br>
                <p class="tip_arrow"><?php echo $model->am_applicant_name; ?> ▪ Submitted </p><br>
                <p class="tip_arrow"><?php echo date('Y-m-d H:i:s',$model->am_submission_time); ?></p>
            </div>
            <div class="timeline">
                <img class="img_arrow " src="/static/img/img/arrow.png" alt="">
            </div>

            <?php foreach ($list as $key => $value) { ?>
                <div class="timeline">
                    <img class="img_wait tip_img" src="/static/img/img/<?php echo $value['status']; ?>.png" alt=""> <br>
                    <p class="tip_arrow"><?php echo $value['am_approver']; ?>
                        <?php if ( $value['status'] == 'pass' ) { ?>
                        ▪ Approved </p><br>
                    <p class="tip_arrow"><?php echo date('Y-m-d H:i:s',$value['time']); ?></p>
                    <?php } elseif( $value['status'] == 'reject' ) { ?>
                        ▪ Rejected </p><br>
                        <p class="tip_arrow"><?php echo date('Y-m-d H:i:s',$value['time']); ?></p>
                        <?php break;?>
                    <?php } ?>
                </div>
                <div class="timeline">
                    <?php if ( $key < count($list)-1 ) { ?>
                        <img class="img_arrow " src="/static/img/img/arrow.png" alt="">
                    <?php } ?>
                </div>
            <?php } ?>
        </div>
    </div>

    <!--审批记录-->
    <div class="row span24 doc-content " style="margin-top: 3%;margin-left: auto;">
        <div class="row_desc" >
            <span class="label_one"></span><div class="title"><?php echo Yii::t('common', '操作记录') ?></div>
            <table cellspacing="0" class="table table-bordered">
                <thead>
                <tr style="background-color: #f2f2f2;">
                    <th style="width:15%"><?php echo Yii::t('common', '操作时间') ?>  </th>
                    <th style="width:15%"><?php echo Yii::t('common', '操作人') ?> </th>
                    <th style="width:15%"><?php echo Yii::t('common', '操作') ?> </th>
                    <th style="width:55%"><?php echo Yii::t('common', '备注') ?> </th>
                </tr>
                </thead>
                <tbody>
                <?php foreach ($remarklog as $value) { ?>
                    <tr>
                        <td><?php echo date('Y-m-d H:i:s',$value['arl_createtime']); ?></td>
                        <td><?php echo $value['username']; ?></td>
                        <td><?php echo $approval_remark_subtype[ $value['arl_subtype'] ]; ?></td>
                        <td><?php echo $value['arl_remark']; ?></td>
                    </tr>
                <?php } ?>
                </tbody>
            </table>
        </div>
    </div>

</div>
</div>

<!---备注-->
<div id="wr_time_update" class="hide">
    <div style="height: 210px; overflow: auto;">
        <label class="control-label control-label-auto"><?php echo Yii::t('common','备注')?></label>
        <textarea id="arl-remark" name="arl_remark" style="width: 250px;" class="control-row2 input-large bui-form-field bui-form-field-hover" aria-disabled="false" aria-pressed="false"></textarea>
    </div>
</div>

<?php include __DIR__ . '/../layouts/bui_footer.php'; ?>
<script type="text/javascript" src="<?php echo Yii::$app->params['staticUrl']; ?>js/js/jquery.magnify.js?v=13" ></script>
<link rel="stylesheet" href="<?php echo Yii::$app->params['staticUrl']; ?>css/css/jquery.magnify.css?v=912">

<script>
    //图片首次加载隐藏，加载完在显示
    $(".files p").hide();
    $(document).ready(function(){
        $(".files span").css("display","none");
        $(".files p").show();

        //浏览器通知清除
        let notice_type = getUrlParam('type');
        let am_id = getUrlParam('am_id');
        if(notice_type == 'push'){
            let url = C.getUrl('common','remove-approval-notice');
            let data = {'am_id':am_id};
            window.parent.sendSockTable({"code":4001,"to_id":am_id,"op_type":9});
            $.post(url,data);
        }
    });
    //图片显示设置
    $('[data-magnify]').magnify({
        fixedContent: false
    });

    //撤销
    function revoke(am_id){
        C.confirm("<?php echo Yii::t('common','Are you sure to revoke the approval')?>？", function () {
            var url = C.getUrl("approval-management", "revoke");
            C.request_post(url, {am_id: am_id});
        })
    }

    //通过
    function approve(am_id){
        omsDialog.showDialog('Approve', 350, 200, 'wr_time_update', function () {
            var url = C.getUrl("approval-management", "approve");
            var remarks = $("#arl-remark").val();
            C.request_post(url, { 'am_id' : am_id , 'remark' : remarks });
        });
    }

    //驳回
    function reject(am_id){
        omsDialog.showDialog('Reject', 350, 200, 'wr_time_update', function () {
            var url = C.getUrl("approval-management", "reject");
            var remarks = $("#arl-remark").val();
            C.request_post(url, { 'am_id' : am_id , 'remark' : remarks });
        });
    }

    //获取url中的参数
    function getUrlParam(name) {
        var reg = new RegExp("(^|&)" + name + "=([^&]*)(&|$)"); //构造一个含有目标参数的正则表达式对象
        var r =  window.location.search.substr(1).match(reg);  //匹配目标参数
        if (r != null) return encodeURI(r[2]);
        return null; //返回参数值
    }

</script>