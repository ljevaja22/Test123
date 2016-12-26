<?php
namespace PMTest\Controllers;

use Plenty\Plugin\Controller;
use Plenty\Plugin\ConfigRepository;
use Plenty\Plugin\Templates\Twig;
use Plenty\Modules\Frontend\Services;

/**
 * Class ContentController
 * @package PMTest\Controllers
 */
class ContentController extends Controller
{

    const YOOCHOOSE_CDN_SCRIPT = '//event.yoochoose.net/cdn';
    const AMAZON_CDN_SCRIPT = '//cdn.yoochoose.net';
	/**
	 * @param Twig $twig
	 * @return string
	 */
	public function sayHello(Twig $twig):string
	{
//        $jsFile = $this->getTitle();

//        addJsFile($jsFile);

//		return $twig->render('PMTest::content.hello');
        
        return '';
	}

    // access configuration from PHP
    public function getTitle(ConfigRepository $config):string
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

        $scriptUrl = $scriptUrl . "v1/{$mandator}{$plugin}/tracking.";
//        $result = sprintf('<script type="text/javascript" src="%s"></script>', $scriptUrl . 'js');
//        $result .= sprintf('<link type="text/css" rel="stylesheet" href="%s">', $scriptUrl . 'css');

        return $scriptUrl;
    }
}
