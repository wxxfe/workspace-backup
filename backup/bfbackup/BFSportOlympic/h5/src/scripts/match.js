require.config({
    baseUrl: 'src/scripts'
});
require([], function () {
    
    var tabMenu = $('.date-tab a');
    var table = $('.matchs');
    var mtable = $('.match-table table');

    var menus = tabMenu.length;
    tabMenu.parents('ul.date-tab').width(tabMenu.width() * menus);

    tabMenu.addSwipeEvents().bind('tap',function(evt,touch){
        var day = $(this).data('dd');
        var matchs = $('#m-' + day);
        tabMenu.removeClass();
        $(this).addClass('active');
        if(matchs.length > 0){
            table.hide();
            matchs.show();
        }else{
            $.get('http://2016.sports.baofeng.com/api/getSchedule/' + day,function(d){
                var data = typeof d === 'string' ? (new Function('return ' + d))() : d;
                var matchs = data.result.data;
                if(data.status == 1){
                    addNewsFragment(d,matchs);
                }
            });
        }
    });

    function addNewsFragment(day,data){
        table.hide();
        var matchs = data;
        var html = '<tbody class="matchs" id="m-'+ day +'">';
        for(var i=0; i<matchs.length; i++){
            html += '<tr>';
            html += '   <td>'+ matchs[i].nice_date +'</td>';
            html += '   <td>'+ matchs[i].large_project +'</td>';
            html += '   <td>'+ matchs[i].small_project +'</td>';
            html += '</tr>';
        }
        html += '</tbody>';
        mtable.append(html);
    }

});
