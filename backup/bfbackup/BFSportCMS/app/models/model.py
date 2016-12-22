# -*- coding: utf-8 -*-

from sqlalchemy.dialects.mysql import TINYINT, SMALLINT
from sqlalchemy.sql import text
from sqlalchemy import Index

from app.extensions import db
from app.utils.text import to_timestamp
from app.utils.text import add_image_domain


event_enums = (
    "football",
    "basketball",
    "misc")


class EventTypeStatus:

    NOTCUP = '0'
    CUP = '1'


class Event(db.Model):
    __tablename__ = 'event'

    id = db.Column(db.Integer, primary_key=True)
    name = db.Column(db.String(20), nullable=False)
    alias = db.Column(db.String(32), nullable=False, server_default='')
    type = db.Column(
        db.Enum(*event_enums), nullable=False, server_default="misc")
    brief = db.Column(db.String(200), nullable=False, server_default='')
    iscup = db.Column(
        TINYINT, nullable=False, server_default=text(EventTypeStatus.NOTCUP))

    event_news = db.relationship('EventNews', backref='event', lazy='dynamic')
    match = db.relationship('Match', backref='event', lazy='dynamic')
    teams = db.relationship(
        'Team', secondary='event_team', back_populates="events")

    activities = db.relationship('Activity', backref='event', lazy='dynamic')
    subpages = db.relationship('SubPage', backref='event', lazy='dynamic')

    created_at = db.Column(
        db.TIMESTAMP,
        server_default=db.func.current_timestamp(),
        nullable=False)
    updated_at = db.Column(
        db.TIMESTAMP,
        server_default=text(
            'CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP'),
        nullable=False)

    def to_dict(self):
            return dict(
                id=self.id,
                name=self.name,
                type=self.type,
                iscup=self.iscup,
                brief=self.brief
            )


class ActivityStatus:

    HIDDEN = '0'
    SHOWN = '1'


class Activity(db.Model):
    __tablename__ = 'activity'

    id = db.Column(db.Integer, primary_key=True)
    title = db.Column(db.String(256), nullable=False)
    event_id = db.Column(
        db.Integer,
        db.ForeignKey('event.id', ondelete="CASCADE", onupdate="CASCADE"))
    image = db.Column(db.String(256), nullable=False, server_default='')
    url = db.Column(db.String(512), nullable=False, server_default='')
    brief = db.Column(db.String(128), nullable=False, server_default='')

    visible = db.Column(
        TINYINT, nullable=False, server_default=text(ActivityStatus.HIDDEN))

    created_at = db.Column(
        db.TIMESTAMP,
        server_default=db.func.current_timestamp(),
        nullable=False)

    updated_at = db.Column(
        db.TIMESTAMP,
        server_default=text(
            'CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP'),
        nullable=False)

    def to_dict(self):
        return {
            'id': self.id,
            'title': self.title,
            'event_id': self.event_id,
            'brief': self.brief,
            'visible': self.visible,
            'url': self.url,
            'created_at': self.created_at,
            'image': add_image_domain(self.image),
        }


class EventNewsStatus:

    HIDDEN = '0'
    SHOWN = '1'


class EventNews(db.Model):
    __tablename__ = 'event_news'

    id = db.Column(db.Integer, primary_key=True)
    event_id = db.Column(
        db.Integer,
        db.ForeignKey('event.id', ondelete="CASCADE", onupdate="CASCADE"))
    title = db.Column(db.String(100), nullable=False)
    title_prior = db.Column(db.String(100), nullable=False, server_default='')
    type = db.Column(db.String(20), nullable=False)
    visible = db.Column(
        TINYINT, nullable=False, server_default=text(EventNewsStatus.SHOWN))
    subtitle = db.Column(db.String(200), nullable=False, server_default='')
    site = db.Column(db.String(20), nullable=False, server_default='')
    args = db.Column(db.String(256), nullable=False, server_default='')
    origin = db.Column(db.String(20), nullable=False, server_default='')
    content = db.Column(db.Text)
    image = db.Column(db.String(256), nullable=False, server_default='')
    large_image = db.Column(db.String(256), nullable=False, server_default='')
    play_url = db.Column(db.String(1024), nullable=False, server_default='')
    play_html = db.Column(db.String(8192), nullable=False, server_default='')
    play_code = db.Column(db.String(1024), nullable=False, server_default='')
    toph = db.Column(TINYINT, nullable=False, server_default=text('0'))
    tope = db.Column(TINYINT, nullable=False, server_default=text('0'))
    pin = db.Column(TINYINT, nullable=False, server_default=text('0'))
    isvr = db.Column(TINYINT, nullable=False, server_default=text('0'))
    source_url = db.Column(
        db.String(200), nullable=False, server_default='', index=True)

    publish_tm = db.Column(
        db.TIMESTAMP,
        server_default=db.func.current_timestamp(),
        nullable=False,
        index=True)
    created_at = db.Column(
        db.TIMESTAMP,
        server_default=db.func.current_timestamp(),
        nullable=False)
    updated_at = db.Column(
        db.TIMESTAMP,
        server_default=text(
            'CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP'),
        nullable=False)

    __table_args__ = (Index('ix_event_news_title_and_site', "site", "title"), )

    def to_dict(self):
            return dict(
                id=self.id,
                event_id=self.event_id,
                title=self.title,
                type=self.type,
                visible=self.visible,
                subtitle=self.subtitle,
                site=self.site,
                origin=self.origin,
                toph=self.toph,
                tope=self.tope,
                image=add_image_domain(self.image),
                imageCode=self.image,
                play_url=self.play_url,
                play_code=self.play_code,
                play_html=self.play_html,
                content=self.content,
                isvr=self.isvr,
                publish_tm=to_timestamp(self.publish_tm)
            )


class MatchStatus:

    NOTSTARTED = '0'
    ONGOING = '1'
    FINISHED = '2'


class MatchVisibleStatus:

    HIDDEN = '0'
    SHOWN = '1'


class Match(db.Model):
    __tablename__ = 'match'

    id = db.Column(db.Integer, primary_key=True)
    event_id = db.Column(
        db.Integer,
        db.ForeignKey('event.id', ondelete="CASCADE", onupdate="CASCADE"))

    brief = db.Column(db.String(200), nullable=False, server_default='')
    forecast = db.Column(db.String(200), nullable=False, server_default='')
    round = db.Column(db.Integer, nullable=False, server_default=text('0'))
    visible = db.Column(
        TINYINT, nullable=False, server_default=text(MatchVisibleStatus.SHOWN))
    status = db.Column(
        TINYINT, nullable=False, server_default=text(MatchStatus.NOTSTARTED))
    start_tm = db.Column(
        db.TIMESTAMP, nullable=False, server_default=text('0'), index=True)
    finish_tm = db.Column(
        db.TIMESTAMP, nullable=False, server_default=text('0'))
    viewers = db.Column(db.Integer, nullable=False, server_default=text('0'))
    team1_id = db.Column(db.Integer, nullable=False)
    team1_score = db.Column(
        db.Integer, nullable=False, server_default=text('0'))
    team1_likes = db.Column(
        db.Integer, nullable=False, server_default=text('0'))
    team2_id = db.Column(db.Integer, nullable=False)
    team2_score = db.Column(
        db.Integer, nullable=False, server_default=text('0'))
    team2_likes = db.Column(
        db.Integer, nullable=False, server_default=text('0'))

    match_news = db.relationship('MatchNews', backref='match', lazy='dynamic')
    match_video = db.relationship(
        'MatchVideo',
        backref='match',
        lazy='dynamic')
    match_live = db.relationship('MatchLive', backref='match', lazy='dynamic')

    created_at = db.Column(
        db.TIMESTAMP,
        server_default=db.func.current_timestamp(),
        nullable=False)
    updated_at = db.Column(
        db.TIMESTAMP,
        server_default=text(
            'CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP'),
        nullable=False)

    def to_dict(self):
            return dict(
                id=self.id,
                event_id=self.event_id,
                brief=self.brief,
                forecast=self.forecast,
                round=self.round,
                visible=self.visible,
                status=self.status,
                start_tm=to_timestamp(self.start_tm),
                finish_tm=to_timestamp(self.finish_tm),
                viewers=self.viewers,
                team1_id=self.team1_id,
                team1_score=self.team1_score,
                team1_likes=self.team1_likes,
                team2_id=self.team2_id,
                team2_score=self.team2_score,
                team2_likes=self.team2_likes
            )


class MatchNewsStatus:

    HIDDEN = '0'
    SHOWN = '1'


class MatchNews(db.Model):
    __tablename__ = 'match_news'

    id = db.Column(db.Integer, primary_key=True)
    match_id = db.Column(
        db.Integer,
        db.ForeignKey('match.id', ondelete="CASCADE", onupdate="CASCADE"))
    title = db.Column(db.String(100), nullable=False)
    title_prior = db.Column(db.String(100), nullable=False, server_default='')
    type = db.Column(db.String(20), nullable=False)
    args = db.Column(db.String(256), nullable=False, server_default='')
    visible = db.Column(
        TINYINT, nullable=False, server_default=text(MatchNewsStatus.SHOWN))
    subtitle = db.Column(db.String(200), nullable=False, server_default='')
    site = db.Column(db.String(20), nullable=False, server_default='')
    origin = db.Column(db.String(20), nullable=False, server_default='')
    content = db.Column(db.Text)
    image = db.Column(db.String(256), nullable=False, server_default='')
    large_image = db.Column(db.String(256), nullable=False, server_default='')
    play_url = db.Column(db.String(1024), nullable=False, server_default='')
    play_html = db.Column(db.String(8192), nullable=False, server_default='')
    play_code = db.Column(db.String(1024), nullable=False, server_default='')
    top = db.Column(TINYINT, nullable=False, server_default=text('0'))
    pin = db.Column(TINYINT, nullable=False, server_default=text('0'))
    isvr = db.Column(TINYINT, nullable=False, server_default=text('0'))
    source_url = db.Column(
        db.String(200), nullable=False, server_default='', index=True)

    publish_tm = db.Column(
        db.TIMESTAMP,
        server_default=db.func.current_timestamp(),
        nullable=False,
        index=True)
    created_at = db.Column(
        db.TIMESTAMP,
        server_default=db.func.current_timestamp(),
        nullable=False)
    updated_at = db.Column(
        db.TIMESTAMP,
        server_default=text(
            'CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP'),
        nullable=False)

    __table_args__ = (Index('ix_match_news_title_and_site', "site", "title"), )

    def to_dict(self):
            return dict(
                id=self.id,
                match_id=self.match_id,
                title=self.title,
                type=self.type,
                visible=self.visible,
                subtitle=self.subtitle,
                site=self.site,
                origin=self.origin,
                content=self.content,
                image=add_image_domain(self.image),
                imageCode=self.image,
                play_url=self.play_url,
                play_code=self.play_code,
                play_html=self.play_html,
                isvr=self.isvr,
                publish_tm=to_timestamp(self.publish_tm)
            )


class MatchVideoStatus:

    HIDDEN = '0'
    SHOWN = '1'


class MatchVideo(db.Model):
    __tablename__ = 'match_video'

    id = db.Column(db.Integer, primary_key=True)
    match_id = db.Column(
        db.Integer,
        db.ForeignKey('match.id', ondelete="CASCADE", onupdate="CASCADE"))
    title = db.Column(db.String(100), nullable=False)
    title_prior = db.Column(db.String(100), nullable=False, server_default='')
    type = db.Column(db.String(20), nullable=False)
    visible = db.Column(
        TINYINT, nullable=False, server_default=text(MatchVideoStatus.SHOWN))
    display_order = db.Column(
        db.Integer, nullable=False, server_default=text('0'))
    site = db.Column(db.String(20), nullable=False, server_default='')
    args = db.Column(db.String(256), nullable=False, server_default='')
    duration = db.Column(db.Integer, nullable=False, server_default=text('0'))
    image = db.Column(db.String(256), nullable=False, server_default='')
    large_image = db.Column(db.String(256), nullable=False, server_default='')
    play_url = db.Column(db.String(1024), nullable=False, server_default='')
    play_html = db.Column(db.String(8192), nullable=False, server_default='')
    play_code = db.Column(db.String(1024), nullable=False, server_default='')
    top = db.Column(TINYINT, nullable=False, server_default=text('0'))
    pin = db.Column(TINYINT, nullable=False, server_default=text('0'))
    isvr = db.Column(TINYINT, nullable=False, server_default=text('0'))

    publish_tm = db.Column(
        db.TIMESTAMP,
        server_default=db.func.current_timestamp(),
        nullable=False,
        index=True)
    created_at = db.Column(
        db.TIMESTAMP,
        server_default=db.func.current_timestamp(),
        nullable=False)
    updated_at = db.Column(
        db.TIMESTAMP,
        server_default=text(
            'CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP'),
        nullable=False)

    __table_args__ = (
        Index('ix_match_video_title_and_site', "site", "title"), )

    def to_dict(self):
            return dict(
                id=self.id,
                title=self.title,
                site=self.site,
                display_order=self.display_order,
                image=add_image_domain(self.image),
                imageCode=self.image,
                play_url=self.play_url,
                play_html=self.play_html,
                play_code=self.play_code,
                isvr=self.isvr,
                publish_tm=to_timestamp(self.publish_tm)
            )


class MatchLive(db.Model):
    __tablename__ = 'match_live'

    id = db.Column(db.Integer, primary_key=True)
    match_id = db.Column(
        db.Integer,
        db.ForeignKey('match.id', ondelete="CASCADE", onupdate="CASCADE"))
    site = db.Column(db.String(20), nullable=False, server_default='')
    play_url = db.Column(db.String(1024), nullable=False, server_default='')
    play_code = db.Column(db.String(1024), nullable=False, server_default='')
    play_html = db.Column(db.String(8192), nullable=False, server_default='')
    feed_code = db.Column(db.String(1024), nullable=False, server_default='')
    isvr = db.Column(TINYINT, nullable=False, server_default=text('0'))

    stream_time = db.Column(
        db.TIMESTAMP,
        server_default=db.func.current_timestamp(),
        nullable=False)

    created_at = db.Column(
        db.TIMESTAMP,
        server_default=db.func.current_timestamp(),
        nullable=False)
    updated_at = db.Column(
        db.TIMESTAMP,
        server_default=text(
            'CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP'),
        nullable=False)

    def to_dict(self):
            return dict(
                id=self.id,
                site=self.site,
                play_code=self.play_code,
                play_html=self.play_html,
                feed_code=self.feed_code,
                stream_time=self.stream_time,
                isvr=self.isvr,
                play_url=self.play_url
            )


class SelectedMatch(db.Model):
    __tablename__ = 'selected_match'

    id = db.Column(db.Integer, primary_key=True)
    event_id = db.Column(db.Integer, server_default=text('0'))
    match_id = db.Column(db.Integer, server_default=text('0'))
    type = db.Column(db.String(20), nullable=False)

    created_at = db.Column(
        db.TIMESTAMP,
        server_default=db.func.current_timestamp(),
        nullable=False)
    updated_at = db.Column(
        db.TIMESTAMP,
        server_default=text(
            'CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP'),
        nullable=False)

    def to_dict(self):
            return dict(
                id=self.id,
                event_id=self.event_id,
                match_id=self.match_id,
                type=self.type,
                created_at=to_timestamp(self.created_at),
                updated_at=to_timestamp(self.updated_at)
            )


class Team(db.Model):
    __tablename__ = 'team'

    id = db.Column(db.Integer, primary_key=True)
    name = db.Column(db.String(20), nullable=False, unique=True)
    english_name = db.Column(db.String(64), server_default='')
    badge = db.Column(db.String(256), server_default='')
    head_coach = db.Column(db.String(64), server_default='')
    home_court = db.Column(db.String(64), server_default='')
    website = db.Column(db.String(64), server_default='')

    events = db.relationship(
        'Event', secondary='event_team', back_populates="teams")
    players = db.relationship(
        'Player', secondary='team_player', back_populates="teams")

    created_at = db.Column(
        db.TIMESTAMP,
        server_default=db.func.current_timestamp(),
        nullable=False)
    updated_at = db.Column(
        db.TIMESTAMP,
        server_default=text(
            'CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP'),
        nullable=False)

    def to_dict(self):
            return dict(
                id=self.id,
                name=self.name,
                english_name=self.english_name,
                head_coach=self.head_coach,
                home_court=self.home_court,
                website=self.website,
                badge=add_image_domain(self.badge),
                badgeCode=self.badge
            )


class Player(db.Model):
    __tablename__ = 'player'

    id = db.Column(db.Integer, primary_key=True)
    name = db.Column(db.String(64), nullable=False)
    english_name = db.Column(db.String(64), nullable=False, server_default='')
    photo = db.Column(db.String(200), nullable=False, server_default='')
    height = db.Column(db.Integer, nullable=False, server_default=text('0'))
    weight = db.Column(db.Integer, nullable=False, server_default=text('0'))
    birthday = db.Column(db.String(64), nullable=False, server_default='')
    nationality = db.Column(db.String(64), nullable=False, server_default='')

    teams = db.relationship(
        'Team', secondary='team_player', back_populates="players")

    created_at = db.Column(
        db.TIMESTAMP,
        server_default=db.func.current_timestamp(),
        nullable=False)
    updated_at = db.Column(
        db.TIMESTAMP,
        server_default=text(
            'CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP'),
        nullable=False)

    def to_dict(self):
            return dict(
                id=self.id,
                name=self.name,
                english_name=self.english_name,
                height=self.height,
                birthday=self.birthday,
                weight=self.weight,
                nationality=self.nationality,
                photo=add_image_domain(self.photo),
                photoCode=self.photo
            )


class EventTeam(db.Model):
    __tablename__ = 'event_team'

    id = db.Column(db.Integer, primary_key=True)
    event_id = db.Column(
        db.Integer,
        db.ForeignKey('event.id', ondelete="CASCADE", onupdate="CASCADE"))
    team_id = db.Column(
        db.Integer,
        db.ForeignKey('team.id', ondelete="CASCADE", onupdate="CASCADE"))
    ranking = db.Column(db.Integer, nullable=False, server_default=text('0'))
    group = db.Column(db.String(16), nullable=False, server_default='')

    created_at = db.Column(
        db.TIMESTAMP,
        server_default=db.func.current_timestamp(),
        nullable=False)
    updated_at = db.Column(
        db.TIMESTAMP,
        server_default=text(
            'CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP'),
        nullable=False)

    def to_dict(self):
            return dict(
                id=self.id,
                event_id=self.event_id,
                team_id=self.team_id,
                group=self.group
            )


class TeamPlayer(db.Model):
    __tablename__ = 'team_player'

    id = db.Column(db.Integer, primary_key=True)

    team_id = db.Column(
        db.Integer,
        db.ForeignKey('team.id', ondelete="CASCADE", onupdate="CASCADE"))
    player_id = db.Column(
        db.Integer,
        db.ForeignKey('player.id', ondelete="CASCADE", onupdate="CASCADE"))
    position = db.Column(db.String(32), nullable=False, server_default='')
    number = db.Column(SMALLINT, nullable=False, server_default=text('0'))

    created_at = db.Column(
        db.TIMESTAMP,
        server_default=db.func.current_timestamp(),
        nullable=False)
    updated_at = db.Column(
        db.TIMESTAMP,
        server_default=text(
            'CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP'),
        nullable=False)

    def to_dict(self):
            return dict(
                id=self.id,
                player_id=self.player_id,
                team_id=self.team_id
            )


class Scoreboard(db.Model):
    __tablename__ = 'scoreboard'

    id = db.Column(db.Integer, primary_key=True)
    event_id = db.Column(db.Integer, nullable=False, server_default=text('0'))
    team_id = db.Column(db.Integer, nullable=False, server_default=text('0'))
    wins = db.Column(db.Integer, nullable=False, server_default=text('0'))
    draws = db.Column(db.Integer, nullable=False, server_default=text('0'))
    loses = db.Column(db.Integer, nullable=False, server_default=text('0'))
    goals = db.Column(db.Integer, nullable=False, server_default=text('0'))
    goals_conceded = db.Column(
        db.Integer, nullable=False, server_default=text('0'))
    goals_differential = db.Column(
        db.Integer, nullable=False, server_default=text('0'))
    points = db.Column(db.Integer, nullable=False, server_default=text('0'))

    created_at = db.Column(
        db.TIMESTAMP,
        server_default=db.func.current_timestamp(),
        nullable=False)
    updated_at = db.Column(
        db.TIMESTAMP,
        server_default=text(
            'CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP'),
        nullable=False)

    def to_dict(self):
            return dict(
                id=self.id,
                event_id=self.event_id,
                team_id=self.team_id,
                wins=self.wins,
                draws=self.draws,
                loses=self.loses,
                goals_differential=self.goals_differential,
                points=self.points
            )


class Topscorer(db.Model):
    __tablename__ = 'topscorer'

    id = db.Column(db.Integer, primary_key=True)
    event_id = db.Column(db.Integer, nullable=False, server_default=text('0'))
    player_id = db.Column(db.Integer, nullable=False, server_default=text('0'))
    goals = db.Column(db.Integer, nullable=False, server_default=text('0'))

    created_at = db.Column(
        db.TIMESTAMP,
        server_default=db.func.current_timestamp(),
        nullable=False)
    updated_at = db.Column(
        db.TIMESTAMP,
        server_default=text(
            'CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP'),
        nullable=False)

    def to_dict(self):
            return dict(
                id=self.id,
                event_id=self.event_id,
                player_id=self.player_id,
                goals=self.goals
            )


class Topassist(db.Model):
    __tablename__ = 'topassist'

    id = db.Column(db.Integer, primary_key=True)
    event_id = db.Column(db.Integer, nullable=False, server_default=text('0'))
    player_id = db.Column(db.Integer, nullable=False, server_default=text('0'))
    assists = db.Column(db.Integer, nullable=False, server_default=text('0'))

    created_at = db.Column(
        db.TIMESTAMP,
        server_default=db.func.current_timestamp(),
        nullable=False)
    updated_at = db.Column(
        db.TIMESTAMP,
        server_default=text(
            'CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP'),
        nullable=False)

    def to_dict(self):
            return dict(
                id=self.id,
                event_id=self.event_id,
                player_id=self.player_id,
                assists=self.assists
            )


class Uploading_video(db.Model):
    __tablename__ = 'uploading_video'

    id = db.Column(db.Integer, primary_key=True)
    type = db.Column(db.String(64), nullable=False)
    status = db.Column(db.Integer, nullable=False, server_default=text('0'))
    rel_id = db.Column(db.Integer, nullable=False, server_default=text('0'))
    title = db.Column(db.String(128), nullable=False)
    image = db.Column(db.String(256), nullable=False, server_default='')

    created_at = db.Column(
        db.TIMESTAMP,
        server_default=db.func.current_timestamp(),
        nullable=False)
    updated_at = db.Column(
        db.TIMESTAMP,
        server_default=text(
            'CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP'),
        nullable=False)


subpage_enums = (
    "teams",
    "topassist",
    "topscorer",
    "scoreboard",
    "stories",
    "gallery")


class SubPage(db.Model):
    __tablename__ = 'subpage'

    id = db.Column(db.Integer, primary_key=True)

    event_id = db.Column(
        db.Integer,
        db.ForeignKey('event.id', ondelete="CASCADE", onupdate="CASCADE"))

    display_order = db.Column(
        db.Integer, nullable=False, server_default=text('0'))

    title = db.Column(db.String(16), nullable=False)
    brief = db.Column(db.String(128), nullable=False, server_default='')
    target = db.Column(
        db.Enum(*subpage_enums), nullable=False)

    created_at = db.Column(
        db.TIMESTAMP,
        server_default=db.func.current_timestamp(),
        nullable=False)
    updated_at = db.Column(
        db.TIMESTAMP,
        server_default=text(
            'CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP'),
        nullable=False)

    def to_dict(self):
            return dict(
                id=self.id,
                event_id=self.event_id,
                title=self.title,
                brief=self.brief,
                target=self.target,
                updated_at=self.updated_at,
                display_order=self.display_order
            )


class PendingVideo(db.Model):
    __tablename__ = 'pending_video'

    id = db.Column(db.Integer, primary_key=True)

    title = db.Column(db.String(128), nullable=False)
    data = db.Column(db.String(1024), nullable=False)
    fresh = db.Column(
        TINYINT, nullable=False, server_default=text('1'))

    created_at = db.Column(
        db.TIMESTAMP,
        server_default=db.func.current_timestamp(),
        nullable=False)
    updated_at = db.Column(
        db.TIMESTAMP,
        server_default=text(
            'CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP'),
        nullable=False)

    def to_dict(self):
            return dict(
                id=self.id,
                title=self.title,
                data=self.data,
                fresh=self.fresh,
                created_at=self.created_at
            )

#对外数据-主版-推荐比赛
class ExSelectedMatch(db.Model):
    __tablename__ = 'ex_selected_match'

    id = db.Column(db.Integer, primary_key=True)
    match_id = db.Column(db.Integer, server_default=text('0'))
    display_order = db.Column(
        db.Integer, nullable=False, server_default=text('0'))
    created_at = db.Column(
        db.TIMESTAMP,
        server_default=db.func.current_timestamp(),
        nullable=False)
    updated_at = db.Column(
        db.TIMESTAMP,
        server_default=text(
            'CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP'),
        nullable=False)

    def to_dict(self):
            return dict(
                id=self.id,
                match_id=self.match_id,
                display_order=self.display_order,
                created_at=to_timestamp(self.created_at),
                updated_at=to_timestamp(self.updated_at)
            )

#对外数据-主版-今日推荐模型
class ExHotspot(db.Model):
    __tablename__ = 'ex_hotspot'

    id = db.Column(db.Integer, primary_key=True)
    title = db.Column(db.String(100), nullable=False)
    brief = db.Column(db.String(128), nullable=False,server_default='')
    image = db.Column(db.String(256), nullable=False, server_default='')
    type = db.Column(db.String(20), nullable=False)
    ref_id = db.Column(db.Integer, nullable=False,server_default=text('0'))
    data = db.Column(db.String(1024), nullable=False, server_default='')
    display_order = db.Column(db.Integer, server_default=text('0'))
    created_at = db.Column(db.TIMESTAMP,
        server_default=db.func.current_timestamp(),
        nullable=False)
    updated_at = db.Column(
        db.TIMESTAMP,
        server_default=text(
            'CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP'),
        nullable=False)

    def to_dict(self):
            return dict(
                id=self.id,
                title=self.title,
                brief=self.brief,
                image=add_image_domain(self.image),
                type=self.type,
                ref_id=self.ref_id,
                data=self.data,
                display_order=self.display_order,
                created_at=self.created_at,
                updated_at=self.updated_at
            )
#对外数据-主版-发现
class ExDiscovery(db.Model):
    __tablename__ = 'ex_discovery'

    id = db.Column(db.Integer, primary_key=True)
    title = db.Column(db.String(100), nullable=False, server_default='')
    image = db.Column(db.String(256), nullable=False, server_default='')
    url = db.Column(db.String(256), nullable=False, server_default='')
    created_at = db.Column(db.TIMESTAMP,
        server_default=db.func.current_timestamp(),
        nullable=False)
    updated_at = db.Column(
        db.TIMESTAMP,
        server_default=text(
            'CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP'),
        nullable=False)

    def to_dict(self):
            return dict(
                id=self.id,
                title=self.title,
                image=add_image_domain(self.image),
                url=self.url,
                created_at=self.created_at,
                updated_at=self.updated_at
            )

#对外数据-主版-发现
class ExColumn(db.Model):
    __tablename__ = 'ex_column'

    id = db.Column(db.Integer, primary_key=True)
    title = db.Column(db.String(100), nullable=False, server_default='')
    type = db.Column(db.String(20), nullable=False)
    display_order = db.Column(db.Integer, server_default=text('0'))
    created_at = db.Column(db.TIMESTAMP,
        server_default=db.func.current_timestamp(),
        nullable=False)
    updated_at = db.Column(
        db.TIMESTAMP,
        server_default=text(
            'CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP'),
        nullable=False)

    def to_dict(self):
            return dict(
                id=self.id,
                title=self.title,
                type=self.type,
                display_order=self.display_order,
                created_at=self.created_at,
                updated_at=self.updated_at
            )

class ExColumnPost(db.Model):
    __tablename__ = 'ex_column_post'

    id = db.Column(db.Integer, primary_key=True)
    column_id = db.Column(db.Integer,nullable=False)
    title = db.Column(db.String(100), nullable=False)
    image = db.Column(db.String(256), nullable=False, server_default='')
    brief = db.Column(db.String(128), nullable=False)
    type = db.Column(db.String(20), nullable=False)
    data = db.Column(db.String(1024), nullable=False)
    ref_id = db.Column(db.Integer, server_default=text('0'))
    display_order = db.Column(db.Integer, server_default=text('0'))
    created_at = db.Column(db.TIMESTAMP,
        server_default=db.func.current_timestamp(),
        nullable=False)
    updated_at = db.Column(
        db.TIMESTAMP,
        server_default=text(
            'CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP'),
        nullable=False)

    def to_dict(self):
            return dict(
                id=self.id,
                column_id=self.column_id,
                title=self.title,
                image=add_image_domain(self.image),
                brief=self.brief,
                type=self.type,
                ref_id=self.ref_id,
                data=self.data,
                display_order=self.display_order,
                created_at=self.created_at,
                updated_at=self.updated_at
            )
