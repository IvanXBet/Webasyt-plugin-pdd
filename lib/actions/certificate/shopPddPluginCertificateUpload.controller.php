<?php

class shopPddPluginCertificateUploadController extends waJsonController
{
	public function execute()
	{
		$files = waRequest::file('files');
		$product_id = waRequest::post("product_id", null, 'int');

		$pdd = waSystem::getInstance('shop')->getPlugin('pdd');
		$this->response = $pdd->uploadCertificateFromPost($files, $product_id);
	}
}

