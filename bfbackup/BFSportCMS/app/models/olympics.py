# -*- coding: utf-8 -*-

from app.utils.text import to_timestamp
from sqlalchemy.dialects.mysql import TINYINT
from sqlalchemy.sql import text

from app.extensions import db
from app.utils.text import add_image_domain


class OGSlideStatus:

    SHOWN = '1'
    HIDDEN = '0'


class OGSlide(db.Model):
    __bind_key__ = 'olympics'
    __tablename__ = 'og_slide'

    id = db.Column(db.Integer, primary_key=True)
    title = db.Column(db.String(100), nullable=False)
    display_order = db.Column(db.Integer, server_default=text('0'))
    platform = db.Column(db.String(20), nullable=False)
    type = db.Column(db.String(20), nullable=False)
    visible = db.Column(TINYINT, server_default=text(OGSlideStatus.HIDDEN))
    image = db.Column(db.String(256), nullable=False, server_default='')
    ref_id = db.Column(db.Integer, nullable=False, server_default=text('0'))
    url = db.Column(db.String(512), nullable=False, server_default='')
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
            type=self.type,
            display_order=self.display_order,
            visible=self.visible,
            image=add_image_domain(self.image),
            url=self.url,
            ref_id=self.ref_id,
            created_at=to_timestamp(self.created_at),
            updated_at=to_timestamp(self.updated_at)
        )


class OGNewsVisibleStatus:

    HIDDEN = '0'
    SHOWN = '1'


class OGNews(db.Model):
    __bind_key__ = 'olympics'
    __tablename__ = 'og_news'

    id = db.Column(db.Integer, primary_key=True)
    type = db.Column(db.String(20), nullable=False)
    title = db.Column(db.String(100), nullable=False)
    subtitle = db.Column(db.String(200), nullable=False, server_default='')
    visible = db.Column(
        TINYINT,
        nullable=False,
        server_default=text(OGNewsVisibleStatus.SHOWN))
    top = db.Column(TINYINT, nullable=False, server_default=text('0'))
    site = db.Column(db.String(20), nullable=False, server_default='')
    content = db.Column(db.Text)
    image = db.Column(db.String(256), nullable=False, server_default='')
    click = db.Column(db.Integer, nullable=False, server_default=text('0'))

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
                type=self.type,
                visible=self.visible,
                subtitle=self.subtitle,
                top=self.top,
                site=self.site,
                image=add_image_domain(self.image),
                imageCode=self.image,
                content=self.content,
                created_at=self.created_at
            )


class OGVideoVisibleStatus:

    HIDDEN = '0'
    SHOWN = '1'


class OGVideo(db.Model):
    __bind_key__ = 'olympics'
    __tablename__ = 'og_video'

    id = db.Column(db.Integer, primary_key=True)
    type = db.Column(db.String(20), nullable=False)
    title = db.Column(db.String(100), nullable=False)
    visible = db.Column(
        TINYINT,
        nullable=False,
        server_default=text(OGVideoVisibleStatus.SHOWN))
    top = db.Column(TINYINT, nullable=False, server_default=text('0'))
    isvr = db.Column(TINYINT, nullable=False, server_default=text('0'))
    site = db.Column(db.String(20), nullable=False, server_default='')
    args = db.Column(db.String(256), nullable=False, server_default='')
    duration = db.Column(db.Integer, nullable=False, server_default=text('0'))
    image = db.Column(db.String(256), nullable=False, server_default='')
    play_url = db.Column(db.String(1024), nullable=False, server_default='')
    play_code = db.Column(db.String(1024), nullable=False, server_default='')

    collections = db.relationship(
        'Collection', secondary='collection_video', back_populates="videos")

    publish_tm = db.Column(
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
                title=self.title,
                type=self.type,
                top=self.top,
                visible=self.visible,
                image=add_image_domain(self.image),
                imageCode=self.image,
                play_url=self.play_url,
                play_code=self.play_code,
                isvr=self.isvr,
                created_at=self.created_at,
                publish_tm=self.publish_tm
            )


class CollectionStatus:
    """when use sqlalchemy server_default,  server_default=0 is wrong.

        from sqlalchemy.sql import text
        ... server_default=text('0')

        The result is  DEFAULT 0.
    """

    HIDDEN = '0'
    SHOWN = '1'


collection_enums = (
    "china_team",
    "medal_time",
    "focus",
    "match_side",
    "no_deadzone",
    "highlights",
    "storm_eye",
    "info_station")


class Collection(db.Model):
    __bind_key__ = 'olympics'
    __tablename__ = 'collection'

    id = db.Column(db.Integer, primary_key=True)
    title = db.Column(db.String(256), nullable=False)
    type = db.Column(
        db.Enum(*collection_enums),
        nullable=False, server_default="china_team")
    visible = db.Column(
        TINYINT,
        nullable=False,
        server_default=text(CollectionStatus.HIDDEN))
    display_order = db.Column(
        db.Integer, nullable=False, server_default=text('0'))
    image = db.Column(db.String(256), nullable=False)

    videos = db.relationship(
        'OGVideo', secondary='collection_video', back_populates="collections")

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
            'visible': self.visible,
            'type': self.type,
            'image': add_image_domain(self.image),
            'imageCode': self.image,
            'created_at': self.created_at,
        }


class CollectionVideo(db.Model):
    __bind_key__ = 'olympics'
    __tablename__ = 'collection_video'

    id = db.Column(db.Integer, primary_key=True)
    collection_id = db.Column(
        db.Integer,
        db.ForeignKey(
            'collection.id', ondelete="CASCADE", onupdate="CASCADE"))
    video_id = db.Column(
        db.Integer,
        db.ForeignKey('og_video.id', ondelete="CASCADE", onupdate="CASCADE"))

    created_at = db.Column(
        db.TIMESTAMP,
        server_default=db.func.current_timestamp(),
        nullable=False)

    updated_at = db.Column(
        db.TIMESTAMP,
        server_default=text(
            'CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP'),
        nullable=False)


class OGChampion(db.Model):
    __bind_key__ = 'olympics'
    __tablename__ = 'og_champion'

    id = db.Column(db.Integer, primary_key=True)
    name = db.Column(db.String(128), nullable=False)
    num = db.Column(db.Integer, nullable=False)
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

    def to_dict(self):
        return dict(
            id=self.id,
            name=self.name,
            num=self.num,
            image=add_image_domain(self.image),
            created_at=to_timestamp(self.created_at),
            updated_at=to_timestamp(self.updated_at)
        )


class ChampionNews(db.Model):
    __bind_key__ = 'olympics'
    __tablename__ = 'champion_news'

    id = db.Column(db.Integer, primary_key=True)
    champion_id = db.Column(db.Integer, nullable=False)
    news_id = db.Column(db.Integer, nullable=False)
    title = db.Column(db.String(128), nullable=False)
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
            champion_id=self.champion_id,
            news_id=self.news_id,
            title=self.title,
            type=self.type,
            created_at=self.created_at,
            updated_at=self.updated_at

        )


class OGMedal(db.Model):
    __bind_key__ = 'olympics'
    __tablename__ = 'og_medal'

    id = db.Column(db.Integer, primary_key=True)
    country = db.Column(db.String(32), nullable=False, server_default='')
    gold_medals = db.Column(
        db.Integer, nullable=False, server_default=text('0'))
    silver_medals = db.Column(
        db.Integer, nullable=False, server_default=text('0'))
    bronze_medals = db.Column(
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
            country=self.country,
            gold_medals=self.gold_medals,
            silver_medals=self.silver_medals,
            bronze_medals=self.bronze_medals,
            created_at=self.created_at,
            updated_at=self.updated_at
        )


class OGHtml(db.Model):
    __bind_key__ = 'olympics'
    __tablename__ = 'og_html'

    id = db.Column(db.Integer, primary_key=True)
    title = db.Column(db.String(128), nullable=False, server_default='')
    type = db.Column(db.String(20), nullable=False)
    html = db.Column(db.Text)
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
            type=self.type,
            html=self.html,
            created_at=self.created_at,
            updated_at=self.updated_at
        )


class OGSchedule(db.Model):
    __bind_key__ = 'olympics'
    __tablename__ = 'og_schedule'

    id = db.Column(db.Integer, primary_key=True)
    start_tm = db.Column(
        db.TIMESTAMP, nullable=False, server_default=text('0'))
    large_project = db.Column(
        db.String(64), nullable=False, server_default='')
    small_project = db.Column(
        db.String(64), nullable=False, server_default='')
    round = db.Column(
        db.String(64), nullable=False, server_default='')
    is_china = db.Column(TINYINT, nullable=False, server_default=text('0'))
    is_medal = db.Column(TINYINT, nullable=False, server_default=text('0'))
    top = db.Column(TINYINT, nullable=False, server_default=text('0'))

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
                start_tm=self.start_tm,
                large_project=self.large_project,
                small_project=self.small_project,
                round=self.round,
                top=self.top,
                is_china=self.is_china,
                is_medal=self.is_medal
            )


class OGGallery(db.Model):
    __bind_key__ = 'olympics'
    __tablename__ = 'og_gallery'

    id = db.Column(db.Integer, primary_key=True)
    title = db.Column(db.String(256), nullable=False)
    brief = db.Column(db.String(256), nullable=False, server_default='')
    visible = db.Column(
        TINYINT,
        nullable=False,
        server_default=text('0'))
    top = db.Column(TINYINT, nullable=False, server_default=text('0'))
    image = db.Column(db.String(256), nullable=False)

    gallery_images = db.relationship(
        'OGGalleryImage', backref='og_gallery', lazy='dynamic')

    publish_tm = db.Column(
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
            title=self.title,
            brief=self.brief,
            visible=self.visible,
            top=self.top,
            image=add_image_domain(self.image),
            publish_tm=self.publish_tm,
            created_at=self.created_at,
            updated_at=self.updated_at
        )


class OGGalleryImage(db.Model):
    __bind_key__ = 'olympics'
    __tablename__ = 'og_gallery_image'

    id = db.Column(db.Integer, primary_key=True)
    gallery_id = db.Column(
        db.Integer,
        db.ForeignKey('og_gallery.id', ondelete="CASCADE", onupdate="CASCADE"))

    title = db.Column(db.String(256), nullable=False)
    desc = db.Column(db.String(1024), nullable=False)
    image = db.Column(db.String(256), nullable=False)
    url = db.Column(db.String(512), nullable=False, server_default='')

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
            gallery_id=self.gallery_id,
            title=self.title,
            desc=self.desc,
            image=add_image_domain(self.image),
            url=self.url,
            created_at=self.created_at,
            updated_at=self.updated_at
        )


class OGLive(db.Model):
    __bind_key__ = 'olympics'
    __tablename__ = 'og_live'

    id = db.Column(db.Integer, primary_key=True)
    title = db.Column(db.String(128), nullable=False)
    source = db.Column(db.String(512), nullable=False)
    status = db.Column(db.String(20), nullable=False)
    switch_time = db.Column(
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
            title=self.title,
            source=self.source,
            status=self.status,
            switch_time = self.switch_time,
            created_at=self.created_at,
            updated_at=self.updated_at
        )
