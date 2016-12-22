<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Tag_model extends MY_Model {

    private $_fakeBase = 1000000;

    private $_fakePrefixes = array(
        // prefix => type
        1 => 'none',
        2 => 'sports',
        3 => 'event',
        4 => 'team',
        5 => 'player',
    );

    private $_typeNames = array(
        'none'      => '其他',
        'sports'    => '项目',
        'event'     => '赛事',
        'team'      => '球队',
        'player'    => '球员',
    );

    public function __construct() {
        parent::__construct();
    }

    public function getCategories($visible = null) {
        $where = array();
        if (isset($visible)) {
            $where['visible'] = intval($visible);
        }
        return $this->dbSports->order_by('id')->get_where('tag_category', $where)->result_array();
    }

    /**
     * 获取标签选择组件所需数据
     * @return array
     */
    public function getSelectionTags() {
        $tags = array();
        
        $tagCategory = $this->dbSports->get('tag_category')->result_array();
        foreach ($tagCategory as $key => $category) {
            $item = array(
                'category_id' => $category['id'],
                'name' => $category['name'],
                'type' => $category['type'],
            );
            if ($category['type'] !== 'none') {
                if ($category['type'] == 'sports') {
                    $item['data'] = $this->dbSports->get($category['type'])->result_array();
                } else {
                    $item['data'] = array();
                }
            } else {
                $item['data'] = $this->dbSports->get_where('tag', array('category_id' => $category['id'],'visible' => 1))->result_array();
            }
            $tags[] = $item;
        }
        
        return $tags;
    }

    /**
     * 根据Fake ID获取标签名称
     * @param string $ids 例："1000001,2000000,3000003"
     * @return array
     */
    public function getTagsByFakeId($ids) {
        if (empty($ids)) return array();
        $ids = explode(',', $ids);
        $tags = array();
        foreach ($ids as $item) {
            $type = $this->getTypeByFakeId($item);
            $id = $this->getIdByFakeId($item);
            
            $row = $this->_dbCurrent->get_where($this->getTableByType($type), array('id' => $id))->row_array();
            if ($row) {
                $tags[] = array('id' => $item, 'name' => $row['name']);
            }
        }
        return $tags;
    }

    /**
     * 根据标签ID返回原始ID
     * @param int $fakeId 
     * @return int
     */
    public function getIdByFakeId($fakeId) {
        if (isset($this->_fakePrefixes[$this->getFakeIdPrefix($fakeId)])) {
            return $fakeId % $this->_fakeBase;
        } else {
            return $fakeId;
        }
    }

    /**
     * 根据Fake ID返回标签类型
     * @param int $fakeId
     * @return string
     */
    public function getTypeByFakeId($fakeId) {
        $prefix = $this->getFakeIdPrefix($fakeId);
        if (isset($this->_fakePrefixes[$prefix])) {
            return $this->_fakePrefixes[$prefix];
        }
        return '';
    }

    /**
     * 生成Fake ID
     * @param string $type 标签类型
     * @param int $id 标签ID
     * @return int
     */
    public function makeFakeId($type, $id) {
        $a = array_flip($this->_fakePrefixes);
        $prefix = isset($a[$type])? $a[$type] : 1;
        return $prefix * $this->_fakeBase + intval($id);
    }

    /**
     * 根据Fake ID返回前缀
     * @param int $fakeId
     * @return string
     */
    public function getFakeIdPrefix($fakeId) {
        return $fakeId / $this->_fakeBase;
    }
    
    public function getTables() {
        $tables = array();
        foreach ($this->_fakePrefixes as $type) {
            $tables[$type] = $this->getTableByType($type);
        }
        return $tables;
    }
    
    /**
     * 根据TAG类型获取对应的表名
     * TODO: team和player暂时没有表，如果以后表名跟类型不相同，还需要再修改
     *
     * @param string $type
     * @return string
     */
    public function getTableByType($type) {
        $table = 'tag';
        $a = array_flip($this->_fakePrefixes);
        if (isset($a[$type]) && $type != 'none') {
            $table = $type;
        }
        return $table;
    }
    
    public function getParentType($type) {
        $index = array_search($type, $this->_fakePrefixes);
        if ($index > 2) {
            return $this->_fakePrefixes[$index - 1];
        }
        return '';
    }
    
}
