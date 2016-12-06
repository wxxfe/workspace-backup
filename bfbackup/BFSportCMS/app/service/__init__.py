# -*- coding: utf-8 -*-

from .channel import ChannelService
from .channel import NewChannelService
from .channel import WSHeadlineService
from .subpage import SubPageService

from .activity import ActivityService
from .event import EventService
from .event import SelectedMatchService
from .event import EventNewsService
from .match import SiteService
from .match import MatchService
from .match import MatchLiveService
from .match import MatchVideoService
from .match import MatchNewsService
from .media_video import PendingVideoService
from .slide import SlideService
from .team import TeamService
from .team import EventTeamService
from .player import PlayerService
from .player import TeamPlayerService
from .section import SectionService
from .section import SectionPostService
from .program import ProgramService
from .program import ProgramPostService
from .scoreboard import ScoreboardService
from .scoreboard import TopscorerService
from .scoreboard import TopassistService
from .mainboard import MainBoardSlideService
from .mainboard import MainBoardSelectedMatchService
from .mainboard import MainBoardHotspotService
from .mainboard import MainBoardDiscoveryService
from .mainboard import MainBoardColumnService
from .mainboard import MainBoardColumnPostService

# olympics
from .olympics import OGNewsService
from .olympics import OGVideoService
from .olympics import CollectionService
from .olympics import CollectionVideoService
from .olympics import OGScheduleService

from .olympics import OlympicsSlideService
from .olympics import OGMedalService
from .olympics import OGHtmlService
from .olympics import OlympicsChampionService
from .olympics import OlympicsGalleryService
from .olympics import OlympicsGalleryImageService
from .olympics import OlympicsChampionNewsService
from .olympics import OGLiveService
