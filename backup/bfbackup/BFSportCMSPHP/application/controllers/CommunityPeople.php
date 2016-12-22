<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class CommunityPeople extends MY_Controller {

    public function __construct() {
        parent::__construct();
        $this->load->model('community_people_model', 'CPM');
    }

    /**
     * 社区达人列表页
     */
    public function index($offset=0) {

        $data = array();
        $limit = 20;

        $total = $this->CPM->db('user')->getTotal('user_extra');
        $community_people = $this->CPM->getAllCp($offset,$limit);

        $data['total'] = $total;
        $data['offset'] = $offset;
        $data['allCp'] = array();
        if(!empty($community_people))
        {
            foreach($community_people as $k =>$v)
            {
                $data['allCp'][$k]['name'] = $this->UM->db("user")->select('name')->where('id',$v['user_id'])->get("user")->result_array()[0]["name"];
                $data['allCp'][$k]['type'] = $this->UM->db("user")->select('name')->where('id',$v['topfinger_id'])->get("topfinger")->result_array()[0]["name"];
                $data['allCp'][$k]['ctime'] = $v["created_at"];
                $data['allCp'][$k]['id'] = $v["id"];
            }
        }
        $data['page'] = $this->_pagination($total,$limit,'/CommunityPeople/index/');

        $this->load->view('community_people/list',$data);
    }

    /**
     * 社区达人类型列表页
     */
  /*  public function typeIndex($offset=0) {

        $this->load->model('Community_type_model', 'CTM');

        $data = array();
        $limit = 20;

        $total = $this->CTM->db('sports')->getTotal('slide');
        $cp_type = $this->CTM->getAllCpType($offset,$limit);

        $data['total'] = $total;
        $data['allCpType'] = $cp_type ? $cp_type : array();
        $data['page'] = $this->_pagination($total,$limit,'/CommunityPeople/typeIndex/');

        $this->load->view('community_people/type_list',$data);
    }
*/
    /**
     * 增加或编辑社区达人
     */
    public function edit($cp_id = 0) {
        $this->load->model('Community_type_model','CTM');
        $referer = @strval($_GET['referer']);

        $info = $_POST;
        if ($info) {
            // 达人表
            $cp_id = $this->input->post('cp_id');
            $update_tag = 1;
            $community_people = array();
            $community_people['topfinger_id'] = $info['type'];
            if ($cp_id) {
                $user_info = $this->UM->db("user")->select('user_id,topfinger_id')->where('id',$cp_id)->get("user_extra")->result_array()[0];
                $push_data['user_id'] = $user_info['user_id'];
                if($user_info['topfinger_id'] == $info['type'])
                {
                    $update_tag = 0;    
                }
                $community_people['updated_at'] = date("Y-m-d H:i:s");
                $this->CPM->db('user')->update('user_extra',$community_people,array('id' => $cp_id));
            } else {
                $push_data['user_id'] = $info['id'];
                $community_people['user_id'] = $info['id'];
                $community_people['created_at'] = date("Y-m-d H:i:s");
                $this->CPM->db('user')->insert('user_extra',$community_people);
            }
            $push_data['type'] = 's_genius';
            $type_name =  $this->CPM->db("user")->select('name')->where('id',$info['type'])->get("topfinger")->result_array()[0]['name'];
            $push_data['data'] = json_encode(array('content'=>$type_name));
            if($update_tag != 0)
            {
                $this->CPM->db('board')->insert('broadcast',$push_data);
            }
            redirect(base_url('/CommunityPeople/index'));
        }
        $cp_id = $this->input->get('cp_id');
        $data['referer'] = $referer;
        $data['community_people'] =array();
        if(!empty($cp_id))
        {
            $data['community_people'] = $this->CPM->getInfoById($cp_id) ;
            $name = $this->UM->db("user")->select('name')->where('id',$data['community_people'][0]['user_id'])->get("user")->result_array()[0]["name"];
            $data['community_people'][0]['name'] = $name;
        }
        $data['cp_type'] = $this->CTM->getCommunityPeopleType();
        $data['cp_id'] = $cp_id;
        $this->load->view('community_people/edit',$data);
    }

    /**
     * 增加或修改达人类型
     */
   /* public function typeEdit($type_id = 0) {
        $this->load->model('Community_type_model','CTM');
        $referer = @strval($_GET['referer']);

        $info = $_POST;
        if ($info) {
            $type_id = $this->input->post('cp_id');
            // 达人类型表
            $cp_type = array();
            $cp_type['name'] = $info['name'];
            $cp_type['image'] = $info['image'];
            $cp_type['created_at'] = $info['created_at'];
            $cp_type['updated_at'] = $info['updated_at'];
            if ($type_id) {
                $this->CTM->db('xxx')->update('xxx',$cp_type,array('id' => $type_id));
            } else {
                $this->CTM->db('xxx')->insert('xxx',$cp_type);
            }

            exit();
        }

        $type_id = $this->input->get('cp_id');
        $data['referer'] = $referer;
        //$data['type_info'] = ($type_id > 0)? $this->CTM->getInfoById($type_id) : array();
        $data['cp_id'] = $type_id;

        $this->load->view('community_people/type_edit',$data);
    }
*/
    public function getNickById() {
        $id = $_POST['id'];
        if($this->UM->db("user")->select('*')->where('user_id',$id)->get("user_extra")->result_array()) {
            echo "exist";
        }else{
            $num = $this->UM->db("user")->select('name')->where('id',$id)->get("user")->result_array();
            if(isset($num[0]) && $num[0]["name"] != "")
            {
                echo $num[0]["name"];
            }
        }
    }

    public function remove() {
        $id = $_POST['id'];
        try{
            $this->CPM->db('user')->remove('user_extra',$id);
            echo 'success';
        }catch(Exception $e){
            echo 'fail';
        }
    }
}
