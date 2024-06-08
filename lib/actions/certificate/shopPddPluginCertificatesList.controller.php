<?php
class shopPddPluginCertificatesListController extends waJsonController
{

	public function execute()
	{
		$hash = waRequest::get('hash', '');

		$collection = new shopPddPluginCertificatesCollection();
		$collection->addWhere('T.product_id = '.$hash);
		$collection->setOrderBy("T.sort ASC");
		$files = $collection->getItems('T.*');
		
		
		
		$items = array();
		foreach($files as $file) 
		{
			$thumb_url = shopPddPluginHelper::getThumbUrl($hash, $file['id'], '0x200', $file['ext']);
			
			$tmp = array(
				'id' => intval($file['id']),
				'name' => htmlspecialchars($file['name'].'.'.$file['ext'], ENT_QUOTES),
				'download_url' => '?plugin=pdd&module=backend&action=download&file_id='.intval($file['id']).'&type=certificates',
				'thumb_url' => $thumb_url,
			);
			array_push($items,  $tmp);
		}
		

		$this->response = $items;
	}
}