<?php
class shopPddPluginBackendEditController extends waJsonController
{
	public function execute()
	{
		$file_id = waRequest::post('file_id', null, 'int');
		$new_name = waRequest::post('new_name', '', 'string');
        $type = waRequest::post('type', '', 'string');


		if (!$file_id) {
            $this->response = array('result' => 0, 'message' => 'ID файла не указан');
            return;
        }

		
		$pdd = waSystem::getInstance('shop')->getPlugin('pdd');
        $this->response = $pdd->updateFile($file_id, $new_name, $type);
		
	}
}