<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Gallery extends MY_Controller {

    public function __construct() {
        parent::__construct();
        $this->load->model('Gallery_model', 'GM');
    }

    public function detail($id, $page_type = 'share') {

        $gallery = $this->GM->getGalleryById($id);

        if (!$gallery) {
            $this->load->view('errors/not-found', array('page_type' => $page_type));
            return;
        }

        $data['gallery'] = $gallery;

        $data['gallery_content'] = $this->GM->getGalleryContent($where = array('gallery_id' => $id), $limit = 0, $offset = 0);

        $data['related'] = $this->GM->getGalleryRelated($id);

        $data['page_type'] = $page_type;
        $data['header_footer_data'] = getHeaderFooterData('gallery', '【图集】' . $gallery['title'] . '_暴风体育', $page_type);
        $data['download_data'] = array(
                'info' => getShareOrAppJson('gallery', $gallery, $page_type)
        );

        $this->load->view('gallery/detail', $data);
    }
}
