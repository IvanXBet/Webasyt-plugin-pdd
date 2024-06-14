<?php

class shopPddPlugin  extends shopPlugin
{
	
	/////////////////////////////////////////////////////////////////////////////////////
	// Хуки
	/////////////////////////////////////////////////////////////////////////////////////


	public function backendProduct($data)
	{
		$view = wa()->getView();

		$plugin = waRequest::get('plugin', '', 'string');
		$module = waRequest::get('module', '', 'string');
		$action = waRequest::get('action', '', 'string');
		
		$pdd_core_li_class = 'no-tab';
		if($plugin == 'pdd' && $module == 'backend' && $action == 'control') {$pdd_core_li_class = 'selected';}
		$view->assign('pdd_core_li_class', $pdd_core_li_class);

		$view->assign('id_poduct', $data['id']);
		return array('edit_section_li' => $view->fetch(wa()->getAppPath(null, 'shop').'/plugins/pdd/templates/BackendMenuEditSectionLi.html'));
	}

	public function backendMenu()
	{
		$view = wa()->getView();
		return array(
			'aux_li' => $view->fetch(wa()->getAppPath(null, 'shop').'/plugins/pdd/templates/BackendMenuAuxLi.html'),
		);
	}
	
	static public function frontendProductCatalogs($product_id)
	{
		$collection = new shopPddPluginCatalogsCollection();
		$collection->addWhere('T.product_id = '.$product_id);
		$files = $collection->getItems('T.*');
		
		return $files;
	}

	static public function frontendProductPrices($product_id)
	{
		$collection = new shopPddPluginPricesCollection();
		$collection->addWhere('T.product_id = '.$product_id);
		$files = $collection->getItems('T.*');
		
		return $files;
	}

	static public function frontendProductCertificates($product_id, $width, $height)
	{
		$collection = new shopPddPluginCertificatesCollection();
		$collection->addWhere('T.product_id = '.$product_id);
		$collection->setOrderBy("T.sort ASC");
		$certificates = $collection->getItems('T.*');
		
		$items = array();
		foreach ($certificates as $certificate) {
			$thumb_url = shopPddPluginHelper::getThumbUrl($product_id, $certificate['id'], $width.'x'.$height, $certificate['ext']);
			$certificate['thumb_url'] = $thumb_url;
			array_push($items, $certificate);
			
		}

		return $items;
	}

	
	
	/////////////////////////////////////////////////////////////////////////////////////
	// Работа с файлами
	/////////////////////////////////////////////////////////////////////////////////////

	public function uploadFilesFromPost($files, $product_id = '', $type)
	{
		switch($type) {
			case 'catalogs':
				$file_model = new shopPddPluginCatalogsModel();
				$path = wa()->getDataPath('plugins/pdd/Catalogs/'.$product_id, true);
				break;

			case 'prices':
				$file_model = new shopPddPluginPricesModel();
				$path = wa()->getDataPath('plugins/pdd/Prices/'.$product_id, true);
				break;

			default:
				return array('result' => 0, 'message' => 'Не удалось определить категорию файла');
		}

		$tmp_path = wa()->getDataPath('plugins/pdd/tmp/', true);
		$result = array();
		foreach($files as $file) 
		{
			if(!$file->uploaded()) {$result[] = array('result' => 0, 'message' => 'Не удалось загрузить файл. Проверьте лимиты на размер файла (MAX_UPLOAD_FILESIZE)'); continue;}
			try 
			{
				$uuid = $file_model->getUUID();
				try
				{
					if((file_exists($path) && !is_writable(($path)) || !file_exists($path) && !waFiles::create($path))) 
					{
						$result[] = array('result' => 0, 'message' => 'Ошибка записи фалйла. Проверте права на запись');
					}
					else
					{
						$file->copyTo($tmp_path.$uuid.'.'.$file->extension);
						$hash = md5(file_get_contents($tmp_path.$uuid.'.'.$file->extension));
						waFiles::delete($tmp_path.$uuid.'.'.$file->extension);

						if($file_model->getByField(array('hash' => $hash, 'product_id' => $product_id))) 
						{
							$file = $file_model->getByField(array('hash' => $hash, 'product_id' => $product_id));
							$result[] = array('result' => 0, 'message' => 'Такой файл уже загружен', 'file' => $file); 
							continue;
						}
						
						$data = array(
							'product_id' => $product_id,
							'name' => pathinfo(basename($file->name), PATHINFO_FILENAME),
							'size' => $file->size,
							'original_filename' => basename($file->name),
							'ext' => $file->extension,
							'hash' => $hash
						);

						$id = $file_model->insert($data);
						$file->moveTo($path, $id.'.'.$file->extension);
						$result[] = array('result' => 1, 'message' => 'Файл загружен', 'file' =>  $file_model->getById($id));
						
					}
				}
				catch (Exception $e)
				{
					$result[] = array('result' => 0, 'message' => $e->getMessage());
				}
			}
			catch (Exception $e)
			{
				$result[] = array('result' => 0, 'message' => $e->getMessage());
			}
		}

		return $result;
		
	}

	public function uploadCertificateFromPost($files, $product_id = '') {

		$file_model = new shopPddPluginCertificatesModel();
		$path = wa()->getDataPath('plugins/pdd/Certificates/', false);
    	$public_path = wa()->getDataPath('plugins/pdd/Certificates/'.$product_id, true);
		$tmp_path = wa()->getDataPath('plugins/pdd/tmp/', true);
		$result = array();

		$valid_extensions = ['jpg', 'jpeg', 'png', 'svg'];
        
		foreach($files as $file) 
		{
			

			if(!$file->uploaded()) {$result[] = array('result' => 0, 'message' => 'Не удалось загрузить файл. Проверьте лимиты на размер файла (MAX_UPLOAD_FILESIZE)'); continue;}

			try 
			{
				$uuid = $file_model->getUUID();
				try
				{
					if((file_exists($path) && !is_writable(($path)) || !file_exists($path) && !waFiles::create($path))) 
					{
						$result[] = array('result' => 0, 'message' => 'Ошибка записи фалйла. Проверте права на запись');
					}
					else
					{
						$file->copyTo($tmp_path.$uuid.'.'.$file->extension);
						$hash = md5(file_get_contents($tmp_path.$uuid.'.'.$file->extension));
						waFiles::delete($tmp_path.$uuid.'.'.$file->extension);

						if($file_model->getByField(array('hash' => $hash, 'product_id' => $product_id))) 
						{
							$file = $file_model->getByField(array('hash' => $hash, 'product_id' => $product_id));
							$result[] = array('result' => 0, 'message' => 'Такой файл уже загружен', 'file' => $file); 
							continue;
						}
						
						$data = array(
							'product_id' => $product_id,
							'name' => pathinfo(basename($file->name), PATHINFO_FILENAME),
							'size' => $file->size,
							'original_filename' => basename($file->name),
							'ext' => $file->extension,
							'hash' => $hash,
							'sort' => $file_model->getMaxSort($product_id)+1,
						);

						if (!in_array($file->extension, $valid_extensions)) {
							$result[] = array('result' => 0, 'massage' => 'Расширение файла не подходит для сертификата');
							continue;
						}

						$id = $file_model->insert($data);
						$thumb_path =  shopPddPluginHelper::getOriginalPath($product_id, $id, $file->extension);
						$path_info = pathinfo($thumb_path);
						$dirname = $path_info['dirname'];
						waFiles::create($dirname);
						
						$file->moveTo($dirname, $id.'.'.$file->extension);
						$size = '0x400'; 
						$max_size = '2000';
						$thumb_url = shopPddPluginHelper::getThumbUrl($product_id, $id, $size, $file->extension);

						$result[] = array(
							'result' => 1,
							'message' => 'Файл загружен',
							'file' => $file_model->getById($id),
							'thumb_url' => $thumb_url,
							'thumb_path' => $thumb_path,
                    	);
						
					}
				}
				catch (Exception $e)
				{
					$result[] = array('result' => 0, 'message' => $e->getMessage());
				}
			}
			catch (Exception $e)
			{
				$result[] = array('result' => 0, 'message' => $e->getMessage());
			}
		}

        return $result;
	}

	public function updateFile($file_id, $showcase, $type) 
	{
		$result = array();
		switch($type) 
		{
			case 'catalogs':
				$model = new shopPddPluginCatalogsModel();
				break;
			case 'prices':
				$model = new shopPddPluginPricesModel();
				break;
			case 'certificates':
				$model = new shopPddPluginCertificatesModel();
				break;
			default:
				return array('result' => 0, 'message' => 'Не удалось найти файл');
		}
		
		$id = $model->escape($file_id);
		$showcase = $model->escape($showcase);
		
		if(is_numeric($id)) 
		{
			$model->updateById($id ,array('showcase' => $showcase));
			$result[] = array('result' => 1, 'message' => 'Имя для витрины сохранено', 'file' =>  $model->getById($id));
		}
		else
		{
			$result[] = array('result' => 0, 'message' => 'Ошибка записи');
		}
		return $result;
	}

	public function updateCertificates($file_id, $new_name, $new_text) 
	{
		$result = array();
		
		$model = new shopPddPluginCertificatesModel();
		
		$id = $model->escape($file_id);
		$new_name = $model->escape($new_name);
		$new_text = $model->escape($new_text);
		
		if(is_numeric($id)) 
		{
			$model->updateById($id ,array('name' => $new_name, 'text' => $new_text));
			$result[] = array('result' => 1, 'message' => 'Изменения сертификата сохранены', 'file' =>  $model->getById($id));
		}
		else
		{
			$result[] = array('result' => 0, 'message' => 'Ошибка записи');
		}
		return $result;
	}

	public function removeFile($file_id, $type)
	{
		$result = array();
		switch($type) 
		{
			case 'catalogs':
				$model = new shopPddPluginCatalogsModel();
				break;
			case 'prices':
				$model = new shopPddPluginPricesModel();
				break;
			case 'certificates':
				$model = new shopPddPluginCertificatesModel();
				break;
			default:
				return array('result' => 0, 'message' => 'Не удалось найти файл');
		}
		$file = $model->getById($model->escape($file_id));

		if($type !==  'certificates')
        {
            $path = wa()->getDataPath("plugins/pdd/$type/".$file['product_id'].'/'.$file_id.'.'.$file['ext'], true);
        } 
        else
        {
            $path = shopPddPluginHelper::getOriginalPath($file['product_id'], $file['id'], $file['ext']);
			$thumb_path =  shopPddPluginHelper::getThumbsPath($file['product_id'], $file['id']);
			waFiles::delete($thumb_path);
			
        }

        if (!file_exists($path)) {
            
            return $result[] = array('result' => 0, 'message' => 'Файл по указанному пути не существует: ' . $path);
        }
		waFiles::delete($path);
		

		$model->deleteById($model->escape($file_id));

		if (!file_exists($path)) {
            $result[] = array('result' => 1, 'message' => 'Файл удалён');
        }
		
		return $result;
	}

	public function sortCertificate($ids) 
	{
		if(!count($ids)) {return array('result' => 0, 'message' => 'Не заданн список для сортировки');}
		$model = new shopPddPluginCertificatesModel();
		return $model->sortSets($ids);
	}
}
