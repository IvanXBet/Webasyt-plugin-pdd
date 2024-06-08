<?php
class shopPddPluginDialogDeleteAction extends waViewAction
{
    public function execute() 
    {
        $file_id = waRequest::post('file_id', 0, 'int');
        $type = waRequest::post('type', '', 'string');
        switch($type) {
            case 'catalogs':
                $model = new shopPddPluginCatalogsModel();
                break;
            case 'prices':
                $model = new shopPddPluginPricesModel();
                break;
            case 'certificates':
                $model = new shopPddPluginCertificatesModel();
                break;
            default:
                return array('result' => 0, 'message' => 'Не удалось найти файл');
        }

		$file = $model->getById($file_id);

        $this->view->assign('file', $file);
        $this->view->assign('type', $type);
    }

}