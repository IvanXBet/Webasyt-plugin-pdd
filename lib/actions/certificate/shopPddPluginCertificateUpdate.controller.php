<?php
class shopPddPluginCertificateUpdateController extends waJsonController
{
	public function execute()
	{
		$file_id = waRequest::post('file_id', null, 'int');
		$new_name = waRequest::post('new_name', '', 'string');
        $new_text = waRequest::post('new_text', '', 'string');



		if (!$file_id) {
            $this->response = array('result' => 0, 'message' => 'ID файла не указан');
            return;
        }

		
		$pdd = waSystem::getInstance('shop')->getPlugin('pdd');
        $this->response = $pdd->updateCertificates($file_id, $new_name, $new_text);
		
	}
}