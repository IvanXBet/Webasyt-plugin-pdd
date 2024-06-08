<?php
class shopPddPluginCertificateSortController extends waJsonController
{
	public function execute()
	{
		$ids = waRequest::post('ids', '', 'string');
		if(!mb_strlen($ids)) {$this->response = array('result' => 0, 'message' => 'Системная ошибка #NOARR'); return;}
        $ids = str_replace("ids=", "", $ids);
        $arr_ids = explode(',', $ids);
            
		$pdd = waSystem::getInstance('shop')->getPlugin('pdd');
		$this->response = $pdd->sortCertificate($arr_ids);
	}
}