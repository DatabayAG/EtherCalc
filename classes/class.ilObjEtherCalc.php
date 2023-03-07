<?php declare(strict_types=1);


/**
 * Class ilObjEtherCalc
 */
class ilObjEtherCalc extends ilObjectPlugin
{
    /**
     * @var string
     */
    protected $page_id;

    /**
     * @var int
     */
    protected $round = 0;

    /**
     * @var int
     */
    protected $online = 0;

    /**
     * @var int
     */
    protected $fullscreen_for_object;

    /**
     * @var
     */
    protected ilDBInterface $db;

    /**
     * @var ilLog
     */
    protected ?ilLogger $log;

    /**
     * ilObjEtherCalc constructor.
     * @param int $a_ref_id
     */
    function __construct($a_ref_id = 0)
    {
        parent::__construct($a_ref_id);
        global $ilDB, $ilLog;
        $this->db = $ilDB;
        $this->log = $ilLog;
    }

    /**
     * Get type.
     */
    final function initType() : void
    {
        $this->setType('xetc');
    }

    /**
     * Create object
     */
    protected function doCreate(bool $clone_mode = false): void
    {
        $rand = $this->createRandomId();
        if ($rand == false) {
            $this->log->write(sprintf('Could not find a unique id for object (%s) object will be broken!',
                $this->getId()));
        } else {
            $this->db->insert(
                'rep_robj_xetc_data',
                array(
                    'id' => array('integer', $this->getId()),
                    'is_online' => array('integer', $this->getOnline()),
                    'page_id' => array('text', $rand)
                )
            );
            $this->createMetaData();
        }
    }

    /**
     * @return bool|string
     */
    protected function createRandomId()
    {
        $this->round++;
        if (function_exists('openssl_random_pseudo_bytes')) {
            $random_id = bin2hex(openssl_random_pseudo_bytes(60));
        } else {
            $random_id = uniqid('', true);
        }
        return $this->checkIfRandomIdIsUnique($random_id);
    }

    /**
     * @param $page_id
     * @return bool| string
     */
    protected function checkIfRandomIdIsUnique($page_id)
    {
        $id = null;
        $page_id = ilUtil::stripSlashes($page_id);

        $set = $this->db->query('SELECT id FROM rep_robj_xetc_data WHERE page_id = ' . $this->db->quote($page_id,
                'text'));
        while ($rec = $this->db->fetchAssoc($set)) {
            $id = $rec['id'];
        }

        if ($id == null) {
            return $page_id;
        } else {
            $this->log->write(sprintf('The ethercalc page id (%s) for object with id (%s) already exists, trying another id',
                $page_id, $id));
            if ($this->round < 10) {
                $this->createRandomId();
            }
        }
        return false;
    }

    /**
     * @return int
     */
    function getOnline()
    {
        return $this->online;
    }

    /**
     * Set online
     * @param boolean        online
     */
    function setOnline($a_val)
    {
        $this->online = $a_val;
    }

    /**
     * Read data from db
     */
    protected function doRead(): void
    {
        $res = $this->db->query('SELECT * FROM rep_robj_xetc_data WHERE id = ' . $this->db->quote($this->getId(),
                'integer'));
        while ($row = $this->db->fetchAssoc($res)) {
            $this->setOnline((bool) $row['is_online']);
            $this->setPageId($row['page_id']);
            $this->setFullScreenForObject($row['fullscreen']);
            break;
        }
    }

    /**
     * Update data
     */
    protected function doUpdate(): void
    {
        $this->db->update(
            'rep_robj_xetc_data',
            array(
                'is_online' => array('integer', $this->getOnline()),
                'fullscreen' => array('integer', $this->getFullScreenForObject())
            ),
            array(
                'id' => array('integer', $this->getId())
            )
        );
    }

    /**
     * @return int
     */
    public function getFullScreenForObject()
    {
        return $this->fullscreen_for_object;
    }

//
// Set/Get Methods for our example properties
//

    /**
     * @param int $fullscreen_for_object
     */
    public function setFullScreenForObject($fullscreen_for_object)
    {
        $this->fullscreen_for_object = $fullscreen_for_object;
    }

    /**
     *
     */
    protected function beforeDelete(): bool
    {
        $this->db->manipulate('DELETE FROM rep_robj_xetc_data WHERE id = ' . $this->db->quote($this->getId(),
                'integer'));
        return true;
    }

    /**
     * Delete data from db
     */
    protected function doDelete(): void
    {

        parent::doDelete();
        $this->deleteMetaData();
    }

    /**
     * @param $a_target_id
     * @param $a_copy_id
     * @param $new_obj
     */
    function doClone($a_target_id, $a_copy_id, $new_obj)
    {
        //TODO: implment
    }

    /**
     * @return string
     */
    public function getPageId()
    {
        return $this->page_id;
    }

    /**
     * @param mixed $page_id
     */
    public function setPageId($page_id)
    {
        $this->page_id = $page_id;
    }

}
