<?php
class shopPddPluginFileListCatalogsController extends shopPddPluginBackendDatatableController
{

	public function execute()
	{
		$hash = waRequest::get('hash', '');

		$collection = new shopPddPluginCatalogsCollection();
		$collection->addWhere('T.product_id = '.$hash);
		$files = $collection->getItems('T.*');
		
		
		$items = array();
		foreach($files as $file) 
		{
			$tmp = array(
				htmlspecialchars($file['name'].'.'.$file['ext'], ENT_QUOTES),
				'<a href="?plugin=pdd&module=backend&action=download&file_id='.intval($file['id']).'&type=catalogs">
					<i class="icon16 download pdd-catalogs-download" title="Скачать" data-id="'.intval($file['id']).'"></i>
				</a>',
				'<i class="icon16 edit pdd-catalogs-edit" title="Редактировать" data-id="'.intval($file['id']).'" data-name="'.$file['name'].'"></i>',
				'<i class="icon16 delete pdd-catalogs-delete" title="Удалить" data-id="'.intval($file['id']).'" data-name="'.$file['name'].'"></i>',
			);
			array_push($items,  $tmp);
		}
		$result['data'] = $items;
		$result['draw'] = $this->draw;
		$this->response = $result;
	}
}