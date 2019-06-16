$(function () {
    $('#discuss-box').keydown(function (event) {
        if(event.keyCode == 13){//按了回车键

            var text = $(this).val();
            var url = "http://119.28.137.51:8811/?s=index/chart/index";
            var data = {'content':text, 'game_id':1};

            $.post(url,data, function () {
                //todo
                $(this).val("");
            },'json')

        }
    });
});