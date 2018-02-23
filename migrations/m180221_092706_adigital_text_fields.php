<?php

namespace craft\contentmigrations;

use Craft;
use craft\db\Migration;

/**
 * m180221_092706_adigital_text_fields migration.
 */
class m180221_092706_adigital_text_fields extends Migration
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
		 * Group: Text
		 */
		
		$textGroup = null;
		$groups = Craft::$app->getFields()->getAllGroups();
		foreach($groups as $group) {
			if ($group->name == "Text") {
				$textGroup = $group;
			}
		}
		
		if (is_null($textGroup)) {
			$textGroup = new \craft\models\FieldGroup();
			$textGroup->name = "Text";
			Craft::$app->getFields()->saveGroup($textGroup);
		}
		
		/////////////////////////////////////////////
		////////////  PLAIN TEXT FIELDS  ////////////
		/////////////////////////////////////////////
		/**
		 * Fields: Heading, Excerpt, Caption, Link
		 * 
		 * Options:
		 * 		multiLine: 1
		 * 		columnType: string, text, mediumtext
		 */
		
		if (is_null(Craft::$app->getFields()->getFieldByHandle("heading"))) {
			$headingField = Craft::$app->getFields()->createField([
			    "type" => "craft\\fields\\PlainText",
			    "groupId" => $textGroup->id,
			    "name" => "Heading",
			    "handle" => "heading",
			    "instructions" => "This can be used as an override, please leave blank if you wish for the title to be used instead",
			    "translationMethod" => "none",
			    "translationKeyFormat" => NULL,
			    "settings" => [
			        "placeholder" => "Type here...",
			        "charLimit" => "",
			        "multiline" => "",
			        "initialRows" => "4",
			        "columnType" => "string"
			    ]
			]);
			Craft::$app->getFields()->saveField($headingField);
		}
		
		if (is_null(Craft::$app->getFields()->getFieldByHandle("excerpt"))) {
			$excerptField = Craft::$app->getFields()->createField([
			    "type" => "craft\\fields\\PlainText",
			    "groupId" => $textGroup->id,
			    "name" => "Excerpt",
			    "handle" => "excerpt",
			    "instructions" => "A brief intro used to entice the reader to click into the post and read more",
			    "translationMethod" => "none",
			    "translationKeyFormat" => NULL,
			    "settings" => [
			        "placeholder" => "Type here...",
			        "charLimit" => "",
			        "multiline" => "",
			        "initialRows" => "4",
			        "columnType" => "text"
			    ]
			]);
			Craft::$app->getFields()->saveField($excerptField);
		}
		
		if (is_null(Craft::$app->getFields()->getFieldByHandle("caption"))) {
			$captionField = Craft::$app->getFields()->createField([
			    "type" => "craft\\fields\\PlainText",
			    "groupId" => $textGroup->id,
			    "name" => "Caption",
			    "handle" => "caption",
			    "instructions" => "A description of the image used for title and alt tags. Leave blank if you want the image name to be used instead",
			    "translationMethod" => "none",
			    "translationKeyFormat" => NULL,
			    "settings" => [
			        "placeholder" => "Type here...",
			        "charLimit" => "",
			        "multiline" => "",
			        "initialRows" => "4",
			        "columnType" => "text"
			    ]
			]);
			Craft::$app->getFields()->saveField($captionField);
		}
		
		if (is_null(Craft::$app->getFields()->getFieldByHandle("link"))) {
			$linkField = Craft::$app->getFields()->createField([
			    "type" => "craft\\fields\\PlainText",
			    "groupId" => $textGroup->id,
			    "name" => "Link",
			    "handle" => "buttonLink",
			    "instructions" => "Please enter a full url starting with http:// or https://",
			    "translationMethod" => "none",
			    "translationKeyFormat" => NULL,
			    "settings" => [
			        "placeholder" => "Type here...",
			        "charLimit" => "",
			        "multiline" => "",
			        "initialRows" => "4",
			        "columnType" => "text"
			    ]
			]);
			Craft::$app->getFields()->saveField($linkField);
		}
		
		/////////////////////////////////////////////
		/////////////  REDACTOR FIELDS  /////////////
		/////////////////////////////////////////////
		/**
		 * Fields: Body, Additional Info
		 * 
		 * Options:
		 * 		redactorConfig: Simple.json, Standard.json
		 * 		availableVolumes: *, [1]
		 * 		cleanupHtml: 1
		 * 		purifyHtml: 1
		 * 		purifierConfig: ""
		 * 		columnType: text, mediumtext
		 */
		
		if (is_null(Craft::$app->getFields()->getFieldByHandle("body"))) {
			$bodyField = Craft::$app->getFields()->createField([
			    "type" => "craft\\redactor\\Field",
			    "groupId" => $textGroup->id,
			    "name" => "Body",
			    "handle" => "body",
			    "instructions" => "The bulk of the text for this entry should be entered here. Formatting for titles, lists, etc can be added using the bar along the top of this field",
			    "translationMethod" => "none",
			    "translationKeyFormat" => NULL,
			    "settings" => [
			        "redactorConfig" => "",
			        "availableVolumes" => "*",
			        "cleanupHtml" => 1,
			        "purifyHtml" => 1,
			        "purifierConfig" => "",
			        "columnType" => "text"
			    ]
			]);
			Craft::$app->getFields()->saveField($bodyField);
		}
		
		if (is_null(Craft::$app->getFields()->getFieldByHandle("additionalInfo"))) {
			$additionalInfoField = Craft::$app->getFields()->createField([
			    "type" => "craft\\redactor\\Field",
			    "groupId" => $textGroup->id,
			    "name" => "Additional Info",
			    "handle" => "additionalInfo",
			    "instructions" => "Some additional information can be added here to appear lower down the page. Formatting for titles, lists, etc can be added using the bar along the top of this field",
			    "translationMethod" => "none",
			    "translationKeyFormat" => NULL,
			    "settings" => [
			        "redactorConfig" => "",
			        "availableVolumes" => "*",
			        "cleanupHtml" => 1,
			        "purifyHtml" => 1,
			        "purifierConfig" => "",
			        "columnType" => "text"
			    ]
			]);
			Craft::$app->getFields()->saveField($additionalInfoField);
		}
	
	    return true;
    }

    /**
     * @inheritdoc
     */
    public function safeDown()
    {
        $heading = Craft::$app->getFields()->getFieldByHandle("heading");
        if (!is_null($heading)) {
	        Craft::$app->getFields()->deleteFieldById($heading->id);
        }
        $excerpt = Craft::$app->getFields()->getFieldByHandle("excerpt");
        if (!is_null($excerpt)) {
	        Craft::$app->getFields()->deleteFieldById($excerpt->id);
        }
        $caption = Craft::$app->getFields()->getFieldByHandle("caption");
        if (!is_null($caption)) {
	        Craft::$app->getFields()->deleteFieldById($caption->id);
        }
        $link = Craft::$app->getFields()->getFieldByHandle("link");
        if (!is_null($link)) {
	        Craft::$app->getFields()->deleteFieldById($link->id);
        }
        $body = Craft::$app->getFields()->getFieldByHandle("body");
        if (!is_null($body)) {
	        Craft::$app->getFields()->deleteFieldById($body->id);
        }
        $additionalInfo = Craft::$app->getFields()->getFieldByHandle("additionalInfo");
        if (!is_null($additionalInfo)) {
	        Craft::$app->getFields()->deleteFieldById($additionalInfo->id);
        }
        
        $groups = Craft::$app->getFields()->getAllGroups();
		foreach($groups as $group) {
			if (count(Craft::$app->getFields()->getFieldsByGroupId($group->id)) == 0 && $group->name == "Text") {
				Craft::$app->getFields()->deleteGroupById($group->id);
			}
		}
                
        return true;
    }
}
