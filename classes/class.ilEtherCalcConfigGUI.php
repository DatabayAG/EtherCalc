<?php declare(strict_types=1);

require_once './Services/Component/classes/class.ilPluginConfigGUI.php';
require_once './Customizing/global/plugins/Services/Repository/RepositoryObject/EtherCalc/classes/class.ilEtherCalcConfig.php';

/**
 * Class ilEtherCalcConfigGUI
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
        /**
         * @var ilTemplate $tpl
         * @var ilLanguage $lng
         * @var ilCtrl     $ilCtrl
         */
        global $lng, $tpl, $ilCtrl;

        $this->lng = $lng;
        $this->tpl = $tpl;
        $this->ctrl = $ilCtrl;
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
                ilUtil::sendFailure($this->lng->txt('form_input_not_valid'));
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
        require_once 'Services/Form/classes/class.ilPropertyFormGUI.php';

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
    public function performCommand($cmd)
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