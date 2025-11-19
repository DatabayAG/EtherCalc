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

/**
 * @ilCtrl_isCalledBy ilObjEtherCalcGUI: ilRepositoryGUI, ilAdministrationGUI, ilObjPluginDispatchGUI, ilLMEditorGUI
 * @ilCtrl_Calls      ilObjEtherCalcGUI: ilPermissionGUI, ilInfoScreenGUI, ilObjectCopyGUI, ilCommonActionDispatcherGUI, ilLearningProgressGUI
 */
class ilObjEtherCalcGUI extends ilObjectPluginGUI
{
    /**
     * @var ilObjEtherCalc|ilObject|null
     */
    protected ?ilObject $object = null;
    protected ilEtherCalcConfig $config;
    protected ilPropertyFormGUI $form;
    protected ilTabsGUI $tabs;
    protected ilCtrl $ctrl;
    protected ilAccessHandler $access;
    private ilGlobalTemplateInterface $mainTpl;

    protected function afterConstructor(): void
    {
        global $DIC;
        $this->tabs = $DIC->tabs();
        $this->access = $DIC->access();
        $this->ctrl = $DIC->ctrl();

        $this->config = ilEtherCalcConfig::getInstance();
    }

    final public function getType(): string
    {
        return 'xetc';
    }

    /**
     * @throws ilObjectException
     */
    public function performCommand(string $cmd): void
    {
        global $DIC;
        $DIC->globalScreen()->tool()->context()->claim()->repository();
        switch ($cmd) {
            case 'create':
            case 'editProperties':
            case 'updateProperties':
                $this->checkPermission('write');
                $this->$cmd();
                break;

            case 'showContent':
                $this->checkPermission('read');
                $this->$cmd();
                break;
        }
    }

    public function getAfterCreationCmd(): string
    {
        return 'editProperties';
    }

    public function getStandardCmd(): string
    {
        return 'showContent';
    }

    protected function setTabs(): void
    {

        if ($this->access->checkAccess('read', '', $this->object->getRefId())) {
            $this->tabs->addTab('content', $this->txt('content'), $this->ctrl->getLinkTarget($this, 'showContent'));
        }

        $this->addInfoTab();

        if ($this->access->checkAccess('write', '', $this->object->getRefId())) {
            $this->tabs->addTab(
                'properties',
                $this->txt('properties'),
                $this->ctrl->getLinkTarget($this, 'editProperties')
            );
        }

        $this->addPermissionTab();
    }

    public function editProperties(): void
    {
        $this->tabs->activateTab('properties');
        $this->initPropertiesForm();
        $this->getPropertiesValues();
        $this->tpl->setContent($this->form->getHTML());
    }

    public function initPropertiesForm(): void
    {
        $this->form = new ilPropertyFormGUI();

        $ti = new ilTextInputGUI($this->txt('title'), 'title');
        $ti->setRequired(true);
        $this->form->addItem($ti);

        $ta = new ilTextAreaInputGUI($this->txt('description'), 'desc');
        $this->form->addItem($ta);

        $page_id = new ilNonEditableValueGUI($this->txt('page_id'), 'page_id');
        $this->form->addItem($page_id);

        $cb = new ilCheckboxInputGUI($this->lng->txt('online'), 'online');
        $this->form->addItem($cb);

        if ($this->config->getFullScreen()) {
            $full_screen = new ilNonEditableValueGUI($this->txt('xetc_fullscreen'), '');
            $full_screen->setValue($this->txt('xetc_fullscreen_global_enabled'));
        } else {
            $full_screen = new ilCheckboxInputGUI($this->txt('xetc_fullscreen'), 'fullscreen');
        }
        $this->form->addItem($full_screen);

        $this->form->addCommandButton('updateProperties', $this->txt('save'));

        $this->form->setTitle($this->txt('edit_properties'));
        $this->form->setFormAction($this->ctrl->getFormAction($this));
    }

    public function getPropertiesValues(): void
    {
        $values['title'] = $this->object->getTitle();
        $values['desc'] = $this->object->getDescription();
        $values['page_id'] = $this->object->getPageId();
        $values['online'] = $this->object->getOnline();
        $values['fullscreen'] = $this->object->getFullScreenForObject();
        $this->form->setValuesByArray($values);
    }

    public function updateProperties(): void
    {
        $this->initPropertiesForm();
        if ($this->form->checkInput()) {
            $this->object->setTitle($this->form->getInput('title'));
            $this->object->setDescription($this->form->getInput('desc'));
            $this->object->setOnline((bool) $this->form->getInput('online'));
            $this->object->setFullScreenForObject((int) $this->form->getInput('fullscreen'));
            $this->object->update();
            $this->tpl->setOnScreenMessage("success", $this->lng->txt("msg_obj_modified"), true);
            $this->ctrl->redirect($this, 'editProperties');
        }

        $this->form->setValuesByPost();
        $this->tpl->setContent($this->form->getHtml());
    }

    public function showContent(): void
    {
        $my_tpl = new ilTemplate("tpl.exp.html", true, true, $this->plugin->getDirectory());

        $my_tpl->setVariable('URL', $this->config->getUrl());
        $my_tpl->setVariable('PAGE_ID', $this->object->getPageId());

        if ($this->config->getFullScreen() || $this->object->getFullScreenForObject()) {
            $my_tpl->setVariable('ETHERCALC_ID', 'ilEtherCalcPluginFullScreen');
        } else {
            $my_tpl->setVariable('ETHERCALC_ID', 'ilEtherCalcPlugin');
        }

        $this->tabs->activateTab('content');
        $this->tpl->setContent($my_tpl->get());
    }

}
