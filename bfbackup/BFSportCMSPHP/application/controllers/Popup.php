<?php
/**
 * app启动活动管理
 * @autho chaodalong@baofeng.com
 * @date 2016.11.02
 */
defined('BASEPATH') OR exit('No direct script access allowed');

class Popup extends MY_Controller {
    private $limit = 20;
    public function __construct() {
        parent::__construct();
        $this->load->model('popup_model','PM');
    }

    /**
     * 列表
     * @param int $offset
     */
    public function index($offset=0){
        $condition = array();
        $keyword = isset($_GET['keyword']) ? $_GET['keyword'] : '';
        if (!empty($keyword)) {
            $condition['title'] = $keyword;
        }
        $list = $this->PM->getList($condition, $this->limit, $offset);
        $total = $this->PM->getCount($condition);

        $data['total'] = $total;
        $data['page'] = $this->_pagination($total, $this->limit, '/popup/index');
        $data['list'] = $list;
        $data['keyword'] = $keyword;
        $this->load->view('popup/list', $data);
    }

    /**
     * 添加
     */
    public function add(){
        $id = $this->input->get('id');
        $data['id'] = $id;

        if ($_POST) {
            $data = array(
                'platf'=>$this->input->post('platf'),
                'version'=>$this->input->post('version'),
                'channel'=>$this->input->post('channel'),
                'title'=>$this->input->post('title'),
                'image'=>$this->input->post('poster'),
                'url'=>$this->input->post('url'),
                'start_tm'=>$this->input->post('start_tm'),
                'finish_tm'=>$this->input->post('finish_tm'),
            );
            if ($id) {
                $this->PM->dbSports->update('popup', $data, array('id'=>$id));
            } else {
                $this->PM->dbSports->insert('popup', $data);
            }
            redirect('popup/index');
        } else {
            $info = $this->PM->getInfo($id);

            $data['info'] = $info;
            $this->load->view('popup/add', $data);
        }
    }

    /**
     * 删除
     */
    public function remove(){
        $id = $_POST['id'];
        if (strpos($id,',').'' != '') {
            $ids = explode(',',$id);
            try {
                $this->PM->dbSports->remove_batch('popup',$ids);
                echo 'success';
            } catch (Exception $e) {
                echo 'fail';
            }
        } else {
            try {
                $this->PM->dbSports->remove('popup',$id);
                echo 'success';
            } catch (Exception $e) {
                echo 'fail';
            }
        }
    }
}
