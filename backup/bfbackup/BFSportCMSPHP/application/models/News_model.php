<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class News_model extends MY_Model {
    
    public function __construct() {
        parent::__construct();
    }

    /**
     * 获取新闻列表
     * @param int $offset 偏移
     * @param int $limit 每页数量
     * @return array
     */
    public function getAllNews($offset=0,$limit=20){
        
        // 去除原sql的group by逻辑，一条新闻只能关联一场比赛
        $sql = "SELECT `news`.*, `site`.`origin` as `site_name`, `match_related`.`match_id` as `rm_id` FROM `news` 
                LEFT JOIN `site` ON `news`.`site`=`site`.`site` 
                LEFT JOIN `match_related` ON (`news`.`id`=`match_related`.`ref_id` AND `match_related`.`type`='news') 
                JOIN (SELECT id FROM `news` ORDER BY `news`.`publish_tm` DESC LIMIT {$limit} OFFSET {$offset}) AS news_order ON `news`.id=news_order.id 
                ORDER BY `news`.`publish_tm` DESC";
        
        
        $query = $this->db('sports')->query($sql);

        return $query->result_array();

    }

    /**
     * 根据新闻ID获取新闻数据
     * @param int $newsId
     * @return array
     */
    public function getNewsById($newsId){

        $query = $this->dbSports->get_where('news',array('id' => intval($newsId)));

        return $query->row_array();

    }

    /**
     * 根据新闻ID获取该新闻下的标签
     * @param int $newsId
     * @return string 以','号分隔的ID字符串
     */
    public function getTagsByNewsId($newsId){

        $query = $this->dbSports->get_where('news_tag',array('news_id' => intval($newsId)));
        $tags = $query->result_array();

        $tagIds = array();
        foreach($tags as $tag){
            $tagIds[] = $tag['tag_id'];
        }

        return implode(',',$tagIds);

    }

    /**
     * 根据新闻ID获取该新闻关联的比赛ID
     * @param int $newsId
     * @return int
     */
    public function getMatchIdByNewsId($newsId){

        $query = $this->dbSports->get_where('match_related',array('ref_id' => intval($newsId), 'type' => 'news'));
        $result = $query->row_array();

        return !empty($result) ? $result['match_id'] : '';

    }

    /**
     * 获取新闻的相关推荐
     * @param int $newsId 新闻ID
     * @return array
     */
    public function getNewsRelateds($newsId){

        $query = $this->dbSports
            ->order_by('priority','DESC')
            ->get_where('news_related',array('news_id' => intval($newsId)));

        return $query->result_array();

    }

    public function getSearchResult($type,$keyword,$tagId,$offset=0,$limit=20){
        $full = array('total' => $searchResult['total']);
        $fdata = array();
        foreach($searchResult['result'] as $item){
            $top = $this->dbSports->where(array('ref_id' => $item['id'], 'tag_id' => intval($tagId)))->get('manual_priority')->row_array();
            $tag = $this->dbSports->where(array($type.'_id' => $item['id'], 'tag_id' => intval($tagId)))->get($type.'_tag')->row_array();
            $item['is_top'] = !empty($top);
            $item['has_tag'] = !empty($tag);
            $item['tid'] = $top['id'];
            $fdata[] = $item;
        }

        $full['result'] = $fdata;

        return $full;
    }
    
    public function addLiveHistory($data) {
        return $this->db('kungfu')->insert('live_news', $data);
    }

    public function getLivehistory($match_id) {
        $query = $this->db('kungfu')->select('*')
            ->from('live_news')
            ->where('match_id', $match_id)
            ->order_by('created_at', 'DESC')
            ->get();
        return $query->result_array();
    }
}
