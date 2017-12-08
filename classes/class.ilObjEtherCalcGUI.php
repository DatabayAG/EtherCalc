<?php

require_once './Services/Repository/classes/class.ilObjectPluginGUI.php';
require_once './Services/Form/classes/class.ilPropertyFormGUI.php';
require_once './Customizing/global/plugins/Services/Repository/RepositoryObject/EtherCalc/classes/ilEtherCalcConfig.php';

/**
* @ilCtrl_isCalledBy ilObjEtherCalcGUI: ilRepositoryGUI, ilAdministrationGUI, ilObjPluginDispatchGUI
* @ilCtrl_Calls ilObjEtherCalcGUI: ilPermissionGUI, ilInfoScreenGUI, ilObjectCopyGUI, ilCommonActionDispatcherGUI
*
*/
class ilObjEtherCalcGUI extends ilObjectPluginGUI
{
	/**
	 * @var ilEtherCalcConfig $config
	 */
	protected $config;

	/**
	 * @var ilPropertyFormGUI
	 */
	protected $form;

	/**
	 * @var ilTabsGUI
	 */
	protected $tabs;

	/**
	 * @var ilCtrl
	 */
	protected $ctrl;

	/**
	 * @var ilAccessHandler
	 */
	protected $access;

	/**
	* Initialisation
	*/
	protected function afterConstructor()
	{
		$this->config = ilEtherCalcConfig::getInstance();

		/**
		 * @var ilTabsGUI		$ilTabs
		 * @var ilAccessHandler	$ilAccess
		 * @var ilCtrl			$ilCtrl
		 */
		global $ilTabs, $ilCtrl, $ilAccess;

		$this->tabs = $ilTabs;
		$this->access = $ilAccess;
		$this->ctrl = $ilCtrl;
	}
	
	/**
	* Get type.
	*/
	final function getType()
	{
		return 'xetc';
	}

	/**
	 * @param $cmd
	 */
	function performCommand($cmd)
	{
		switch ($cmd)
		{
			case 'editProperties':		// list all commands that need write permission here
			case 'updateProperties':
			//case '...':
				$this->checkPermission('write');
				$this->$cmd();
				break;
			
			case 'showContent':			// list all commands that need read permission here
			//case '...':
			//case '...':
				$this->checkPermission('read');
				$this->$cmd();
				break;
		}
	}

	/**
	* After object has been created -> jump to this command
	*/
	function getAfterCreationCmd()
	{
		return 'editProperties';
	}

	/**
	* Get standard command
	*/
	function getStandardCmd()
	{
		return 'showContent';
	}
	
//
// DISPLAY TABS
//
	
	/**
	* Set tabs
	*/
	function setTabs()
	{
		
		if ($this->access->checkAccess('read', '', $this->object->getRefId()))
		{
			$this->tabs->addTab('content', $this->txt('content'), $this->ctrl->getLinkTarget($this, 'showContent'));
		}

		$this->addInfoTab();

		if ($this->access->checkAccess('write', '', $this->object->getRefId()))
		{
			$this->tabs->addTab('properties', $this->txt('properties'), $this->ctrl->getLinkTarget($this, 'editProperties'));
		}

		$this->addPermissionTab();
	}

	/**
	* Edit Properties. This commands uses the form class to display an input form.
	*/
	function editProperties()
	{
		global $tpl;

		$this->tabs->activateTab('properties');
		$this->initPropertiesForm();
		$this->getPropertiesValues();
		$tpl->setContent($this->form->getHTML());
	}

	/**
	 * 
	 */
	public function initPropertiesForm()
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

		if($this->config->getFullScreen())
		{
			$full_screen = new ilNonEditableValueGUI($this->txt('xetc_fullscreen'), '');
			$full_screen->setValue($this->txt('xetc_fullscreen_global_enabled'));
		}
		else
		{
			$full_screen = new ilCheckboxInputGUI($this->txt('xetc_fullscreen'), 'fullscreen');
		}
		$this->form->addItem($full_screen);

		$this->form->addCommandButton('updateProperties', $this->txt('save'));
	                
		$this->form->setTitle($this->txt('edit_properties'));
		$this->form->setFormAction($this->ctrl->getFormAction($this));
	}
	
	/**
	* Get values for edit properties form
	*/
	function getPropertiesValues()
	{
		$values['title'] = $this->object->getTitle();
		$values['desc'] = $this->object->getDescription();
		$values['page_id'] = $this->object->getPageId();
		$values['online'] = $this->object->getOnline();
		$values['fullscreen'] = $this->object->getFullScreenForObject();
		$this->form->setValuesByArray($values);
	}
	
	/**
	* Update properties
	*/
	public function updateProperties()
	{
		$this->initPropertiesForm();
		if ($this->form->checkInput())
		{
			$this->object->setTitle($this->form->getInput('title'));
			$this->object->setDescription($this->form->getInput('desc'));
			$this->object->setOnline($this->form->getInput('online'));
			$this->object->setFullScreenForObject($this->form->getInput('fullscreen'));
			$this->object->update();
			ilUtil::sendSuccess($this->lng->txt('msg_obj_modified'), true);
			$this->ctrl->redirect($this, 'editProperties');
		}

		$this->form->setValuesByPost();
		$this->tpl->setContent($this->form->getHtml());
	}

	/**
	* Show content
	*/
	function showContent()
	{
		$this->tpl->addJavaScript('Customizing/global/plugins/Services/Repository/RepositoryObject/EtherCalc/templates/ethercalc.js');
		$this->tpl->addCSS('Customizing/global/plugins/Services/Repository/RepositoryObject/EtherCalc/templates/ethercalc.css');

		$my_tpl = new ilTemplate('Customizing/global/plugins/Services/Repository/RepositoryObject/EtherCalc/templates/tpl.main.html', false, false);

		$my_tpl->setVariable('URL', $this->config->getUrl());
		$my_tpl->setVariable('PAGE_ID', $this->object->getPageId());

		if($this->config->getFullScreen() || $this->object->getFullScreenForObject())
		{
			$my_tpl->setVariable('ETHERCALC_ID', 'ilEtherCalcPluginFullScreen');
		}
		else
		{
			$my_tpl->setVariable('ETHERCALC_ID', 'ilEtherCalcPlugin');
			$this->tabs->activateTab('content');
		}

		$this->tpl->setContent($my_tpl->get());
	}
	

}
