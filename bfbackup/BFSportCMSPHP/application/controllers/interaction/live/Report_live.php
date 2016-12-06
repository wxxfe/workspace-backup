<?php
defined('BASEPATH') OR exit('No direct script access allowed');

//战报
class Report_live extends MY_Controller {

    public function __construct() {
        parent::__construct();
        
        $this->load->model('interaction/live/Report_model', 'RM');
    }

    public function index($matchId, $host='') {
        $data = array();
        $data['matchId'] = $matchId;
        $report_list = $this->RM->getAllReport($matchId);
        foreach ($report_list as $k=>$v) {
            $report_list[$k]['report_tm'] /= 60;
        }
        
        $data['reportlist'] = $report_list ? $report_list : array();
        $data['host']       = $host;

        $this->load->view('interaction/live/report', $data);
    }
    
    public function saveinfo() {
        //var_dump($_POST);exit;
        $mid       = $this->input->post('match_id');
        $image    = $this->input->post('poster');
        $content  = $this->input->post('content');
        $timeline = $this->input->post('timeline');
        $sumup    = $this->input->post('sumup');
        $gif      = $this->input->post('poster1');
        $gif_size = $this->input->post('size');
        $host     = $this->input->post('host');
        
        if (!$mid || !$content || !$timeline && !$sumup) {
            redirect(base_url('/interaction/live/report_live/index/'.$mid.'/'.$host));
            return false;
        }
        
        if ($sumup) {
            $timeline = -1;
        }
        
        $timeline = $timeline * 60;
        $data = array(
            'match_id'  => $mid,
            'report_tm' => $timeline,
            'image'     => $image,
            'content'   => $content,
            'gif_size'  => $gif_size
        );
        
        if ($image && $gif) {
            $data['gif'] = $image;
            $data['image'] = $gif;
        }
        
        $id = $this->RM->db('sports')->insert('match_report', $data);
        $result = $this->RM->_sendReport($id, $mid, $host);
        
        redirect(base_url('/interaction/live/report_live/index/'.$mid.'/'.$host));
    }

    public function edit($id, $match_id=0, $host='') {
        if (!$id) {
            redirect(base_url('/interaction/live/report_live/index/'.$match_id.'/'.$host));
            exit();
        }
        
        $report_info = $this->RM->getReportById($id);
        if (!$report_info) {
            redirect(base_url('/interaction/live/report_live/index/'.$match_id.'/'.$host));
            exit();
        }
        
        $report_info['report_tm'] /= 60;
        
        if ($_POST) {
            $timeline = $this->input->post('timeline');
            $sumup    = $this->input->post('sumup');
            $content  = $this->input->post('content');
            $visible  = $this->input->post('visible');
            $image    = $this->input->post('poster');
            $gif      = $this->input->post('poster1');
            $gif_size = $this->input->post('size');
            
            if ($sumup) {
                $timeline = -1;
            }
            
            if (!$content || !$sumup && !$timeline) {
                redirect(base_url('/interaction/live/report_live/index/'.$match_id.'/'.$host));
                exit();
            }
            
            $timeline = $timeline * 60;
            
            $data = array(
                'report_tm' => $timeline,
                'image'     => $image,
                'content'   => $content,
                'visible'   => $visible,
                'gif_size'  => $gif_size
            );
            
            if ($image && $gif) {
                $data['gif'] = $image;
                $data['image'] = $gif;
            } else {
                $data['gif'] = '';
            }
            
            $this->RM->db('sports')->update('match_report', $data, array('id'=>$id));
            $result = $this->RM->_sendReport($id, $match_id, $host);
            
            redirect(base_url('/interaction/live/report_live/index/'.$match_id.'/'.$host));
            exit();
        }
        
        $data = array(
            'id'       => $id,
            'match_id' => $match_id,
            'host'     => $host
        );
        
        $data['report_info'] = $report_info;
        
        
        $this->load->view('interaction/live/report_edit', $data);
    }
    
    public function setimportant() {
        $id = $this->input->post('id');
        if (!$id) {
            echo json_encode(array('status'=>1, 'info'=>'id error'));
            exit();
        }
        
        $report_info = $this->RM->getReportById($id);
        if (!$report_info) {
            echo json_encode(array('status'=>1, 'info'=>'data error'));
            exit();
        }
        
        if ($report_info['visible']) {
            $data = array(
                'visible' => 0
            );
        } else {
            $data = array(
                'visible' => 1
            );
        }
        
        
        $this->RM->db('sports')->update('match_report', $data, array('id'=>$id));
        
        echo json_encode(array('status'=>0));
    }
    
}
