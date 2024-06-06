<?php
class shopPddPluginBackendDownloadController extends waJsonController
{
    public function execute() 
    {
        $file_id = waRequest::get('file_id', null, 'int'); 
        $type = waRequest::get('type', '', 'string'); 

        if (!$file_id) {
            $this->response = array('result' => 0, 'message' => 'ID файла не указан');
            return;
        }

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
                return array('result' => 0, 'message' => 'Не удалось скачать файл');
        }

        $file = $model->getById($file_id);

        if (!$file) {
           
            return   array('result' => 0, 'message' => 'Файл не найден');
        }
        if($type !==  'certificates')
        {
            $path = wa()->getDataPath("plugins/pdd/$type/".$file['product_id'].'/'.$file_id.'.'.$file['ext'], true);
        } 
        else
        {
            $path = shopPddPluginHelper::getOriginalPath($file['product_id'], $file['id'], $file['ext']);
        }
       

        if (!file_exists($path)) {
            return array('result' => 0, 'message' => 'Файл по указанному пути не существует: ' . $path);
        }

        $name = pathinfo($file['name'], PATHINFO_FILENAME);  
        waFiles::readFile($path, $name.'.'.$file['ext']); 
    }

}


 
 