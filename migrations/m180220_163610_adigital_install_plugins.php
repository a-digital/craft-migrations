<?php

namespace craft\contentmigrations;

use Craft;
use craft\db\Migration;

/**
 * m180220_163610_adigital_install_plugins migration.
 */
class m180220_163610_adigital_install_plugins extends Migration
{
    /**
     * @inheritdoc
     */
    public function safeUp()
    {
	    /////////////////////////////////////////////
		/////////////////  PLUGINS  /////////////////
		/////////////////////////////////////////////
		/**
		 * Plugins:
		 * 		Zendesk
		 * 		Redactor
		 * 		Minify
		 */
		
        Craft::$app->plugins->init();
        Craft::$app->plugins->installPlugin("zendesk");
		$plugin = Craft::$app->plugins->getPlugin("zendesk");
		$settings = [
			"api_key" => "gPwSCpfFO0YH8cZzvnKvWwJj0lqEcqvmuJbgxf04",
			"user" => "andrew@adigital.co.uk",
			"url" => "https://adigital.zendesk.com/api/v2",
			"widgetName" => "Ask A Digital",
			"ticketUrl" => "https://adigital.zendesk.com/hc/requests/"
		];
		Craft::$app->plugins->savePluginSettings($plugin, $settings);
		
		Craft::$app->plugins->installPlugin("redactor");
		
		Craft::$app->plugins->installPlugin("minify");
		
		return true;
    }

    /**
     * @inheritdoc
     */
    public function safeDown()
    {
	    Craft::$app->plugins->uninstallPlugin("minify");
	    
	    Craft::$app->plugins->uninstallPlugin("redactor");
	    
        Craft::$app->plugins->uninstallPlugin("zendesk");
        
        return true;
    }
}
