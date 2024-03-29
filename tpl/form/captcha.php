<?php
namespace GDO\Captcha\tpl\form;

/** @var $field GDT_Captcha * */

use GDO\Captcha\GDT_Captcha;
use GDO\Core\GDT_Template;

?>
<div class="gdt-captcha gdt-container<?=$field->classError()?>">
    <label<?=$field->htmlForID()?>><?=$field->htmlIcon()?><?=t('captcha')?></label>
	<?=GDT_Template::php('Captcha', 'form/captcha_inner.php', ['field' => $field])?>
	<?=$field->htmlError()?>
</div>
