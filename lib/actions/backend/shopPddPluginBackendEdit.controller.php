<?php
class shopPddPluginBackendEditController extends waJsonController
{
	public function execute()
	{
		$file_id = waRequest::post('file_id', null, 'int');
		$showcase = waRequest::post('showcase', '', 'string');
        $type = waRequest::post('type', '', 'string');


		if (!$file_id) {
            $this->response = array('result' => 0, 'message' => 'ID файла не указан');
            return;
        }

		
		$pdd = waSystem::getInstance('shop')->getPlugin('pdd');
        $this->response = $pdd->updateFile($file_id, $showcase, $type);
		
	}
}