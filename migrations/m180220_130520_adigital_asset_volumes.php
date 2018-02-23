<?php

namespace craft\contentmigrations;

use Craft;
use craft\db\Migration;

/**
 * m180220_130520_adigital_asset_volumes migration.
 */
class m180220_130520_adigital_asset_volumes extends Migration
{
    /**
     * @inheritdoc
     */
    public function safeUp()
    {
	    /////////////////////////////////////////////
		//////////////  ASSET VOLUMES  //////////////
		/////////////////////////////////////////////
	    /**
		 * Volumes: Main Uploads, Gallery
		 *
		 * Options:
		 * 		craft\volumes\Local:
		 *			path: '/path/to/folder'
		 *
		 * Error Checking:
		 * 		Craft::$app->matrix->validateFieldSettings($matrixField);
		 * 		echo "<pre>";
		 * 		print_r($matrixField);
		 * 		exit;
		 */
	    
        if (is_null(Craft::$app->volumes->getVolumeByHandle("mainUploads"))) {
	        $volume = new \craft\volumes\Local([
		        "name" => "Main Uploads",
		        "handle" => "mainUploads",
		        "hasUrls" => true,
		        "url" => "@web/media/main-uploads",
		        "path" => "@webroot/media/main-uploads"
	        ]);
	        
	        Craft::$app->volumes->saveVolume($volume);
        }
        
        if (is_null(Craft::$app->volumes->getVolumeByHandle("gallery"))) {
	        $volume = new \craft\volumes\Local([
		        "name" => "Gallery",
		        "handle" => "gallery",
		        "hasUrls" => true,
		        "url" => "@web/media/gallery",
		        "path" => "@webroot/media/gallery"
	        ]);
	        
	        Craft::$app->volumes->saveVolume($volume);
        }
    }

    /**
     * @inheritdoc
     */
    public function safeDown()
    {
        $mainUploads = Craft::$app->volumes->getVolumeByHandle("mainUploads");
        if (!is_null($mainUploads)) {
	        Craft::$app->volumes->deleteVolumeById($mainUploads->id);
        }
        
        $gallery = Craft::$app->volumes->getVolumeByHandle("gallery");
        if (!is_null($gallery)) {
	        Craft::$app->volumes->deleteVolumeById($gallery->id);
        }
		
		return true;
    }
}
