<?php
namespace GDO\Captcha;

use GDO\Core\Application;
use GDO\Core\GDT_String;
use GDO\Core\GDT_Template;
use GDO\Session\GDO_Session;

/**
 * Very basic captcha and easy to solve. Solving rate for humans is around 98%. Bots easily can cope with it.
 *
 * @version 7.0.1
 * @since 3.4.0
 * @author gizmore
 */
class GDT_Captcha extends GDT_String
{

	protected function __construct()
	{
		parent::__construct();
		$this->icon('captcha');
		$this->notNull();
		$this->tooltip('tt_captcha');
		$this->initial(GDO_Session::get('php_captcha_lock'));
	}

	public function gdtDefaultName(): ?string { return 'captcha'; }

	public function renderForm(): string
	{
		return GDT_Template::php('Captcha', 'form/captcha.php', ['field' => $this]);
	}

	public function validate(int|float|string|array|null|object|bool $value): bool
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

	private function unsetRequest(): void
	{
		$this->reset();
		$this->initial(null);
	}

	################
	### Validate ###
	################

	public function invalidate(): bool
	{
		GDO_Session::remove('php_captcha');
		GDO_Session::remove('php_captcha_lock');
		$this->unsetRequest();
		return $this->error('err_captcha');
	}

	public function onSubmitted(): void
	{
		$this->invalidate();
	}

	public function hrefCaptcha(): string
	{
		return href('Captcha', 'image');
	}

	public function hrefNewCaptcha(): string
	{
		return href('Captcha', 'image', '&new=1&_ajax=1');
	}

}
