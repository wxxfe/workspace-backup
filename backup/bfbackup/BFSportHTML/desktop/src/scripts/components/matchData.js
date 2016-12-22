/**
 * @des: 获取赛程表数据
 * @author: minghui
 * @example: 
 */
require.config({
  baseUrl: 'src/scripts'
});
define(['components/localStorage'],function(localStorage) {

    return {

        matchWrap : $('.matchs'),

        slide : null,

        activeRound : 0,

        statusText : ['未开始','直播中','已结束','延期'],
        
        //缓存过期时间(单位：秒)
        expires : 0,

        showIndex: null,

        today : '',

        init : function(slider,expires,index){
            this.slide = slider;
            this.expires = expires || 0;
            this.showIndex = index || null;
            this.today = $('.all').html();

            $('.events-tab a').on('click',this,this.changeHandle);
        },

        changeHandle : function(event){

            var _self = event.data;
            var eventId = $(this).data('eid');
            var name = $(this).text();
            if(eventId){
                $('.all').html('<span class="event-name">'+ name +'</span>');
            }else{
                $('.all').html(_self.today);
            }
            $('.events-tab a').removeClass('active');
            $(this).addClass('active');
            _self.getMatchData(eventId);

        },

        getCache : function(eventId){

            var data = localStorage.get('match_' + (eventId ? eventId : 'all'));
            return data ? (new Function('return ' + data))() : false;
            
        },

        setCache : function(eventId,data){
            var now = new Date();
            var exp = (new Date(now.getTime() + this.expires * 1000)).getTime();
            var d = data;
            d['exp'] = exp;

            var dr = window.JSON ? JSON.stringify(d) : this.stringify(d);

            localStorage.set('match_' + (eventId ? eventId : 'all'),dr);

        },

        removeCache : function(key){
            localStorage.remove(key);
        },

        /**
         * JSON to String
         */
        stringify : function stringify(obj) {         
            if ("JSON" in window) {
                return JSON.stringify(obj);
            }

            var t = typeof (obj);
            if (t != "object" || obj === null) {
                // simple data type
                if (t == "string") obj = '"' + obj + '"';

                return String(obj);
            } else {
                // recurse array or object
                var n, v, json = [], arr = (obj && obj.constructor == Array);

                for (n in obj) {
                    v = obj[n];
                    t = typeof(v);
                    if (obj.hasOwnProperty(n)) {
                        if (t == "string") {
                            v = '"' + v + '"';
                        } else if (t == "object" && v !== null){
                            v = jQuery.stringify(v);
                        }

                        json.push((arr ? "" : '"' + n + '":') + String(v));
                    }
                }

                return (arr ? "[" : "{") + String(json) + (arr ? "]" : "}");
            }
        },

        getMatchData : function(eventId){

            var _self = this;
            var eid = eventId ? eventId : '';

            var matchs = this.getCache(eid);
            if(!matchs.exp){
                this.removeCache('match_' + (eventId ? eventId : 'all'));
                matchs = false;
            }
            
            if(!matchs || new Date().getTime() > matchs.exp){
                $.get('http://sports.baofeng.com/api/getTopMatchlive/' + eid,function(data){
                    var _matchs = typeof data === 'string' ? (new Function('return ' + data))() : data;
                    _self.setCache(eid,_matchs);
                    if(!eid){
                        _self.showAll(_matchs);
                    }else{
                        _self.showEvent(_matchs);
                    }
                });
            }else{
                var matchsJSON = matchs;
                if(!eid){
                    this.showAll(matchsJSON);
                }else{
                    this.showEvent(matchsJSON);
                }
            }

        },

        getSortDate : function(dateStr){
            var d = new Date(dateStr);
            var m = d.getMonth() + 1;
            var dd = d.getDate();
            if(m < 10) m = '0' + m;
            return m + '-' + dd;
        },

        showEvent : function(matchs){
            var m = matchs.result.data;
            this.activeRound = matchs.result.round;
            var roundHtml = '';
            for(round in m){
                roundHtml += '<li id="r'+ round +'">';
                for(var i=0; i<m[round].length; i++){
                    var match = m[round][i];
                    var _status = match.status;
                    var isLiving = _status == 1 ? 'class="living" ' : '';
                    var r = _status == 0 ? ' style="color:#333;"' : '';
                    roundHtml += '<a '+ isLiving +'href="http://sports.baofeng.com/match/detail/'+ match.id +'.html" target="_blank">';
                    roundHtml += '  <p class="info"><strong'+ r +'>'+ (_status == 0 ? this.getSortDate(match.start_tm) : this.statusText[_status]) +'</strong>'+ match.brief +'</p>';
                    roundHtml += '  <p class="team"><strong>'+ (_status == 0 ? '-' : match.team1_score) +'</strong>'+ match.team1_name +'</p>';
                    roundHtml += '  <p class="team"><strong>'+ (_status == 0 ? '-' : match.team2_score) +'</strong>'+ match.team2_name +'</p>';
                    if(_status == 1){
                    roundHtml += '  <span class="living-layer"><i></i>直播中</span>';
                    }
                    roundHtml += '</a>';
                }
                roundHtml += '</li>';
            }

            this.matchWrap.html(roundHtml);

            this.slide.initData();
            var index = this.getIndex(this.activeRound);
            this.slide.go(this.showIndex || index);
            
        },

        showAll : function(matchs){
            var m = matchs.result.data;
            this.activeRound = matchs.result.round;

            var matchHtml = '';

            for(var i=0; i<m.length; i++){
                var match = m[i];
                var _status = match.status;
                var isLiving = _status == 1 ? 'class="living" ' : '';
                var r = _status == 0 ? ' style="color:#333;"' : '';
                matchHtml += '<li>';
                matchHtml += '<a '+ isLiving +'href="http://sports.baofeng.com/match/detail/'+ match.id +'.html" target="_blank">';
                matchHtml += '  <p class="info"><strong'+ r +'>'+ (_status == 0 ? this.getSortDate(match.start_tm) : this.statusText[_status]) +'</strong>'+ match.brief +'</p>';
                matchHtml += '  <p class="team"><strong>'+ (_status == 0 ? '-' : match.team1_score) +'</strong>'+ match.team1_name +'</p>';
                matchHtml += '  <p class="team"><strong>'+ (_status == 0 ? '-' : match.team2_score) +'</strong>'+ match.team2_name +'</p>';
                if(_status == 1){
                matchHtml += '  <span class="living-layer"><i></i>直播中</span>';
                }
                matchHtml += '</a>';
                if(i%7 == 0){
                    matchHtml += '</li>';
                }
                if(m.length < 7){
                    matchHtml += '</li>';
                }
            }

            this.matchWrap.html(matchHtml);

            this.slide.initData();
        },

        getIndex : function(round){
            var rounds = $('li',this.matchWrap);
            return rounds.index($('#r' + round));
        }

    };

});
