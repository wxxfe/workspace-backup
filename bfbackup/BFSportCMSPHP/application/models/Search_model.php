<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Search_model extends MY_Model {

    const XAPIAN_SLOT_ID = 0;

    private $_lockFileHandle = null;

    public function __construct() {
        parent::__construct();
        $this->load->library('Xapianlib');
    }

    private function _dbPath($db) {
        return rtrim(XAPIAN_DB_PATH, '/') . '/' . $db;
    }

    private function _dbPathNew($db) {
        return $this->_dbPath($db) . '.new';
    }

    private function _getLockFile($db) {
        return $this->_dbPath($db) . '.lock';
    }

    public function lockIndexer($db) {
        $lock_file = $this->_getLockFile($db);
        if (!file_exists($lock_file)) {
            touch($lock_file);
        }

        $this->_lockFileHandle = @fopen($lock_file, 'w');
        if ($this->_lockFileHandle) {
            if (flock($this->_lockFileHandle, LOCK_EX | LOCK_NB)) {
                return true;
            } else {
                fclose($this->_lockFileHandle);
                $this->_lockFileHandle = null;
            }
        }

        return false;
    }

    public function unlockIndexer($db) {
        if ($this->_lockFileHandle && flock($this->_lockFileHandle, LOCK_UN)) {
            fclose($this->_lockFileHandle);
            $this->_lockFileHandle = null;
            return true;
        }
        return false;
    }

    public function tagQuery($keyword = '', $type = '') {
        $total = 0;
        $result = array();

        if ($keyword) {
            $keyword = trim(rawurldecode($keyword));
        } else {
            $keyword = @$_GET['keyword'];
        }

        if (!$type) {
            $type = @strval($_GET['type']);
        }

        if ($keyword) {
            try {
                $this->load->model('Tag_model', 'TM');
                $xdict = xapian_Prefix_Dictionary::get_instance()->add_boolean_prefix('type', 'Xtype_');
                $xquery = xapian_Query::make($this->_dbPath('tag'), $xdict);
                $matches = $xquery->setFlags(XapianQueryParser::FLAG_WILDCARD)->execute("{$keyword}*" . ($type ? " AND type:{$type}" : ""));
                $total = $matches->get_num_matches();
                while ($total > 0 && !is_null($match = $matches->get_next())) {
                    $data = json_decode($match->get_document()->get_data(), true);
                    $result[] = $data;
                }
            } catch (Exception $e) {
                error_log($e->getMessage());
            }
        }

        return array('total' => $total, 'result' => $result);
    }

    public function itemsQuery($type, $keyword = '', $offset = 0, $limit = 10) {
        $total = 0;
        $result = array();

        if ($keyword) {
            $keyword = trim(rawurldecode($keyword));
        } else {
            $keyword = @$_GET['keyword'];
            $offset = @$_GET['offset'] ?: 0;
            $limit = @$_GET['limit'] ?: 10;
        }

        $offset = intval($offset);
        $limit = intval($limit);
        if ($keyword && $limit) {
            try {
                $this->load->model('Tag_model', 'TM');
                $this->load->model('Team_model', 'TeamM');
                $xdict = xapian_Prefix_Dictionary::get_instance();
                $xquery = xapian_Query::make($this->_dbPath($type), $xdict);
                $matches = $xquery->setSorter('value', self::XAPIAN_SLOT_ID, true)
                    ->execute('"' . utf8_str_extend_space($keyword) . '"', $limit, $offset);
                $total = $matches->get_num_matches();
                while ($offset < $total && !is_null($match = $matches->get_next())) {
                    $data = json_decode($match->get_document()->get_data(), true);
                    if ($type == 'match') {
                        $data['teams'] = $this->TeamM->getMatchTeams($data['id']);
                    }
                    $result[] = $data;
                }
            } catch (Exception $e) {
                error_log($e->getMessage());
            }
        }

        return array('total' => $total, 'result' => $result);
    }

    public function tagIndexer($action = '') {
        if ($action != 'incr') {
            $xindexer = xapian_Record_Indexer::make($this->_dbPathNew('tag'))->set_read_write();
        } else {
            $xindexer = xapian_Record_Indexer::make($this->_dbPath('tag'))->set_read_write();
        }

        $this->load->model('Tag_model', 'TM');
        $tables = $this->TM->getTables();
        foreach ($tables as $type => $table) {
            $max_id = 0;
            if ($action == 'incr') {
                try {
                    $xdict = xapian_Prefix_Dictionary::get_instance()
                        ->add_boolean_prefix('type', 'Xtype_');
                    $xquery = xapian_Query::make($this->_dbPath('tag'), $xdict);
                    $matches = $xquery->setSorter('value', self::XAPIAN_SLOT_ID, true)->execute("type:{$type}", 1);
                    if ($match = $matches->get_next()) {
                        $row = json_decode($match->get_document()->get_data(), true);
                        $max_id = $row['id'];
                    }

                    $total = $matches->get_num_matches();
                    echo "incr: {$type}, {$total}, {$max_id}\n";
                } catch (Exception $e) {
                    echo 'Ignore incr: ' . $e->getMessage(), "\n";
                }
            }

            $last_id = 0;
            $limit = 1000;
            while (true) {
                $select = 'id, name';
                if ($type == 'event') {
                    $select .= ', visible';
                }
                if ($type == 'player') {
                    $select .= ', photo, nationality';
                }
                $rows = $this->TM->db('sports')->select($select)->order_by('id')
                    ->get_where($table, "id > {$max_id} AND id > {$last_id}", $limit)
                    ->result_array();
                foreach ($rows as $row) {
                    $last_id = $row['id'];
                    $row['id'] = intval($row['id']);
                    $row['type'] = $type;
                    $row['fake_id'] = $this->TM->makeFakeId($table, $row['id']);
                    $xindexer->set_id($row['fake_id'])
                        ->add_boolean_term("Xtype_{$type}")
                        ->add_to_slot(self::XAPIAN_SLOT_ID, $row['id'])
                        ->add_text($row['name'])
                        ->set_data(json_encode($row))
                        ->execute();

                    // 从索引中删掉已经下线的赛事
                    if ($type == 'event' && !$row['visible']) {
                        $xindexer->delete($row['fake_id']);
                    }
                }

                if (count($rows) < $limit) {
                    break;
                }
            }
        }

        if ($action != 'incr') {
            $cmd = 'rsync -avz --delete-after ' . $this->_dbPathNew('tag') . '/ ' . $this->_dbPath('tag') . '/';
            echo "{$cmd}\n";
            exec($cmd);
        }

        echo date('Y-m-d H:i:s'), ", tag, SUCC\n";
        return true;
    }

    public function itemsIndexer($action = '') {
        $this->load->model('Tag_model', 'TM');
        $this->config->load('sports');
        $items = $this->config->item('items');
        foreach ($items as $table) {
            $type = $table;

            if ($action != 'incr') {
                $xindexer = xapian_Record_Indexer::make($this->_dbPathNew($type))->set_read_write();
            } else {
                $xindexer = xapian_Record_Indexer::make($this->_dbPath($type))->set_read_write();
            }

            $max_id = 0;
            if ($action == 'incr') {
                try {
                    $xdict = xapian_Prefix_Dictionary::get_instance()
                        ->add_boolean_prefix('visible', 'Xvisible_');
                    $xquery = xapian_Query::make($this->_dbPath($type), $xdict);
                    $matches = $xquery->setSorter('value', self::XAPIAN_SLOT_ID, true)->execute('visible:1', 1);
                    if ($match = $matches->get_next()) {
                        $row = json_decode($match->get_document()->get_data(), true);
                        $max_id = $row['id'];
                    }

                    $total = $matches->get_num_matches();
                    echo "incr: {$type}, {$total}, {$max_id}\n";
                } catch (Exception $e) {
                    echo 'Ignore incr: ' . $e->getMessage(), "\n";
                }
            }

            $last_id = 0;
            $limit = 1000;
            while (true) {
                $rows = $this->TM->db($type == 'thread'? 'board' : 'sports')->select('*')->order_by('id')
                    ->get_where($table, "id > {$max_id} AND id > {$last_id}", $limit)
                    ->result_array();
                foreach ($rows as $row) {
                    $last_id = $row['id'];
                    $row['id'] = intval($row['id']);
                    if ($table == 'news') {
                        unset($row['content']);
                    }
                    $xindexer->set_id($row['id'])
                        ->add_boolean_term("Xvisible_{$row['visible']}")
                        ->add_to_slot(self::XAPIAN_SLOT_ID, $row['id'])
                        ->add_text(utf8_str_extend_space($table == 'match' ? $row['brief'] : $row['title']))
                        ->set_data(json_encode($row))
                        ->execute();

                    // 从索引中删掉已经下线的
                    if (!$row['visible']) {
                        $xindexer->delete($row['id']);
                    }
                }

                if (count($rows) < $limit) {
                    break;
                }
            }

            if ($action != 'incr') {
                $cmd = 'rsync -avz --delete-after ' . $this->_dbPathNew($type) . '/ ' . $this->_dbPath($type) . '/';
                echo "{$cmd}\n";
                exec($cmd);
            }

            echo date('Y-m-d H:i:s'), ", {$type}, SUCC\n";
        }

        return true;
    }

    public function userQuery($type, $keyword = '') {
        $result = array();
        if ($keyword) {
            $keyword = trim(rawurldecode($keyword));
        } else {
            $keyword = @$_GET['keyword'];
        }
        if ($keyword) {
            try {
                if ($type == 'name') {
                    $result = $this->getUsersIDAPI($keyword);
                } else {
                    $result = $this->getUsersAPI($keyword, 2);
                }
            } catch (Exception $e) {
                error_log($e->getMessage());
            }
        }
        return $result;
    }

}
