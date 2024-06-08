<?php 
	class shopPddPluginCertificatesModel extends waModel
	{
		protected $table = 'shop_pdd_certificates'; 
		public function getUUID()
		{
			$data = $this->query('SELECT UUID() AS uuid')->fetchAll();
			return $data[0]['uuid'];
		}
		public function getMaxSort($product_id) 
        {
            $data = $this->query("SELECT  MAX(sort) AS mx FROM ".$this->table." WHERE product_id = ".$product_id)->fetchAll();
           
            if(!count($data)) {return 0;}
            return $data[0]["mx"];
        }
		public function sortSets($arr_ids)
        {

            if(!count($arr_ids)) {return;}
            $sort = 1;
            foreach($arr_ids as $key => $id) {
                $this->updateById($id, array('sort' => $sort));
                $sort++;
            }
            return array(
                'data' => $arr_ids,
                'mas' => 'Готово',
            );
        }
	}