<?php

namespace craft\contentmigrations;

use Craft;
use craft\db\Migration;

/**
 * m180221_092708_adigital_assets_fields migration.
 */
class m180221_092708_adigital_assets_fields extends Migration
{
    /**
     * @inheritdoc
     */
    public function safeUp()
    {
		/////////////////////////////////////////////
		///////////////  FIELD GROUP  ///////////////
		/////////////////////////////////////////////
		/**
		 * Group: Assets
		 */
		
		$assetsGroup = null;
		$groups = Craft::$app->getFields()->getAllGroups();
		foreach($groups as $group) {
			if ($group->name == "Assets") {
				$assetsGroup = $group;
			}
		}
		
		if (is_null($assetsGroup)) {
			$assetsGroup = new \craft\models\FieldGroup();
			$assetsGroup->name = "Assets";
			Craft::$app->getFields()->saveGroup($assetsGroup);
		}
		
		$mainUploadsVolume = Craft::$app->volumes->getVolumeByHandle("mainUploads");
		$mainUploadsFolderTree = Craft::$app->assets->getFolderTreeByVolumeIds([$mainUploadsVolume->id]);
		$mainUploadsFolder = $mainUploadsFolderTree[0]->id;
		$galleryVolume = Craft::$app->volumes->getVolumeByHandle("gallery");
		$galleryFolderTree = Craft::$app->assets->getFolderTreeByVolumeIds([$galleryVolume->id]);
		$galleryFolder = $galleryFolderTree[0]->id;
		
		/////////////////////////////////////////////
		//////////////  ASSETS FIELDS  //////////////
		/////////////////////////////////////////////
		/**
		 * Fields: Main Image, Gallery
		 * 
		 * Options:
		 * 		useSingleFolder: 1
		 * 		sources: *, [folder:1]
		 * 		defaultUploadLocationSource: folder:1
		 * 		defaultUploadLocationSubpath: "path/to/subfolder"
		 * 		singleUploadLocationSource: folder:1
		 * 		singleUploadLocationSubpath: "path/to/subfolder"
		 * 		restrictFiles: 1
		 * 		allowedKinds: [access, audio, compressed, excel, flash, html, illustrator, image, javascript, json, pdf, photoshop, php, powerpoint, text, video, word, xml]
		 * 		viewMode: list, large
		 */
		
		if (is_null(Craft::$app->getFields()->getFieldByHandle("mainImage"))) {
			$mainImageField = Craft::$app->getFields()->createField([
			    "type" => "craft\\fields\\Assets",
			    "groupId" => $assetsGroup->id,
			    "name" => "Main Image",
			    "handle" => "mainImage",
			    "instructions" => "Choose or upload a main image for this entry which can be used as a banner and also a thumbnail",
			    "translationMethod" => "none",
			    "translationKeyFormat" => NULL,
			    "settings" => [
			        "useSingleFolder" => 1,
			        "singleUploadLocationSource" => "folder:".$mainUploadsFolder,
			        "singleUploadLocationSubpath" => "",
			        "restrictFiles" => "",
			        "allowedKinds" => ["access", "audio", "compressed", "excel", "flash", "html", "illustrator", "image", "javascript", "json", "pdf", "photoshop", "php", "powerpoint", "text", "video", "word", "xml"],
			        "limit" => "1",
			        "viewMode" => "list",
			        "selectionLabel" => ""
			    ]
			]);
			Craft::$app->getFields()->saveField($mainImageField);
		}
		
		if (is_null(Craft::$app->getFields()->getFieldByHandle("gallery"))) {
			$galleryField = Craft::$app->getFields()->createField([
			    "type" => "craft\\fields\\Assets",
			    "groupId" => $assetsGroup->id,
			    "name" => "Gallery",
			    "handle" => "gallery",
			    "instructions" => "Choose or upload multiple images to create a gallery or slider",
			    "translationMethod" => "none",
			    "translationKeyFormat" => NULL,
			    "settings" => [
			        "useSingleFolder" => 1,
			        "singleUploadLocationSource" => "folder:".$galleryFolder,
			        "singleUploadLocationSubpath" => "",
			        "restrictFiles" => "",
			        "allowedKinds" => ["access", "audio", "compressed", "excel", "flash", "html", "illustrator", "image", "javascript", "json", "pdf", "photoshop", "php", "powerpoint", "text", "video", "word", "xml"],
			        "limit" => "",
			        "viewMode" => "list",
			        "selectionLabel" => ""
			    ]
			]);
			Craft::$app->getFields()->saveField($galleryField);
		}
	
	    return true;
    }

    /**
     * @inheritdoc
     */
    public function safeDown()
    {
        $mainImage = Craft::$app->getFields()->getFieldByHandle("mainImage");
        if (!is_null($mainImage)) {
	        Craft::$app->getFields()->deleteFieldById($mainImage->id);
        }
        $gallery = Craft::$app->getFields()->getFieldByHandle("gallery");
        if (!is_null($gallery)) {
	        Craft::$app->getFields()->deleteFieldById($gallery->id);
        }
        
        $groups = Craft::$app->getFields()->getAllGroups();
		foreach($groups as $group) {
			if (count(Craft::$app->getFields()->getFieldsByGroupId($group->id)) == 0 && $group->name == "Assets") {
				Craft::$app->getFields()->deleteGroupById($group->id);
			}
		}
        
        return true;
    }
}
