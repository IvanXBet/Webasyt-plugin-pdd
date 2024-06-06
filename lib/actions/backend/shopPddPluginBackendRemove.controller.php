<?php
class shopPddPluginBackendRemoveController extends waJsonController
{
	public function execute()
	{
		$file_id = waRequest::post('file_id', null, 'int');
        $type = waRequest::post('type', '', 'string');

		if (!$file_id) {
            $this->response = array('result' => 0, 'message' => 'ID файла не указан');
            return;
        }
		
		$pdd = waSystem::getInstance('shop')->getPlugin('pdd');
        $this->response = $pdd->removeFile($file_id, $type);
	}
}