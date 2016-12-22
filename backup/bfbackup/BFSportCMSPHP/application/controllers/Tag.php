<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Tag extends MY_Controller {

    public function __construct() {
        parent::__construct();
        $this->load->model('Tag_model', 'TM');
    }

    public function index($keyword = '-', $filter = '') {
        $keyword = trim(urldecode($keyword));
        $filter = trim(urldecode($filter));
        
        $filters = array();
        if ($filter) {
            $filters = json_decode($filter, true);
        }
        
        $parent_ids = array();
        $cate_tags = array();
        $categories = $this->TM->getCategories(1);
        foreach ($categories as $cate) {
            $parent_type = $this->TM->getParentType($cate['type']);
            $tags = $this->_getCateTags($cate['type'], $cate['id'], @$parent_ids[$parent_type]);
            foreach ($tags as $k => $tag) {
                // 搜索
                if ($keyword && $keyword != '-' && stristr($tag['name'], $keyword) === false) {
                    unset($tags[$k]);
                    continue;
                }
                
                // 该类型的所有标签id，在过滤其子类型时使用
                $parent_ids[$cate['type']][] = $tag['id'];
                
                // 如果没有过滤条件，则默认选中每个类型下的第一个标签进行过滤
                if (/* !$filter &&  */ !isset($filters[$cate['type']]) && !in_array($cate['type'], array('player', 'none'))) {
                    $filters[$cate['type']] = $tag['id'];
                }
            }
            
            if (!($keyword && $keyword != '-')) {
                $parent_ids[$cate['type']][] = 0;
            }
            if (isset($filters[$cate['type']])) {
                $parent_ids[$cate['type']] = array();
                $parent_ids[$cate['type']][] = $filters[$cate['type']];
            }
            
            $tags = array_values($tags);
            $cate_tags[$cate['id']] = $tags;
        }
        
        $data['keyword'] = ($keyword && $keyword != '-')? trim($keyword) : '';
        $data['filter'] = json_encode($filters);
        $data['categories'] = $categories;
        $data['cate_tags'] = $cate_tags;
        $this->load->view('tag/list', $data);
    }
    
    public function add($cate_id = 0) {
        $cate_id = intval($cate_id);
        $field = $_POST['name'];
        $value = $_POST['value'];
        if ($cate_id
        && is_numeric($this->TM->db('sports')->insert('tag', array($field => $value, 'category_id' => $cate_id, 'editable' => 1)))) {
            echo '添加成功';
        } else {
            show_error();
        }
        exit();
    }
    
    public function update() {
        $id = $_POST['pk'];
        $field = $_POST['name'];
        $value = $_POST['value'];
        $r = $this->TM->db('sports')->update('tag', array($field => $value), array('id' => $id, 'editable' => 1));
        if (is_numeric($r)) {
            echo '修改成功';
        } else {
            show_error($r);
        }
        exit();
    }
    
    public function delete() {
        $id = $_POST['pk'];
        if ($this->TM->db('sports')->remove('tag', $id)) {
            echo '删除成功';
        } else {
            show_error();
        }
        exit();
    }

    public function category($action = 'list') {
        $id = isset($_POST['id'])? intval($_POST['id']) : 0;
        $name = isset($_POST['name'])? addslashes(trim($_POST['name'])) : '';
        
        if ($action == 'list') {
            if ($name) {
                // insert
                $insert_id = $this->TM->db('sports')->insert('tag_category', array('name' => $name, 'type' => 'none', 'editable' => 1));
            }
            $data['list'] = $this->TM->getCategories();
            $this->load->view('tag/category', $data);
        } else {
            // ajax
            if ($id) {
                if ($action == 'update') {
                    $visible = isset($_POST['visible'])? intval($_POST['visible']) : 0;
                    $update_count = $this->TM->db('sports')->update('tag_category', array('name' => $name, 'visible' => $visible), array('id' => $id, 'editable' => 1));
                    echo $update_count;
                } elseif ($action == 'remove') {
                    $count = $this->TM->db('sports')->select('count(*) AS count')->get_where('tag', array('category_id' => $id))->row_array()['count'];
                    if ($count > 0) {
                        echo '该分类下有标签，不可删除！';
                    } else {
                        $delete_count = $this->TM->db('sports')->delete('tag_category', array('id' => $id, 'editable' => 1));
                        echo $delete_count;
                    }
                }
            }
            exit();
        }
    }
    
    private function _getCateTags($cate, $cate_id, $parent_ids = array()) {
        $event_season_ids = $this->_getEventSeasonIds();
        
        $table = $this->TM->getTableByType($cate);
        $where = array();
        if ($table == 'event') {
            if ($parent_ids) {
                $this->TM->db('sports')->where_in('sports_id', $parent_ids);
            }
            $where['visible'] = 1;
            $this->TM->db('sports')->where_in('id', array_merge(array_keys($event_season_ids), array(0)));
        } elseif ($table == 'team') {
            if ($parent_ids) {
                $this->load->model('Team_model', 'TeamM');
                $this->TM->db('sports')->where_in('id', array_merge($this->TeamM->getEventTeamIds($parent_ids, $event_season_ids), array(0)));
            }
        } elseif ($table == 'player') {
            if ($parent_ids) {
                $this->load->model('Team_model', 'TeamM');
                $this->TM->db('sports')->where_in('id', array_merge($this->TeamM->getTeamPlayerIds($parent_ids, $event_season_ids), array(0)));
            }
        } elseif ($table == 'tag') {
            $where = array('category_id' => $cate_id, 'visible' => 1);
        } else {
            $where = array();
        }
        
        return $this->TM->db('sports')->get_where($table, $where)->result_array();
    }
    
    private function _getEventSeasonIds() {
        if (!isset($this->_eventSeasonIds) || empty($this->_eventSeasonIds)) {
            $this->load->model('Event_model', 'EM');
            $this->_eventSeasonIds = $this->EM->getCurrentEventSeasonIds();
        }
        return $this->_eventSeasonIds;
    }

}
