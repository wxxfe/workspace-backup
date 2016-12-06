require.config({
    baseUrl: 'src/scss'
});

require([
    'components/libs/fastclick',
    'components/libs/zepto.min',
    'components/abase/util',
    'components/deviceapi/index',
    'components/listitem/index',
    'components/list/index',
    'components/button/index',
    'components/tab/index',
    'components/questionitem/index',
    'components/quiz/index',
    'components/toast/index',
    'components/matchinfo/index',
    'components/channel/index',
    'components/topicitem/index',
    'components/topiclist/index',
    'components/sharepage/index',
    'components/page/index'
], function (FastClick) {
    SharePageFactory();

    FastClick.attach(document.body);
    ChannelShareFactory();

    var quiz = document.querySelector('.quiz'),
        downloadtoast = document.querySelector('.downloadtoast'),
        downloadtoastComp;

    var buttons = document.querySelectorAll('.button');
    for (var i = 0, len = buttons.length; i < len; i++) {
        ButtonFactory({element: buttons[i]})
    }

    var tabs = document.querySelectorAll('.tab');
    for (var i = 0, len = tabs.length; i < len; i++) {
        var ta = tabs[i],
            type = 'default';
        if (ta.classList.contains('slide')) {
            type = 'slide'
        }
        TabFactory({element: tabs[i], type: type})
    }

    var matchinfo = document.querySelector('.match-info');
    matchinfo && MatchInfoFactory({
        element: matchinfo, onApointment: function () {
            downloadtoastComp.show();
        }
    })

    downloadtoast && ( downloadtoastComp = ToastFactory({element: downloadtoast}) )

    quiz && QuizFactory({
        element: quiz, onAnswerSubmit: function () {
            downloadtoastComp.show();
        }
    })

    var list = document.querySelector('.list');
    list && ListFactory({element: list});

    var topiclist = document.querySelector('.topic-list');
    topiclist && TopicListFactory({element: topiclist});

});
