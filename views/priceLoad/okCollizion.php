<?php

use yii\helpers\Html;

$btns = <<<HERE
<table class = 'buttons_table table table-condensed'>
    <tr>
        <td class="info"><button type="button" class="btn btn-success btn-xs btn-save btn-save-full">Сохранить, запомнить</button> <button type="button" class="btn btn-success btn-xs btn-save btn-save-nofull">Сохранить, не запоминать</button></td>
        <td class="warning"><button type="button" class="btn btn-danger btn-xs btn-ignore-once">Игнорировать</button></td>
    </tr>
</table>    
HERE;
?>
<div class="orign_butons hidden"><?= $btns ?></div>


<p>
    <a href="/<?= $this->context->id ?>/finish?providerId=<?= $providerId; ?>" class="btn btn-primary finish">Завершить обработку</a>
</p>
<div id="rows">
    <div id="loading" style="display:none">Loading....</div>
</div>


<style>
    .buttons_table, .table_params {margin-bottom:0;}
    .buttons_table td, .table_params td{
        text-align: center;
        vertical-align: bottom !important;
        padding: 0 5px;
    }
    .btns_row{
        width: 465px;
    }
</style>

<script>
<?php ob_start(); ?>
    var key = '<?= $arRes['key']; ?>';
    function insertButtons(k) {
        $('[data-k="' + k + '"] .btns_row').html($('.orign_butons').html());
        $('[data-k="' + k + '"] .btn-ignore-once').click(function () {
            var k = $(this).parents('[data-k]').attr('data-k');
            var params = {key: key};
            //отправить запроса на сервер
            $.post('/<?= $this->context->id ?>/ignore-once?k=' + k,
                    params,
                    function (data) {
                        //удалить строку
                        $('[data-k="' + data.k + '"]').parents('table.table-striped').remove();
                    },
                    'json'
                    )
            return false;
        });
        $('[data-k="' + k + '"] .btn-save').click(function () {
            var k = $(this).parents('[data-k]').attr('data-k');
            var type = $(this).parents('[data-k]').find(':selected').val();
            var selects = $('[data-k1="' + k + '"] select');
            var ok = true;
            var params = {key: key, type: type};
            selects.each(function () {
                var val = $(this).find(':selected').val();
                if (val === '') {
                    ok = false;
                }
                params[$(this).attr('name')] = val;
            });
            if (!ok) {
                alert('Заполните все поля');
                return true;
            }
            var notFull = ($(this).hasClass('btn-save-nofull')) ? '&saveAccord=n' : '';
            //отправить запроса на сервер
            $.post('/<?= $this->context->id ?>/save-full?k=' + k + notFull,
                    params,
                    function (data) {
                        //удалить строку
                        $('[data-k="' + data.k + '"]').parents('table.table-striped').remove();
                    },
                    'json'
                    );
            return false;
        });
    }

    function getParamsForm(obj) {
        var goodTypeId = obj.find(':selected').attr('value');
        var k = obj.parents('[data-k]').attr('data-k');
        $('[data-k1="' + k + '"] .params').html('');
        if (goodTypeId) {
            $.getJSON('/<?= $this->context->id ?>/get-params-form',
                    {goodTypeId: goodTypeId, k: k},
            function (json) {
                var paramTable = '';
                for (var p in json.params) {
                    var select = '<option selected="" value=""> - - </option>';
                    for (var v in json.params[p].select) {
                        select = select + '<option value="' + v + '">' + json.params[p].select[v] + '</option>';
                    }
                    var parent = (json.params[p].parent) ? ' data-parent="' + json.params[p].parent + '"' : '';
                    select = '<select name="' + p + '" ' + parent + '>' + select + '</select>';
                    paramTable = paramTable + "<td class='success'>" + json.params[p].name + '<br>' + select + "</td>";
                }
                paramTable = "<table  class='table table-condensed table_params'><tr>" + paramTable + "</tr></table>";
                $('[data-k1="' + json.k + '"] .params').html(paramTable);
                $('[data-k1="' + json.k + '"] select').change(function () {
                    var parentParamVal = $(this).find(':selected').attr('value');
                    var rowDOM = $(this).parents('[data-k1]');
                    var k = rowDOM.attr('data-k1');
                    var cildren = [];
                    rowDOM.find('[data-parent="' + $(this).attr('name') + '"]').each(function () {
                        cildren.push($(this).attr('name'));
                    })
                    if (parentParamVal) {
                        for (var p in cildren) {
                            $.getJSON('/<?= $this->context->id ?>/get-child-params',
                                    {k: k, paramId: cildren[p], parentParamVal: parentParamVal},
                            function (json) {
                                var select = '<option selected="" value=""> - - </option>';
                                for (var v in json.params) {
                                    select = select + '<option value="' + v + '">' + json.params[v] + '</option>';
                                }
                                $('[data-k1="' + json.k + '"] [name="' + json.paramId + '"]').html(select);
                            }
                            );
                        }
                    } else {
                        var select = '<option selected="" value=""> - - </option>';
                        for (var p in cildren) {
                            $('[data-k1="' + json.k + '"] [name="' + cildren[p] + '"]').html(select);
                        }
                    }

                });
            }
            )
        }
    }

    engine = {
        posts: [],
        target: null,
        busy: false,
        count: 10,
        typeList: '<?= str_replace(chr(10), '', Html::dropDownList('goodType', '', $arRes['arGoodTypes'])) ?>',
        render: function (obj) {
            xhtml = "<table  class='table table-striped table-hover'>";
            xhtml += "<tr data-k='" + obj['k'] + "'>";
            xhtml += "<td>" + obj['k'] + "</td>";
            xhtml += "<td>" + this.typeList + '&nbsp;&nbsp;&nbsp;' + obj['name'] + "</td>";
            xhtml += "<td class='btns_row'></td>";
            xhtml += "</tr>";
            xhtml += "<tr data-k1='" + obj['k'] + "'><td colspan=3 class='params'></td></tr>";
            xhtml += "</table>";
            return xhtml;
        },
        init: function (posts, target) {

            if (!target)
                return;

            this.target = $(target);

            this.append(posts);

            var that = this;
            $(window).scroll(function () {
                if ($(document).height() - $(window).height() <= $(window).scrollTop() + 50) {
                    that.scrollPosition = $(window).scrollTop();
                    that.get();
                }
            });
        },
        append: function (posts) {
            posts = (posts instanceof Array) ? posts : [];
            this.posts = this.posts.concat(posts);

            for (var i = 0, len = posts.length; i < len; i++) {
                var post = posts[i];
                this.target.append(this.render(post));
                var typeSelect = $('[data-k="' + post.k + '"] [name="goodType"]');
                typeSelect.val(post.goodType);
                getParamsForm(typeSelect);
                typeSelect.change(function () {
                    getParamsForm($(this));
                });
                insertButtons(post.k);
            }

            if (this.scrollPosition !== undefined && this.scrollPosition !== null) {
                $(window).scrollTop(this.scrollPosition);
            }
        },
        get: function () {

            if (!this.target || this.busy)
                return;

            if (this.posts && this.posts.length) {
                var lastId = this.posts[this.posts.length - 1].k;
            } else {
                var lastId = 0;
            }


            this.setBusy(true);

            var that = this;

            $.getJSON('/<?= $this->context->id ?>/get-items', {count: this.count, last: lastId},
            function (data) {
                var posts = [];
                for (var i in data) {
                    posts.push(data[i]);
                }
                if (posts.length > 0) {
                    that.append(posts);
                }
                that.setBusy(false);
            }
            );
        },
        showLoading: function (bState) {
            var loading = $('#loading');

            if (bState) {
                $(this.target).append(loading);
                loading.show('slow');
            } else {
                $('#loading').hide();
            }
        },
        setBusy: function (bState) {
            this.showLoading(this.busy = bState);
        }
    };

    engine.init(null, $("#rows"));
    engine.get();

<?php
$js = ob_get_contents();
ob_end_clean();
?>
</script>
<?php
$this->registerJs($js);
?>
