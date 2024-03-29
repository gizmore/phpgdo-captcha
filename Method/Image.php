<?php
declare(strict_types=1);
namespace GDO\Captcha\Method;

use GDO\Captcha\Module_Captcha;
use GDO\Captcha\PhpCaptcha;
use GDO\Core\Application;
use GDO\Core\GDT;
use GDO\Core\MethodAjax;
use GDO\Net\HTTP;
use GDO\Session\GDO_Session;

/**
 * Create and display a captcha.
 *
 * @version 7.0.3
 * @since 2.0.0
 * @author gizmore
 */
class Image extends MethodAjax
{

	public function isTrivial(): bool { return false; }

	public function execute(): GDT
	{
		# Load the Captcha class
		$module = Module_Captcha::instance();

		# disable HTTP Caching
		HTTP::noCache();

		# Setup Font, Color, Size
		$aFonts = [];
		$bFonts = $module->cfgCaptchaFonts();
		foreach ($bFonts as $path => $font)
		{
			$aFonts[] = GDO_PATH . $path;
		}
		$rgbcolor = ltrim($module->cfgCaptchaBG(), '#');
		$width = $module->cfgCaptchaWidth();
		$height = $module->cfgCaptchaHeight();
		$oVisualCaptcha = new PhpCaptcha($aFonts, $width, $height, $rgbcolor);

		if ($this->hasInputFor('new'))
		{
			GDO_Session::remove('php_captcha');
			GDO_Session::remove('php_captcha_lock');
			GDO_Session::commit();
		}

		$challenge = GDO_Session::get('php_captcha_lock', true);
		$oVisualCaptcha->Create('', $challenge);
		return Application::exit();
	}

}
