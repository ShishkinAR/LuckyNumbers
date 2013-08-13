<?php
/**
 * Module view & layout config
 * Инициализирует свойства объектов view и layout из конфига для
 * выбранного модуля.
 *
 * @category   RV
 * @package    RV_Controller
 * @subpackage Plugins
 * @copyright  Copyright (c) 2010 Roman V. Konovaltsev
 * @author     Roman V. Konovaltsev
 * @version    1.0
 */

/**
 * @see Zend_Controller_Plugin_Abstract
 */
require_once 'Zend/Controller/Plugin/Abstract.php';

class RV_Controller_Plugin_ModuleConfigLV extends Zend_Controller_Plugin_Abstract {
    public function dispatchLoopStartup(Zend_Controller_Request_Abstract $request) {

        $layout = Zend_Layout::getMvcInstance ();
        $front = Zend_Controller_Front::getInstance ();
        $bootstrap = $front->getParam('bootstrap');
        $options = $bootstrap->getOptions();
        $view = $bootstrap->getResource('view');
        $module = $this->getRequest()->getModuleName();

        // Set defaults
        $pragmaNoCache = (isset($options['resources']['view']['pragmaNoCache']) && strtolower($options['resources']['view']['pragmaNoCache']) == 'on')? true : false;
        $dLayout = isset($options['resources']['layout']['layout'])? $options['resources']['layout']['layout'] : $layout->getLayout();
        $dLayoutPath = isset($options['resources']['layout']['layoutPath'])? $options['resources']['layout']['layoutPath'] : $layout->getLayoutPath();
        $dViewDoctype = isset($options['resources']['view']['doctype'])? $options['resources']['view']['doctype'] : $view->doctype()->getDocType();
        $dViewEncoding = isset($options['resources']['view']['encoding'])? $options['resources']['view']['encoding'] : $view->getEncoding();
        $dViewContentType = isset($options['resources']['view']['contentType'])? $options['resources']['view']['contentType'] : NULL;  
        $dViewHeadTitle = isset($options['resources']['view']['title'])? $options['resources']['view']['title'] : NULL;  
        $dViewHeadTitleSeparator = isset($options['resources']['view']['titleSeparator'])? $options['resources']['view']['titleSeparator'] : NULL;  

        if(isset($options[$module]['resources']['layout']['layout']))
            $layout->setLayout($options[$module]['resources']['layout']['layout']);
        else
            $layout->setLayout($dLayout);

        if(isset($options[$module]['resources']['layout']['layoutPath']))
            $layout->setLayoutPath($front->getModuleDirectory().$options[$module]['resources']['layout']['layoutPath']);
        else
            $layout->setLayoutPath($dLayoutPath);

        if(isset($options[$module]['resources']['view']['doctype']))
            $view->doctype($options[$module]['resources']['view']['doctype']);
        else
            $view->doctype($dViewDoctype);

        if(isset($options[$module]['resources']['view']['encoding']))
            $view->setEncoding($options[$module]['resources']['view']['encoding']);
        else
            $view->setEncoding($dViewEncoding);

        // Set Content Type
        if (isset($options[$module]['resources']['view']['contentType']))
            $view->headMeta()->appendHttpEquiv('Content-Type', $options[$module]['resources']['view']['contentType']);
        elseif(!is_null($dViewContentType))
            $view->headMeta()->appendHttpEquiv('Content-Type', $dViewContentType);

        // Set Head Title
        if (isset($options[$module]['resources']['view']['title']))
            $view->headTitle($options[$module]['resources']['view']['title']);
        elseif(!is_null($dViewHeadTitle))
            $view->headTitle($dViewHeadTitle);

        // Set Separator
        if (isset($options[$module]['resources']['view']['titleSeparator']))
            $view->headTitle()->setSeparator($options[$module]['resources']['view']['titleSeparator']);
        elseif(!is_null($dViewHeadTitleSeparator))
            $view->headTitle()->setSeparator($dViewHeadTitleSeparator);

        if(isset($options[$module]['resources']['view']['pragmaNoCache']))
        {
            $pragmaNoCache = strtolower($options[$module]['resources']['view']['pragmaNoCache']) == 'on'? true : false;
        }

        if($pragmaNoCache)
        {
            $view->headMeta()->appendHttpEquiv('expires', 'Wed, 26 Feb 1997 08:21:57 GMT')
                             ->appendHttpEquiv('pragma', 'no-cache')
                             ->appendHttpEquiv('Cache-Control', 'no-cache');
        }
    }
}