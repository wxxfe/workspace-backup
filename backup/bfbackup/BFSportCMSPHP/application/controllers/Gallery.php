<?php
defined('BASEPATH') OR exit('No direct script access allowed');

/**
 * Class Gallery
 * 图集和图集的图片
 */
class Gallery extends MY_Controller {
    /**
     * @var string 模块名
     */
    private $title = array('module_title' => '图集', 'module_title_en' => 'Gallery');

    /**
     * 映射控制器方法的路由url
     */
    private $url = array(
        'list_url' => '/gallery/index/',
        'add_url' => '/gallery/add',
        'in_place_edit_url' => '/gallery/inPlaceEdit',
        'edit_url' => '/gallery/edit/',
        'del_url' => '/gallery/del',
        'add_images_url' => '/gallery/addImages',
        'edit_image_url' => '/gallery/editImage',
        'del_image_url' => '/gallery/delImage/'
    );

    /**
     * @var int 分页每页条数
     */
    private $page_limit = 20;

    public function __construct() {
        parent::__construct();
        //载入控制器用到的模型，第二个参数是别名
        $this->load->model('Gallery_model', 'm');
        $this->load->model('Tag_model', 'tm');
        $this->load->model('Search_model', 'sm');
    }

    /**
     * 最后一段是控制器类名的路由url映射的方法
     * 列表页显示数据处理
     * 如果有搜索关键字数据，则是搜索列表
     * 否则是正常列表
     * @param int $offset 分页偏移量
     */
    public function index($offset = 0) {
        $data = array_merge($this->title, $this->url);

        $page_limit = $this->page_limit;

        $keyword = $this->input->get('keyword');

        if ($keyword) {
            if (is_numeric($keyword)) {
                $list = array_filter(array($this->m->getItem($keyword)));
                $page_total = 1;
            } else {
                $search_result = $this->sm->itemsQuery('gallery', $keyword, $offset, $page_limit);
                $list = $search_result['result'];
                $page_total = $search_result['total'];
            }
            $data['keyword'] = $keyword;
        } else {
            $list = $this->m->getList($page_limit, $offset);
            $page_total = $this->m->getTotal('gallery');
        }

        $this->_pageCheck($offset, $page_limit, $page_total, $data['list_url']);

        $data = array_merge($data, $this->_pageData($offset, $page_limit, $page_total, $data['list_url']));

        $data['list'] = array();
        foreach ($list as $item) {
            $id = $item['id'];
            $item['tags'] = $this->tm->db('sports')->getTagsByFakeId($this->m->getTagsId($id));
            $item['images_num'] = $this->m->getImagesNum($id);
            $item['match_id'] = $this->m->getMatchId($id);

            array_push($data['list'], $item);
        }

        $this->load->view('gallery/list', $data);
    }

    /**
     * 添加
     */
    public function add() {
        /*
         * 如果post的表单数据为空，则显示添加页
         * 否则把post数据插入数据库，并返回ID
         */
        if (empty($this->input->post())) {
            $data = array_merge($this->title, $this->url);
            $data['redirect'] = $data['list_url'];
            $data['main_title'] = '添加' . $this->title['module_title'];
            $data['submit_btn'] = '添加';
            $data['title'] = '';
            $data['brief'] = '';
            $data['visible'] = 0;
            $data['match_id'] = '';
            $data['image'] = '';
            $data['tags'] = '';
            $data['img_list'] = array();

            $data['publish_tm'] = date("Y-m-d H:i:s", time());

            $data['sites'] = $this->m->db->get('site')->result_array();
            $data['origin'] = '暴风体育';

            $this->load->view('gallery/add_edit', $data);
        } else {
            echo $this->m->add();
        }
    }

    /**
     * 编辑
     * @param $id 编辑的数据ID
     */
    public function edit($id) {
        /*
         * 如果post的表单数据为空，则显示编辑页
         * 否则用post数据更新数据库，然后跳转回上一页
         */
        if (empty($this->input->post())) {
            $data = array_merge($this->title, $this->url, $this->m->getItem($id));
            $data['main_title'] = '编辑' . $this->title['module_title'];
            $data['submit_btn'] = '保存';
            $data['redirect'] = $this->input->get('redirect');

            $data['sites'] = $this->m->db->get('site')->result_array();
            $data['site_name'] = $data['origin'];

            $this->load->view('gallery/add_edit', $data);
        } else {
            $this->m->edit($id);
            // 9月30日，图集下线需要同时删除图集相关标签，关联关系。删除比赛关联的图集关系
            $visible = $this->input->post('visible');
            if ($visible == 0) {
                $this->_deleteRelated('gallery', $id);
            }
        }
    }

    /**
     * 就地编辑
     */
    public function inPlaceEdit() {
        $this->m->inPlaceEdit('gallery');
        
        // 9月30日，图集下线需要同时删除图集相关标签，关联关系。删除比赛关联的图集关系
        $fieldK = $this->input->post('name');
        $fieldV = $this->input->post('value');
        $fieldId = $this->input->post('pk');
        if ($fieldK == 'visible' && $fieldV == 0) {
            $this->_deleteRelated('gallery', $fieldId);
        }
    }

    /**
     * 删除，包括批量删除
     */
    public function del() {
        $this->m->del();
    }

    /**
     * 添加图集的图片
     * 返回所有图片数据
     */
    public function addImages() {
        echo json_encode($this->m->addImages());
    }

    /**
     * 编辑图集的图片
     * 返回所有图片数据
     */
    public function editImage() {
        echo json_encode($this->m->editImage());
    }

    /**
     * 删除图集的图片
     * @param $id 图片ID
     */
    public function delImage($id) {
        $this->m->delImage($id);
    }
    
    /**
     * 图集相关推荐
     * @param intval $id
     */
    public function related($id){
        // $this->load->model('Tag_model', 'TM');
    
        $relateds = $this->m->getRelateds($id);
        $gallery = $this->m->getItem($id);
        
        $data = array();
        $data['gallery'] = $gallery;
        $data['list'] = $relateds;
        $this->load->view('gallery/related', $data);
    }
    
    public function setRelated($id,$type,$relatedId){
        $numargs = func_num_args();
        if($numargs < 3) show_404();
        $related = array(
                'gallery_id' => intval($id),
                'type' => $type,
                'ref_id' => $relatedId,
                'priority' => 0
        );
        $r = $this->m->db('sports')->insert('gallery_related',$related);
        $this->m->setGalleryUpdateTime($id);
        if($r) redirect(base_url('/gallery/related/'.$id));
    }
    
    public function cancelRelated($rid){
        try{
            // 检查gallery_id，修改gallery表格对应内容的updated_at字段
            $this->m->db('sports')->where('id', $rid);
            $query = $this->m->db('sports')->get('gallery_related');
            $data = $query->row_array();
            $this->m->setGalleryUpdateTime($data['gallery_id']);
            ///////
            
            $this->m->db('sports')->remove('gallery_related',$rid);
            echo 'success';
        }catch(Exception $e){
            echo 'fail';
        }
    }
    
    public function updateSort(){
        $data = $_POST;
        $rid = $data['pk'];
        $gallery_id = $data['gallery_id'];
        $sort = intval($data['value']);
        $result = $this->m->db('sports')->setTbSort('gallery_related',$rid,array('priority' => $sort),array('gallery_id' => $gallery_id));
        $this->m->setGalleryUpdateTime($gallery_id);
        echo $result ? 'success' : 'fail';
    }
    
    public function batchSync() {
        $data = $_POST;
        $name = $this->input->post('name');
        $value = $this->input->post('value');
        $gallery_id = $this->input->post('gallery_id');
        if (empty($gallery_id)) {
            return false;
        }
        
        $this->m->db('sports')->update('gallery_image', array($name=>$value), array('gallery_id'=>$gallery_id));
        
        return true;
    }
    
    public function search() {
        
        $keyword = $this->input->get('keyword');
        $limit   = $this->input->get('limit');
        $offset  = $this->input->get('offset');
        
        if (is_numeric($keyword)) {
            $item = $this->m->db('sports')->get_where('gallery', array('id' => $keyword))->row_array();
            if (!empty($item)) {
                $item['id'] = intval($item['id']);
                $list = array($item);
                $page_total = 1;
            } else {
                $list = array();
                $page_total = 0;
            }
        } else {
            $search_result = $this->sm->itemsQuery('gallery', $keyword, $offset, $limit);
            $list = $search_result['result'];
            $page_total = $search_result['total'];
        }
        
        foreach ($list as & $item) {
            $id = $item['id'];
            $item['images_num'] = $this->m->getImagesNum($id);
        }
        
        $result = array(
            'status' => 1,
            'list'   => $list,
            'total'  => $page_total,
        );
        
        echo json_encode($result);
        return true;
    }
    
    public function info() {
        $id = $this->input->get('id');
        
        $info = $this->m->getItem($id);
        
        $result = array(
            'status' => 1,
            'info'   => $info,
        );
        
        echo json_encode($result);
        return true;
    }
}
