<?php declare(strict_types=1);
/******************************************************************************
 *
 * This file is part of ILIAS, a powerful learning management system.
 *
 * ILIAS is licensed with the GPL-3.0, you should have received a copy
 * of said license along with the source code.
 *
 * If this is not the case or you just want to try ILIAS, you'll find
 * us at:
 *      https://www.ilias.de
 *      https://github.com/ILIAS-eLearning
 *
 *****************************************************************************/


/**
 * @ilCtrl_IsCalledBy ilEtherCalcConfigGUI: ilObjComponentSettingsGUI
 */

class ilEtherCalcConfigGUI extends ilPluginConfigGUI
{
    /**
     * @var ilTemplate
     */
    protected $tpl;

    /**
     * @var ilLanguage
     */
    protected $lng;

    /**
     * @var ilCtrl
     */
    protected $ctrl;

    /**
     * @var ilToolbarGUI
     */
    protected $toolbar;

    /**
     * @var ilDBInterface
     */
    protected $db;

    /**
     *
     */
    public function __construct()
    {

        global $DIC;

        $this->lng = $DIC->language();
        $this->tpl = $DIC->ui()->mainTemplate();
        $this->ctrl = $DIC->ctrl();
    }

    /**
     *
     */
    protected function saveConfigurationForm()
    {
        $form = $this->getConfigurationForm();
        if ($form->checkInput()) {
            try {
                ilEtherCalcConfig::getInstance()->setUrl($form->getInput('url'));
                ilEtherCalcConfig::getInstance()->setFullScreen($form->getInput('fullscreen'));
                ilEtherCalcConfig::getInstance()->save();
                $this->ctrl->redirect($this, 'configure');
            } catch (ilException $e) {
                $this->tpl->setOnScreenMessage("failure", $this->lng->txt("form_input_not_valid"), true);
            }
        }

        $form->setValuesByPost();
        $this->showConfigurationForm($form);
    }

    /**
     * @return ilPropertyFormGUI
     */
    protected function getConfigurationForm()
    {
        $form = new ilPropertyFormGUI();
        $form->setTitle($this->lng->txt('settings'));
        $form->setFormAction($this->ctrl->getFormAction($this, 'showConfigurationForm'));
        $form->setShowTopButtons(true);

        $url = new ilTextInputGUI($this->getPluginObject()->txt('xetc_url'), 'url');
        $url->setInfo($this->getPluginObject()->txt('xetc_url_info'));
        $form->addItem($url);

        $fullscreen = new ilCheckboxInputGUI($this->getPluginObject()->txt('xetc_fullscreen'), 'fullscreen');
        $fullscreen->setInfo($this->getPluginObject()->txt('xetc_fullscreen_info'));
        $form->addItem($fullscreen);

        $form->addCommandButton('saveConfigurationForm', $this->lng->txt('save'));

        return $form;
    }

    /**
     * @param ilPropertyFormGUI|null $form
     * @return void
     */
    protected function showConfigurationForm(ilPropertyFormGUI $form = null)
    {

        if (!$form instanceof ilPropertyFormGUI) {
            $form = $this->getConfigurationForm();
            $form->setValuesByArray(array(
                'url' => ilEtherCalcConfig::getInstance()->getUrl(),
                'fullscreen' => ilEtherCalcConfig::getInstance()->getFullScreen()
            ));
        }
        $this->tpl->setContent($form->getHTML());
    }

    /**
     * @param $cmd
     * @return void
     */
    public function performCommand(string $cmd) : void
    {
        switch ($cmd) {
            case 'saveConfigurationForm':
                $this->saveConfigurationForm();
                break;

            case 'showConfigurationForm':
            default:
                $this->showConfigurationForm();
                break;
        }
    }
}