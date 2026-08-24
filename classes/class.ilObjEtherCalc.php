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
    private const MAX_PAGE_ID_ATTEMPTS = 10;

    protected string $page_id = '';

    protected int $online = 0;

    protected int $fullscreen_for_object = 0;

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
        $this->insertData();
        if (!$clone_mode) {
            $this->createMetaData();
        }
    }

    /**
     * @throws ilException if no unique page id could be generated
     */
    protected function insertData(): void
    {
        $this->setPageId($this->createUniquePageId());
        $this->db->insert(
            'rep_robj_xetc_data',
            [
                'id' => ['integer', $this->getId()],
                'is_online' => ['integer', $this->getOnline()],
                'fullscreen' => ['integer', $this->getFullScreenForObject()],
                'page_id' => ['text', $this->getPageId()]
            ]
        );
    }

    /**
     * @throws ilException if no unique page id could be generated
     */
    protected function createUniquePageId(): string
    {
        for ($attempt = 0; $attempt < self::MAX_PAGE_ID_ATTEMPTS; $attempt++) {
            $page_id = bin2hex(random_bytes(60));
            if (!$this->pageIdExists($page_id)) {
                return $page_id;
            }
            $this->log->warning(sprintf(
                'The ethercalc page id (%s) already exists, trying another id',
                $page_id
            ));
        }

        throw new ilException(sprintf(
            'Could not find a unique ethercalc page id for object (%s)',
            $this->getId()
        ));
    }

    protected function pageIdExists(string $page_id): bool
    {
        $set = $this->db->queryF(
            'SELECT id FROM rep_robj_xetc_data WHERE page_id = %s',
            ['text'],
            [$page_id]
        );
        return $this->db->numRows($set) > 0;
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
        $res = $this->db->queryF(
            'SELECT * FROM rep_robj_xetc_data WHERE id = %s',
            ['integer'],
            [$this->getId()]
        );
        $row = $this->db->fetchAssoc($res);

        if ($row === null) {
            $this->log->warning(sprintf(
                'No ethercalc data found for object (%s), creating a new data set',
                $this->getId()
            ));
            $this->insertData();
            return;
        }

        $this->setOnline((bool) $row['is_online']);
        $this->setFullScreenForObject((int) ($row['fullscreen'] ?? 0));
        $this->setPageId((string) ($row['page_id'] ?? ''));

        if ($this->getPageId() === '') {
            $this->log->warning(sprintf(
                'No ethercalc page id found for object (%s), creating a new one',
                $this->getId()
            ));
            $this->repairPageId();
        }
    }

    /**
     * @throws ilException if no unique page id could be generated
     */
    protected function repairPageId(): void
    {
        $this->setPageId($this->createUniquePageId());
        $this->db->update(
            'rep_robj_xetc_data',
            ['page_id' => ['text', $this->getPageId()]],
            ['id' => ['integer', $this->getId()]]
        );
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
        if (!$new_obj instanceof self) {
            return;
        }

       $new_obj->setOnline((bool) $this->getOnline());
        $new_obj->setFullScreenForObject($this->getFullScreenForObject());
        $new_obj->update();

        $this->cloneMetaData($new_obj);
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
