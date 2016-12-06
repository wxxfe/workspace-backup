<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class News extends MY_Controller {

    public function __construct() {

        parent::__construct();

        $this->load->model('News_model', 'NM');

    }

    public function index() {
        $this->load->view('welcome_message');
    }

    public function detail($id, $page_type = 'app') {

        $news = $this->NM->getNewsById($id);

        if (!$news) {
            $this->load->view('errors/not-found', array('page_type' => $page_type));
            return;
        }

        $data['news'] = $news;

        $data['page_type'] = $page_type;
        $data['download_data'] = array(
                'info' => getShareOrAppJson('news', $news, $page_type)
        );

        if ($data['news'] && isset($data['news']['column']) && $data['news']['column']) {

            $data['header_footer_data'] = getHeaderFooterData('special-column', '【' . (isset($news['column']['title']) ? $news['column']['title'] : "") . '专栏】' . $news['title'] . '_暴风体育', $page_type);

            $this->load->view('column/detail', $data);
        } else {

            $data['header_footer_data'] = getHeaderFooterData('news', $news['title'] . '_暴风体育', $page_type);

            $data['related'] = $this->NM->getNewsRelated($id);
            $this->load->view('news/detail', $data);
        }
    }
}
