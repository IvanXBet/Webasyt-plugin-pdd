<?php
class shopPddPluginBackendEditController extends waJsonController
{
	public function execute()
	{
		$file = waRequest::post('file', null, );
        $type = waRequest::post('type', '', 'string');

		$file_id = $file['id'];
		$new_name = $file['name'];


		if (!$file_id) {
            $this->response = array('result' => 0, 'message' => 'ID файла не указан');
            return;
        }

		
		$pdd = waSystem::getInstance('shop')->getPlugin('pdd');
        $this->response = $pdd->updateFile($file_id, $new_name, $type);
		
	}
}