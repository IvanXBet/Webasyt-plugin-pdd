<?php 
	class shopPddPluginCertificatesModel extends waModel
	{
		protected $table = 'shop_pdd_certificates'; 
		public function getUUID()
		{
			$data = $this->query('SELECT UUID() AS uuid')->fetchAll();
			return $data[0]['uuid'];
		}
	}