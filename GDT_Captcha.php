<?php
namespace GDO\Captcha;

use GDO\Session\GDO_Session;
use GDO\Core\Application;
use GDO\Core\GDT_Template;
use GDO\Core\GDT_String;

/**
 * Very basic captcha and easy to solve. Solving rate for humans is around 98%. Bots easily can cope with it.
 * 
 * @author gizmore
 * @version 7.0.1
 * @since 3.4.0
 */
class GDT_Captcha extends GDT_String
{
	public function getDefaultName() : string { return 'captcha'; }
	
	protected function __construct()
	{
	    parent::__construct();
		$this->icon('captcha');
		$this->notNull();
		$this->tooltip('tt_captcha');
		$this->initial(GDO_Session::get('php_captcha_lock'));
	}
	
	public function hrefCaptcha() : string
	{
	    return href('Captcha', 'image');
	}
	
	public function hrefNewCaptcha() : string
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
	    
		if ($stored = GDO_Session::get('php_captcha'))
		{
			if (strtoupper($value) === strtoupper($stored))
			{
			    $this->unsetRequest();
			    GDO_Session::set('php_captcha_lock', strtoupper($value));
			    $this->initial($value);
			    return true;
			}
		}
		return $this->invalidate();
	}
	
	public function onSubmitted() : void
	{
		$this->invalidate();
	}
	
	public function invalidate() : bool
	{
		GDO_Session::remove('php_captcha');
		GDO_Session::remove('php_captcha_lock');
		$this->unsetRequest();
		return $this->error('err_captcha');
	}

	private function unsetRequest() : void
	{
		$this->reset(true);
		$this->initial(null);
	}
	
}
