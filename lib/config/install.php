<?php
$path = wa()->getDataPath('pdd/Certificates', true, 'shop/plugins');
waFiles::create($path);
waFiles::copy(wa()->getAppPath('lib/config/data/thumb_public.php', 'contest'), $path.'/thumb.php');
waFiles::copy(wa()->getAppPath('lib/config/data/.htaccess', 'contest'), $path.'/.htaccess');