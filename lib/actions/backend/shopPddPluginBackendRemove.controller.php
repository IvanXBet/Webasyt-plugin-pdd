<?php
class shopPddPluginBackendRemoveController extends waJsonController
{
	public function execute()
	{
		$file = waRequest::post('file', null);
        $type = waRequest::post('type', '', 'string');

		if (!$file['id']) {
            $this->response = array('result' => 0, 'message' => 'ID файла не указан', 'file' => $file['id']);
            return;
        }
		
		$pdd = waSystem::getInstance('shop')->getPlugin('pdd');
        $this->response = $pdd->removeFile($file['id'], $type);
	}
}