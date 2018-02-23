<?php

namespace craft\contentmigrations;

use Craft;
use craft\db\Migration;

/**
 * m180221_092707_adigital_entries_fields migration.
 */
class m180221_092707_adigital_entries_fields extends Migration
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
		 * Group: Entries
		 */
		
		$entriesGroup = null;
		$groups = Craft::$app->getFields()->getAllGroups();
		foreach($groups as $group) {
			if ($group->name == "Entries") {
				$entriesGroup = $group;
			}
		}
		
		if (is_null($entriesGroup)) {
			$entriesGroup = new \craft\models\FieldGroup();
			$entriesGroup->name = "Entries";
			Craft::$app->getFields()->saveGroup($entriesGroup);
		}
		
		/////////////////////////////////////////////
		/////////////  ENTRIES FIELDS  //////////////
		/////////////////////////////////////////////
		/**
		 * Fields: Related
		 * 
		 * Options:
		 * 		sources: *, [section:2, singles]
		 */
		
		if (is_null(Craft::$app->getFields()->getFieldByHandle("related"))) {
			$relatedField = Craft::$app->getFields()->createField([
			    "type" => "craft\\fields\\Entries",
			    "groupId" => $entriesGroup->id,
			    "name" => "Related",
			    "handle" => "related",
			    "instructions" => "Please choose either a single entry or multiple entries to link to from this entry.",
			    "translationMethod" => "none",
			    "translationKeyFormat" => NULL,
			    "settings" => [
			        "sources" => "*",
			        "limit" => "",
			        "selectionLabel" => ""
			    ]
			]);
			Craft::$app->getFields()->saveField($relatedField);
		}
	
	    return true;
    }

    /**
     * @inheritdoc
     */
    public function safeDown()
    {
        $related = Craft::$app->getFields()->getFieldByHandle("related");
        if (!is_null($related)) {
	        Craft::$app->getFields()->deleteFieldById($related->id);
        }
        
        $groups = Craft::$app->getFields()->getAllGroups();
		foreach($groups as $group) {
			if (count(Craft::$app->getFields()->getFieldsByGroupId($group->id)) == 0 && $group->name == "Entries") {
				Craft::$app->getFields()->deleteGroupById($group->id);
			}
		}
        
        return true;
    }
}
