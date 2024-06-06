<?php

class shopPddPluginFileUploadController extends waJsonController
{
	public function execute()
	{
		$files = waRequest::file('files');
		$product_id = waRequest::post("product_id", null, 'int');
		$type = waRequest::post("type", '', 'string');
		$pdd = waSystem::getInstance('shop')->getPlugin('pdd');
		$this->response = $pdd->uploadFilesFromPost($files, $product_id, $type);
	}
}