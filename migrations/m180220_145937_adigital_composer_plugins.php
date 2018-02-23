<?php

namespace craft\contentmigrations;

use Craft;
use craft\db\Migration;

/**
 * m180220_145937_adigital_composer_plugins migration.
 */
class m180220_145937_adigital_composer_plugins extends Migration
{
    /**
     * @inheritdoc
     */
    public function safeUp()
    {
	    /////////////////////////////////////////////
		/////////////////  COMPOSER  ////////////////
		/////////////////////////////////////////////
		/**
		 * Plugins:
		 * 		Zendesk
		 * 		Redactor
		 * 		Minify
		 */
		
		Craft::$app->composer->install([
			"adigital/zendesk" => "v1.0.0",
			"craftcms/redactor" => "1.0.1",
			"nystudio107/craft-minify" => "1.2.7"
		]);
		
		return true;
    }

    /**
     * @inheritdoc
     */
    public function safeDown()
    {
        Craft::$app->composer->uninstall([
			"nystudio107/craft-minify",
			"craftcms/redactor",
			"adigital/zendesk"
		]);
		
        return true;
    }
}
