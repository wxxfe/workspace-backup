# -*- coding: utf-8 -*-
#@author
#     __  ____             __  __      _ 
#    /  |/  (_)___  ____ _/ / / /_  __(_)
#   / /|_/ / / __ \/ __ `/ /_/ / / / / / 
#  / /  / / / / / / /_/ / __  / /_/ / /  
# /_/  /_/_/_/ /_/\__, /_/ /_/\__,_/_/   
#                /____/                  
#
# 2016-05-24

import MySQLdb
import time
from datetime import datetime
from datetime import timedelta
from config import MysqlConfig

class SportsData(object):

    def __init__(self):
        self.conn_mysql()

    def conn_mysql(self):

        self.mysql_conn = MySQLdb.connect(
	    host = MysqlConfig.HOST,
	    user = MysqlConfig.USER,
            passwd = MysqlConfig.PASSWORD,
	    db = MysqlConfig.DB,
            port = MysqlConfig.PORT, 
	    charset = 'utf8'
        )
	self.cursor = self.mysql_conn.cursor(cursorclass=MySQLdb.cursors.DictCursor)

    #convert datetime type
    def to_timestamp(self,dt):
        if not dt:
            return dt
        ts = time.mktime(dt.timetuple())
        return int(ts)

    #获取赛事文字新闻
    def getEventNewsOfText(self):
        
        sql = 'SELECT * FROM event_news WHERE type="literal"'
	query = self.cursor.execute(sql)
	textNews = self.cursor.fetchall()
        tn = []

        for news in textNews:
            news['id'] = str(news['id'])
            news['publish_tm'] = self.to_timestamp(news['publish_tm'])
            news['created_at'] = self.to_timestamp(news['created_at'])
            news['updated_at'] = self.to_timestamp(news['updated_at'])
            tn.append(news)

        return tn

    #获取赛事视频新闻
    def getEventNewsOfVideo(self):
        
        sql = 'SELECT * FROM event_news WHERE type="video"'
	query = self.cursor.execute(sql)
	videoNews = self.cursor.fetchall()
        vn = []
        for news in videoNews:
            news['id'] = str(news['id'])
            news['publish_tm'] = self.to_timestamp(news['publish_tm'])
            news['created_at'] = self.to_timestamp(news['created_at'])
            news['updated_at'] = self.to_timestamp(news['updated_at'])
            vn.append(news)

        return vn

    #获取文字新闻
    def getMatchNewsOfText(self):
        
        sql = 'SELECT * FROM match_news WHERE type="literal"'
	query = self.cursor.execute(sql)
	textNews = self.cursor.fetchall()
        tn = []
        for news in textNews:
            news['id'] = str(news['id'])
            news['publish_tm'] = self.to_timestamp(news['publish_tm'])
            news['created_at'] = self.to_timestamp(news['created_at'])
            news['updated_at'] = self.to_timestamp(news['updated_at'])
            tn.append(news)

        return tn

    #获取视频新闻
    def getMatchNewsOfVideo(self):
        
        sql = 'SELECT * FROM match_news WHERE type="video"'
	query = self.cursor.execute(sql)
	videoNews = self.cursor.fetchall()
        vn = []
        for news in videoNews:
            news['id'] = str(news['id'])
            news['publish_tm'] = self.to_timestamp(news['publish_tm'])
            news['created_at'] = self.to_timestamp(news['created_at'])
            news['updated_at'] = self.to_timestamp(news['updated_at'])
            vn.append(news)

        return vn

    #获取比赛录像
    def getMatchVideoOfReplay(self):
        
        sql = 'SELECT * FROM match_video WHERE type="replay"'
	query = self.cursor.execute(sql)
	replayVideo = self.cursor.fetchall()
        rv = []
        for video in replayVideo:
            video['id'] = str(video['id'])
            video['publish_tm'] = self.to_timestamp(video['publish_tm'])
            video['created_at'] = self.to_timestamp(video['created_at'])
            video['updated_at'] = self.to_timestamp(video['updated_at'])
            rv.append(video)

        return rv

    #获取比赛集锦
    def getMatchVideoOfHighlight(self):
        
        sql = 'SELECT * FROM match_video WHERE type="highlight"'
	query = self.cursor.execute(sql)
	highlightVideo = self.cursor.fetchall()
        hv = []
        for video in highlightVideo:
            video['id'] = str(video['id'])
            video['publish_tm'] = self.to_timestamp(video['publish_tm'])
            video['created_at'] = self.to_timestamp(video['created_at'])
            video['updated_at'] = self.to_timestamp(video['updated_at'])
            hv.append(video)

        return hv

    #获取所有比赛
    def getMatch(self):
	
	sql = 'SELECT `match`.*, `t1`.`name` as `team1_name`, `t1`.`badge` as `t1_logo`, `t2`.`name` as `team2_name`, `t2`.`badge` as `t2_logo` FROM `match` JOIN `team` as `t1` ON `match`.`team1_id` = `t1`.`id` JOIN `team` as `t2` ON `match`.`team2_id` = `t2`.`id`'
	query = self.cursor.execute(sql)
        matchs = self.cursor.fetchall()
        m = []
        for match in matchs:
            match['id'] = str(match['id'])
            match['start_tm'] = self.to_timestamp(match['start_tm'])
            match['finish_tm'] = self.to_timestamp(match['finish_tm'])
            match['created_at'] = self.to_timestamp(match['created_at'])
            match['updated_at'] = self.to_timestamp(match['updated_at'])
            m.append(match)

        return m

    #获取所有比赛直播
    def getMatchLive(self):

        sql = 'SELECT `match_live`.*, `a`.`badge` as `team1_badge`, `b`.`badge` as `team2_badge`, `a`.`name` as `team1_name`, `b`.`name` as `team2_name` FROM `match_live` JOIN `match` ON `match_live`.`match_id` = `match`.`id` JOIN `team` as `a` ON `match`.`team1_id` = `a`.`id` JOIN `team` as `b` ON `match`.`team2_id` = `b`.`id`'
	query = self.cursor.execute(sql)
        lives = self.cursor.fetchall()
        l = []
        for live in lives:
            live['id'] = str(live['id'])
            live['created_at'] = self.to_timestamp(live['created_at'])
            live['updated_at'] = self.to_timestamp(live['updated_at'])
            l.append(live)

        return l

    #获取所有球员及所属球队
    def getPlayers(self):

        sql = 'SELECT player.*,team.name as team_name,team.badge as team_logo FROM team_player JOIN player ON team_player.player_id=player.id JOIN team ON team_player.team_id=team.id'
	query = self.cursor.execute(sql)
        players = self.cursor.fetchall()
        p = []
        for player in players:
            player['id'] = str(player['id'])
            player['created_at'] = self.to_timestamp(player['created_at'])
            player['updated_at'] = self.to_timestamp(player['updated_at'])
            p.append(player)

        return p

    #获取所有球队
    def getTeams(self):

        sql = 'SELECT team.*,event.name as event_name FROM event_team JOIN team ON event_team.team_id=team.id JOIN event ON event_team.event_id=event.id'
	query = self.cursor.execute(sql)
        teams = self.cursor.fetchall()
        t = []
        for team in teams:
            team['id'] = str(team['id'])
            team['created_at'] = self.to_timestamp(team['created_at'])
            team['updated_at'] = self.to_timestamp(team['updated_at'])
            t.append(team)

        return t

