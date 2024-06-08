<?php

return array
	(
		'name' => 'pdd',
		'version' => '1.0.0',
		'vendor' => 995002,
		'description' => 'Позволяет загружать в товар несколько PDF с каталогами, несколько прайс-листов файлами, и несколько сертификатов картинками.',
		'img' => 'img/main_image.svg',
		'handlers' => array
						(
							'backend_product' => 'backendProduct',
							'frontend_product' => 'frontendProduct',
							'backend_menu' => 'backendMenu',
						),
	);