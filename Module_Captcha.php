<?php
declare(strict_types=1);
namespace GDO\Captcha;

use GDO\Core\GDO_Module;
use GDO\Core\GDT_UInt;
use GDO\UI\GDT_Color;
use GDO\UI\GDT_Font;

/**
 * Captcha implementation via PhpCaptcha.php
 *
 * Requires Session.
 *
 * @TODO Add a hidden field captcha_title/ctitle that may not be filled out.
 *
 * @version 7.0.3
 * @since 3.2.0
 * @author gizmore
 */
final class Module_Captcha extends GDO_Module
{

	public string $license = 'BSD';

	##############
	### Config ###
	##############

	public function getDependencies(): array
	{
		return [
			'Session',
		];
	}


	public function getConfig(): array
	{
		return [
			GDT_Font::make('captcha_font')->multiple()->minSelected(1)->maxSelected(20)->initial($this->getInitialFontsVar())->notNull(),
			GDT_Color::make('captcha_bg')->initial('#f8f8f8')->notNull(),
			GDT_UInt::make('captcha_width')->initial('256')->min(48)->max(512)->notNull(),
			GDT_UInt::make('captcha_height')->initial('48')->min(24)->max(256)->notNull(),
		];
	}

	private function getInitialFontsVar(): string
	{
		$f = 'GDO/Core/thm/default/fonts/';
		$fonts = [
			"{$f}arial.ttf" => 'arial',
			"{$f}microgramma.ttf" => 'microgramma',
		];
		return json_encode($fonts);
	}

	public function onLoadLanguage(): void
	{
		$this->loadLanguage('lang/captcha');
	}

	public function onIncludeScripts(): void
	{
		$this->addCSS('css/captcha.css');
	}

	public function cfgCaptchaBG(): string { return $this->getConfigValue('captcha_bg'); }



	public function cfgCaptchaWidth(): int { return $this->getConfigValue('captcha_width'); }


	public function cfgCaptchaHeight(): int { return $this->getConfigValue('captcha_height'); }


	public function cfgCaptchaFonts(): array
	{
		return json_decode($this->getConfigVar('captcha_font'), true);
	}

}
