<?php
namespace GDO\Captcha;

use GDO\Core\GDO_Module;
use GDO\UI\GDT_Color;
use GDO\UI\GDT_Font;
use GDO\Core\GDT_UInt;

/**
 * Captcha implementation via PhpCaptcha.php
 * @TODO Add a hidden field captcha_title/ctitle that may not be filled out.
 * 
 * @author gizmore
 * @version 7.0.1
 * @since 3.2.0
 */
final class Module_Captcha extends GDO_Module
{
    public string $license = 'BSD';
    
    ##############
    ### Config ###
    ##############
	public function getConfig() : array
	{
		return [
			GDT_Font::make('captcha_font')->multiple()->minSelected(1)->initial($this->getInitialFontsVar())->notNull(),
			GDT_Color::make('captcha_bg')->initial('#f8f8f8')->notNull(),
			GDT_UInt::make('captcha_width')->initial('256')->min(48)->max(512)->notNull(),
			GDT_UInt::make('captcha_height')->initial('48')->min(24)->max(256)->notNull(),
		];
	}
	public function cfgCaptchaFonts() : array { return $this->getConfigValue('captcha_font'); }
	public function cfgCaptchaBG() : string { return $this->getConfigValue('captcha_bg'); }
	public function cfgCaptchaWidth() : int { return $this->getConfigValue('captcha_width'); }
	public function cfgCaptchaHeight() : int { return $this->getConfigValue('captcha_height'); }
	
	#############
	### Hooks ###
	#############
	public function onLoadLanguage() : void
	{
		$this->loadLanguage('lang/captcha');
	}

	public function onIncludeScripts() : void
	{
	    $this->addCss('css/captcha.css');
	}

	###############
	### Private ###
	###############
	private function getInitialFontsVar() : string
	{
		return '["arial","kitten","kitten2","microgramma","teen"]';
// 		$f = 'GDO/Core/thm/default/fonts/';
// 		return [
// 			"{$f}arial.ttf",
// 			"{$f}kitten.ttf",
// 			"{$f}kitten2.ttf",
// 			"{$f}microgramma.ttf",
// 			"{$f}teen.ttf",
// 		];
	}

}
