<?php
namespace GDO\Captcha\tpl\form;
use GDO\Captcha\GDT_Captcha;
/** @var $field GDT_Captcha **/
?>
<div>
 <input
<?=$field->htmlID()?>
  autocomplete="off"
  type="text"
  pattern="[a-zA-Z]{5}"
  size="5"
  required="required"
<?=$field->htmlFormName()?>
  value="<?=$field->getVar()?>" /><img class="ib gdo-captcha-img" src="<?=$field->hrefCaptcha()?>"
   alt="Captcha" onclick="this.src='<?=$field->hrefNewCaptcha()?>'+(new Date().getTime())" /></div>
