<?php
namespace GDO\Captcha;

use GDO\Form\GDT_Form;
use GDO\Session\GDO_Session;
use GDO\Core\Application;
use GDO\Core\GDT_Template;
use GDO\Core\GDT_String;

/**
 * Very basic captcha and easy to solve. Solving rate for humans is around 98%. Bots easily can cope with it.
 * 
 * @deprecated @TODO: captcha is too easy to break.
 * @author gizmore
 * @version 7.0.0
 * @since 3.4.0
 */
class GDT_Captcha extends GDT_String
{
	public bool $notNull = true;
	
	public bool $cli = false;
	
	public function addFormValue(GDT_Form $form, $value) {}
	
	public function getDefaultName() : string { return 'captcha'; }
	
	protected function __construct()
	{
	    parent::__construct();
		$this->icon('captcha');
		$this->tooltip('tt_captcha');
		$this->initial = GDO_Session::get('php_captcha_lock');
	}
	
	public function hrefCaptcha()
	{
	    return href('Captcha', 'image');
	}
	
	public function hrefNewCaptcha()
	{
	    return href('Captcha', 'image', '&new=1&_ajax=1');
	}

	public function renderForm() : string
	{
		return GDT_Template::php('Captcha', 'form/captcha.php', ['field' => $this]);
	}
	
	################
	### Validate ###
	################
	public function validate($value) : bool
	{
	    $app = Application::$INSTANCE;
	    if ($app->isCLI() || $app->isUnitTests())
	    {
	        return true;
	    }
	    
		$stored = GDO_Session::get('php_captcha');
		if (strtoupper($value) === strtoupper($stored))
		{
		    $this->unsetRequest();
		    GDO_Session::set('php_captcha_lock', strtoupper($value));
		    $this->initial($value);
		    return true;
		}
		return $this->invalidate();
	}
	
	public function invalidate()
	{
	    GDO_Session::remove('php_captcha');
	    GDO_Session::remove('php_captcha_lock');
	    $this->unsetRequest();
		return $this->error('err_captcha');
	}

	public function onValidated()
	{
	    GDO_Session::remove('php_captcha');
	    GDO_Session::remove('php_captcha_lock');
	    $this->unsetRequest();
	}
	
	private function unsetRequest()
	{
	    $this->var = $this->initial = null;
// 	    unset($_REQUEST[$this->formVariable()][$this->name]);
	}
	
}
