<?php

/**
 * This file is part of ILIAS, a powerful learning management system
 * published by ILIAS open source e-Learning e.V.
 *
 * ILIAS is licensed with the GPL-3.0,
 * see https://www.gnu.org/licenses/gpl-3.0.en.html
 * You should have received a copy of said license along with the
 * source code, too.
 *
 * If this is not the case or you just want to try ILIAS, you'll find
 * us at:
 * https://www.ilias.de
 * https://github.com/ILIAS-eLearning
 *
 *********************************************************************/

declare(strict_types=1);

class ilObjEtherCalc extends ilObjectPlugin
{
    protected string $page_id;

    protected int $round = 0;

    protected int $online = 0;

    protected int $fullscreen_for_object;

    protected ilDBInterface $db;

    protected ?ilLogger $log;

    public function __construct(int $a_ref_id = 0)
    {
        parent::__construct($a_ref_id);
        global $DIC;
        $this->db = $DIC->database();
        $this->log = $DIC->logger()->root();
    }

    final public function initType(): void
    {
        $this->setType('xetc');
    }

    protected function doCreate(bool $clone_mode = false): void
    {
        $rand = $this->createRandomId();
        if ($rand == false) {
            $this->log->write(sprintf(
                'Could not find a unique id for object (%s) object will be broken!',
                $this->getId()
            ));
        } else {
            $this->db->insert(
                'rep_robj_xetc_data',
                [
                    'id' => ['integer', $this->getId()],
                    'is_online' => ['integer', $this->getOnline()],
                    'page_id' => ['text', $rand]
                ]
            );
            $this->createMetaData();
        }
    }

    protected function createRandomId(): bool|string
    {
        $this->round++;
        if (function_exists('openssl_random_pseudo_bytes')) {
            $random_id = bin2hex(openssl_random_pseudo_bytes(60));
        } else {
            $random_id = uniqid('', true);
        }
        return $this->checkIfRandomIdIsUnique($random_id);
    }

    protected function checkIfRandomIdIsUnique(string $page_id): bool|string
    {
        $id = null;
        $page_id = ilUtil::stripSlashes($page_id);

        $set = $this->db->query('SELECT id FROM rep_robj_xetc_data WHERE page_id = ' . $this->db->quote(
            $page_id,
            'text'
        ));
        while ($rec = $this->db->fetchAssoc($set)) {
            $id = $rec['id'];
        }

        if ($id == null) {
            return $page_id;
        } else {
            $this->log->write(sprintf(
                'The ethercalc page id (%s) for object with id (%s) already exists, trying another id',
                $page_id,
                $id
            ));
            if ($this->round < 10) {
                $this->createRandomId();
            }
        }
        return false;
    }

    public function getOnline(): int
    {
        return $this->online;
    }

    public function setOnline(bool $a_val): void
    {
        $this->online = (int) $a_val;
    }


    protected function doRead(): void
    {
        $res = $this->db->query('SELECT * FROM rep_robj_xetc_data WHERE id = ' . $this->db->quote(
            $this->getId(),
            'integer'
        ));
        while ($row = $this->db->fetchAssoc($res)) {
            $this->setOnline((bool) $row['is_online']);
            $this->setPageId($row['page_id']);
            $this->setFullScreenForObject((int) $row['fullscreen']);
            break;
        }
    }

    protected function doUpdate(): void
    {
        $this->db->update(
            'rep_robj_xetc_data',
            [
                'is_online' => ['integer', $this->getOnline()],
                'fullscreen' => ['integer', $this->getFullScreenForObject()]
            ],
            [
                'id' => ['integer', $this->getId()]
            ]
        );
    }

    public function getFullScreenForObject(): int
    {
        return $this->fullscreen_for_object;
    }

    public function setFullScreenForObject(int $fullscreen_for_object): void
    {
        $this->fullscreen_for_object = $fullscreen_for_object;
    }

    protected function beforeDelete(): bool
    {
        $this->db->manipulate('DELETE FROM rep_robj_xetc_data WHERE id = ' . $this->db->quote(
            $this->getId(),
            'integer'
        ));
        return true;
    }

    protected function doDelete(): void
    {

        parent::doDelete();
        $this->deleteMetaData();
    }

    protected function doCloneObject(ilObject2 $new_obj, int $a_target_id, ?int $a_copy_id = null): void
    {
    }

    public function getPageId(): string
    {
        return $this->page_id;
    }

    public function setPageId(string $page_id): void
    {
        $this->page_id = $page_id;
    }

}
