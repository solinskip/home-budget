function showalert(message, alerttype) {
    $('#alerts').append('<div id="alertdiv" class="alertremove alert ' + alerttype + '"><a class="close" data-dismiss="alert">×</a><span>' + message + '</span></div>')
}
function removealert() {
    $('.alertremove').remove();
}

/**
 * Load ajax content to view
 * Usage:
 *
 <div class="content" id="sub-content">;
 <?= $content ?>
 </div>
 *
 * Value from link with class loadAjax is loaded to div#main-content
 *
 Html::a('<span uk-icon="icon:{ICON}"></span>', false, ['class' => 'loadAjaxSub', 'value' => Url::to([''])]);
 *
 */

$(document).on('click', '.loadAjax', function () {
    $('#ajax-content').html('<div class="text-center"><i class="fas fa-spin fa-cog"></i></div>');
    $('#ajax-content').load($(this).attr('value'));
});

/**
 * Load ajax content to modal
 * Usage:
 *      <? Modal::begin([
 *          'options' => [
 *              'tabindex' => false,
 *           ],
 *          'header' => '<h4 id="modalHeaderTitle"></h4>',
 *          'headerOptions' => ['id' => 'modalHeader'],
 *          'id' => 'modalAjax',
 *          'footer' => '',
 *          'clientOptions' => ['backdrop' => 'static', 'keyboard' => FALSE]
 *      ]) ?>
 *      <div class="modalAjaxContent"></div>
 *      <? Modal::end(); ?>
 *
 *      <?= Html::a(Yii::t('', ''), FALSE, ['value' => $URL, 'class' => 'loadAjaxContent btn btn-primary', 'icon' => '<i class="fa fa-tasks"></i>', 'modaltitle' => Yii::t('', '')]); ?>
 */

$(document).on('click', '.loadAjaxContent', function () {
    showAjaxModal($(this).attr('value'), $(this).attr('icon'), $(this).attr('modaltitle'));
});

function showAjaxModal(viewUrl, modalIcon, modalTitle) {
    $('.modalAjaxContent').html('<div class="text-center"><i class="fas fa-spin fa-cog fa-2x"></i></div>');
    var modal = $('#modalAjax');
    modal.show();
    if (modal.data('bs.modal').isShown) {
        modal.find('.modalAjaxContent')
            .load(viewUrl);
        document.getElementById('modalHeaderTitle').innerHTML = modalIcon + ' ' + modalTitle;
    } else {
        modal.modal('show')
            .find('.modalAjaxContent')
            .load(viewUrl);
        document.getElementById('modalHeaderTitle').innerHTML = modalIcon + ' ' + modalTitle;
    }
}