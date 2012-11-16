<?php

// Check to ensure this file is included in Joomla!
defined('_JEXEC') or die();

jimport('joomla.application.component.view');

class fabrikViewApprovals extends JView
{

	var $_isMambot = false;

	function display($tmpl = 'default')
	{
		require_once(COM_FABRIK_FRONTEND.DS.'helpers'.DS.'html.php');

		$model		= &$this->getModel();
		$usersConfig = &JComponentHelper::getParams('com_fabrik');
		$id = JRequest::getVar('id', $usersConfig->get('visualizationid', JRequest::getInt('visualizationid', 0)));
		$model->setId($id);
		$this->assignRef('row', $this->get('Visualization'));
		$this->assign('html', $this->get('HTML'));

		$this->assign('containerId', $this->get('ContainerId'));

		$this->calName = $this->get('VizName');

		$this->assignRef('params', $this->get('PluginParams'));
		$tmpl = $this->params->get('approvals_layout', $tmpl);
		$tmplpath = COM_FABRIK_FRONTEND.DS.'plugins'.DS.'visualization'.DS.'approvals'.DS.'views'.DS.'approvals'.DS.'tmpl'.DS.$tmpl;
		$this->_setPath('template', $tmplpath);

		$ab_css_file = $tmplpath.DS."template.css";

		if (file_exists($ab_css_file)) {
			JHTML::stylesheet('template.css', 'components/com_fabrik/plugins/visualization/approvals/views/approvals/tmpl/'.$tmpl.'/', true);
		}
		//check and add a general fabrik custom css file overrides template css and generic table css
		FabrikHelperHTML::stylesheetFromPath("media".DS."com_fabrik".DS."css".DS."custom.css");
		//check and add a specific biz  template css file overrides template css generic table css and generic custom css
		FabrikHelperHTML::stylesheetFromPath("components".DS."com_fabrik".DS."plugins".DS."visualization".DS."approvals".DS."views".DS."approvals".DS."tmpl".DS.$tmpl.DS."custom.css");

		$text = $this->loadTemplate();
		$opt = JRequest::getVar('option');
		$view = JRequest::getCmd('view');
		JRequest::setVar('view', 'article');
		JRequest::setVar('option', 'com_content');
		jimport('joomla.html.html.content');
		$text .= '{emailcloak=off}';
		$text = JHTML::_('content.prepare', $text);
		$text = preg_replace('/\{emailcloak\=off\}/', '', $text);
		JRequest::setVar('option', $opt);
		JRequest::setVar('view', $view);
		if ($this->_isMambot) {
			return $text;
		} else {
			echo $text;
		}
	}


}
?>
