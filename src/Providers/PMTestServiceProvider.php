<?php
namespace PMTest\Providers;

use Plenty\Plugin\ServiceProvider;
use Plenty\Plugin\Templates\Twig;
use Plenty\Plugin\Events\Dispatcher;
use Plenty\Plugin\ConfigRepository;
use Plenty\Modules\Frontend\Services;


/**
 * Class PMTestServiceProvider
 * @package PMTest\Providers
 */
class PMTestServiceProvider extends ServiceProvider
{

	const YOOCHOOSE_CDN_SCRIPT = '//event.yoochoose.net/cdn';
	const AMAZON_CDN_SCRIPT = '//cdn.yoochoose.net';

	/**
	 * Register the service provider.
	 */
	public function register()
	{
		$this->getApplication()->register(PMTestRouteServiceProvider::class);
	}

	public function boot(Twig $twig, Dispatcher $eventDispatcher, ConfigRepository $config,Services $services)
	{
		$services->addJsFile($this->getScriptURL($config));

	}

	private function getScriptURL(ConfigRepository $config):string
	{
		$mandator = $config->get('PMTest.customer.id');
		$plugin = $config->get('PMTest.plugin.id');
		$plugin = $plugin ? '/' . $plugin : '';
		$scriptOverwrite = $config->get('PMTest.overwrite.endpoint');

		if ($scriptOverwrite) {
			$scriptOverwrite = (!preg_match('/^(http|\/\/)/', $scriptOverwrite) ? '//' : '') . $scriptOverwrite;
			$scriptUrl = preg_replace('(^https?:)', '', $scriptOverwrite);
		} else {
			$scriptUrl = $config->get('PMTest.performance') ?
				self::AMAZON_CDN_SCRIPT : self::YOOCHOOSE_CDN_SCRIPT;
		}

		$scriptUrl = $scriptUrl . 'v1/'. $mandator . '/' . $plugin. '/tracking.js';
//        $result = sprintf('<script type="text/javascript" src="%s"></script>', $scriptUrl . 'js');
//        $result .= sprintf('<link type="text/css" rel="stylesheet" href="%s">', $scriptUrl . 'css');

		return $scriptUrl;
	}
}
