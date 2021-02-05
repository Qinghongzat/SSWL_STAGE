<?php
use app\components\helpers\Helper;
use yii\widgets\LinkPager;
?>
<style>
    .btnSearch{
        margin-left: 35px;
    }
    .btnSearch,.btnReset{
        margin-top: 10px;
    }
    button img{
        width: 20px;
    }
    .search-control220{
        width:280px;
    }
    .form-horizontal .control-label-auto{
        width: 150px;
    }
    .doc-content table thead tr:last-child th{
        font-size: 14px;
        background: #f2f2f2;
        font-weight: inherit;
        border-bottom: 1px solid rgb(221, 221, 221);
        height: 24px;
    }

    .form-row-dec{
        margin-left: -60px;
    }

</style>
<div class="container">
    <div class="row span24">
        <form class="searchForm  well form-horizontal " action="/index.php" method="GET" id="searchForm">
            <input type="hidden" name="r" value="approval-management/index"/>
            <div class="row show-grid form-row form-row-dec">

                <div class="control-group span5 search-control220">
                    <label class="control-label control-label-auto"><?php echo Yii::t('common','型号'); ?></label>
                    <div class="controls">
                        <select name="at_id"  class="search-input" style="width: 125px">
                            <option value=""><?php echo  Yii::t('common','请选择')?></option>
                            <?php foreach ($am_type as $key => $value){?>
                                <option value="<?php echo $key?>" <?php if($option['at_id'] == $key){echo 'selected';}?>><?php echo $value?></option>
                            <?php }?>
                        </select>
                    </div>
                </div>
                <div class="control-group span5 search-control220">
                    <label class="control-label control-label-auto"><?php echo Yii::t('common','审核状态'); ?></label>
                    <div class="controls">
                        <select name="am_status"  class="search-input" style="width: 125px">
                            <option value=""><?php echo  Yii::t('common','请选择')?></option>
                            <?php foreach (  $model->am_statuss as $key => $value){?>
                                <option value="<?php echo $key?>" <?php if($option['am_status'] == $key){echo 'selected';}?>><?php echo $value?></option>
                            <?php }?>
                        </select>
                    </div>
                </div>
                <div class="control-group span5 search-control220">
                    <label class="control-label control-label-auto"><?php echo Yii::t('common','申请人'); ?></label>
                    <div class="controls">
                        <input type="text" id="am_applicant_name" name="am_applicant_name" value="">
                        <input type="hidden" id="am_applicant_id" name="am_applicant_id" value="">
                    </div>
                </div>
                <div class="control-group span12 search-control220" style="width: 430px">
                    <label class="control-label control-label-auto"><?php echo Yii::t('common','提交时间'); ?></label>
                    <div class="controls">
                        <input name="start_time" class="control-text calendar" type="text" style="width:115px" value="<?php echo $option['start_time']?>">
                        <label>-</label>
                        <input name="end_time" class="control-text calendar" type="text" style="width:115px" value="<?php echo $option['end_time']?>">
                    </div>
                </div>

            </div>

            <div class="row show-grid form-row form-row-dec">
                <div class="control-group span5 search-control220">
                    <label class="control-label control-label-auto"><?php echo Yii::t('common','审批人'); ?></label>
                    <div class="controls">
                        <input type="text" id="am_approver" name="am_approver" value="">
                        <input type="hidden" id="am_approver_id" name="am_approver_id" value="">
                    </div>
                </div>
                <div class="control-group span5 search-control220">
                    <label class="control-label control-label-auto"><?php echo Yii::t('common','运单编号'); ?></label>
                    <div class="controls">
                        <input type="text" class="control-text" value="<?php echo $option['am_to_no']; ?>" name="am_to_no"/>
                    </div>
                </div>
            </div>

            <div class="row show-grid form-row">
                <div class="form-actions">
                    <button class="button button-primary btnSearch"
                            type="submit"><?php echo Yii::t('common', '查询') ?></button>
                    <button class="button btnReset" type="button"><?php echo Yii::t('common', '重置') ?></button>
                    <input type="hidden" name="r"
                           value="<?php echo Yii::$app->controller->id; ?>/<?php echo Yii::$app->controller->action->id; ?>"/>
                </div>
            </div>
        </form>

        <div class="doc-content">
            <table cellspacing="0" class="table table-bordered  table-striped ">
                <thead>
                <tr>
                    <th style="width:10%;"><?php echo Yii::t('common','审批编号'); ?></th>
                    <th style="width:10%;"><?php echo Yii::t('common','型号'); ?></th>
                    <th style="width:10%;"><?php echo Yii::t('common','运单编号'); ?></th>
                    <th style="width:10%;"><?php echo Yii::t('common','申请人'); ?></th>
                    <th style="width:10%;"><?php echo Yii::t('common','提交时间'); ?></th>
                    <th style="width:10%;"><?php echo Yii::t('common','状态'); ?></th>
                    <th style="width:10%;"><?php echo Yii::t('common','审批人'); ?></th>
                    <th style="width:10%;"><?php echo Yii::t('common','操作'); ?></th>
                </tr>
                </thead>
                <tbody>
                <?php foreach ($list as $value) { ?>
                    <tr style=" height: 42px;">
                        <td>
                            <a href="javascript:;"
                               onclick="C.open('<?php echo Yii::t('common','审批单详情')?>', '/index.php?r=approval-management/view&am_id=<?php echo $value['am_id']; ?>')"><?php echo $value['am_no']; ?></a>
                        </td>
                        <td><?php echo $value['at_name']; ?></td>
                        <td>
                            <?php foreach ( explode(',',$value['am_to_no']) as $k => $v ) { ?>
                                <a href="javascript:;"
                                   onclick="C.open('<?php echo Yii::t('common','订单明细')?>', '/index.php?r=order/view&id=<?php echo explode(',',$value['am_to_id'])[$k]; ?>&cost_arrow=cost_arrow')"><?php echo $v; ?></a>
                            <?php } ?>
                        </td>
                        <td><?php echo $value['am_applicant_name']; ?></td>
                        <td><?php echo date('Y-m-d H:i:s',$value['am_submission_time']); ?></td>
                        <td><?php echo ($value['am_visible'] == 1) ?  $model->am_statuss[  $value['am_status'] ] : 'Revoked'; ?></td>
                        <td><?php echo $value['am_approve_now']; ?></td>
                        <td style="text-align: center">
                            <?php if(Helper::hasAccess('approval-management', 'approve', Yii::$app->user->identity->getPrivilege()) && Helper::hasAccess('approval-management', 'reject', Yii::$app->user->identity->getPrivilege()) && in_array($username,[$value['am_approve_now']]) && in_array($value['am_status'],[1])  ) { ?>
                                <button class="button button-primary" onclick=" approve(<?php echo $value['am_id']; ?>) " type="button">Approve</button>
                                <button class="button button-danger" onclick=" reject(<?php echo $value['am_id']; ?>) " type="button">Reject</button>
                            <?php }?>
                            <?php if(Helper::hasAccess('approval-management', 'revoke', Yii::$app->user->identity->getPrivilege()) && in_array($ur_uid,[$value['am_applicant_id']]) && in_array($value['am_status'],[1]) && in_array($value['am_visible'],[1])  ) { ?>
                                <button class="button " onclick=" revoke(<?php echo $value['am_id']; ?>) " type="button">Revoke</button>
                            <?php }?>
                        </td>
                    </tr>
                <?php } ?>
                </tbody>
            </table>
            <div>
                <span class="_page" style="margin-left:35%;">
                   <?php echo LinkPager::widget(['pagination' => $page, 'nextPageLabel' => Yii::t('common', '下一页'), 'prevPageLabel' => Yii::t('common', '上一页'), 'maxButtonCount' => 5, 'firstPageLabel' => Yii::t('common', '首页'), 'lastPageLabel' => Yii::t('common', '末页'),]); ?>
                </span>
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

<script type="text/javascript">

    C.pageShow(
        '<?php echo $_GET['page'] ? ($_GET['page']-1)*$page->pagesize +1 : 1 ?>',
        '<?php echo $_GET['page'] ? (
        $_GET['page']*$page->pagesize > $page->totalCount ? $page->totalCount : $_GET['page']*$page->pagesize
        ) : $page->pagesize ?>',
        '<?php echo $page->totalCount ?>')

    //重置
    $('.btnReset').click(function () {
        C.reset('searchForm');
    });

    var datepicker = new BUI.Calendar.DatePicker({
        trigger: '.calendar',
        autoRender: true,
        dateMask: 'yyyy-mm-dd',
        showTime: false
    });

    C.select_user('am_applicant_name','am_applicant_id',{"id":"<?php echo $option['am_applicant_id']; ?>",'name':"<?php echo $option['am_applicant_name']; ?>"},'common','user-search'); //获取申请人
    C.select_user('am_approver','am_approver_id',{"id":"<?php echo $option['am_approver_id']; ?>",'name':"<?php echo $option['am_approver']; ?>"},'common','user-search'); //获取审批人

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

</script>

