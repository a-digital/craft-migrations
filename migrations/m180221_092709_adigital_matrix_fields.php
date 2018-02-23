<?php

namespace craft\contentmigrations;

use Craft;
use craft\db\Migration;

/**
 * m180221_092709_adigital_matrix_fields migration.
 */
class m180221_092709_adigital_matrix_fields extends Migration
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
		 * Group: Matrix
		 */
		
		$matrixGroup = null;
		$groups = Craft::$app->getFields()->getAllGroups();
		foreach($groups as $group) {
			if ($group->name == "Matrix") {
				$matrixGroup = $group;
			}
		}
		
		if (is_null($matrixGroup)) {
			$matrixGroup = new \craft\models\FieldGroup();
			$matrixGroup->name = "Matrix";
			Craft::$app->getFields()->saveGroup($matrixGroup);
		}
		
		$mainUploadsVolume = Craft::$app->volumes->getVolumeByHandle("mainUploads");
		$mainUploadsFolderTree = Craft::$app->assets->getFolderTreeByVolumeIds([$mainUploadsVolume->id]);
		$mainUploadsFolder = $mainUploadsFolderTree[0]->id;
		$galleryVolume = Craft::$app->volumes->getVolumeByHandle("gallery");
		$galleryFolderTree = Craft::$app->assets->getFolderTreeByVolumeIds([$galleryVolume->id]);
		$galleryFolder = $galleryFolderTree[0]->id;
				
		/////////////////////////////////////////////
		//////////////  MATRIX FIELDS  //////////////
		/////////////////////////////////////////////
		/**
		 * Fields: Slider, Gallery Block, Content Block
		 *
		 * Blocks: 
		 * 		Slider: Slides
		 * 		Gallery Block: Images, Description
		 * 		Content Block: Content, Text, Heading, Image, Video, Gallery, Quote
		 *
		 * Options:
		 * 		PlainText:
		 *			multiLine: 1
		 *			columnType: string, text, mediumtext
		 * 		Redactor:
		 *			redactorConfig: Simple.json, Standard.json
		 * 			availableVolumes: *, 1
		 * 			cleanupHtml: 1
		 * 			purifyHtml: 1
		 * 			purifierConfig: ""
		 * 			columnType: text, mediumtext
		 * 		Dropdown:
		 *			options: [[label: "", value: "", default: 1],[label: "", value: "", default: 1]]
		 * 		Assets:
		 *			useSingleFolder: 1
		 * 			sources: *, [folder:1]
		 * 			defaultUploadLocationSource: folder:1
		 * 			defaultUploadLocationSubpath: "path/to/subfolder"
		 * 			singleUploadLocationSource: folder:1
		 * 			singleUploadLocationSubpath: "path/to/subfolder"
		 * 			restrictFiles: 1
		 * 			allowedKinds: [access, audio, compressed, excel, flash, html, illustrator, image, javascript, json, pdf, photoshop, php, powerpoint, text, video, word, xml]
		 * 			viewMode: list, large
		 *
		 * Error Checking:
		 * 		Craft::$app->matrix->validateFieldSettings($matrixField);
		 * 		echo "<pre>";
		 * 		print_r($matrixField);
		 * 		exit;
		 */
		
		if (is_null(Craft::$app->getFields()->getFieldByHandle("slider"))) {
			$homeBlock = new \craft\fields\Matrix([
			    "groupId" => $matrixGroup->id,
			    "name" => "Slider",
			    "handle" => "slider",
			    "instructions" => "",
			    "translationMethod" => "none",
			    "translationKeyFormat" => NULL,
		        "minBlocks" => "",
		        "maxBlocks" => "",
		        "blockTypes" => [
			        new \craft\models\MatrixBlockType([
				        "name" => "Slides",
				        "handle" => "slides",
				        "fields" => [
					        new \craft\fields\Assets([
						        "name" => "Image",
						        "handle" => "image",
						        "instructions" => "Please choose or upload an image for this slide",
						        "required" => false,
						        "useSingleFolder" => 1,
								"singleUploadLocationSource" => "folder:".$mainUploadsFolder,
								"singleUploadLocationSubpath" => "",
								"restrictFiles" => "",
								"allowedKinds" => ["access", "audio", "compressed", "excel", "flash", "html", "illustrator", "image", "javascript", "json", "pdf", "photoshop", "php", "powerpoint", "text", "video", "word", "xml"],
								"limit" => 1,
								"viewMode" => "list",
								"selectionLabel" => ""
					        ]),
					        new \craft\fields\PlainText([
						        "name" => "Button Text",
						        "handle" => "buttonText",
						        "instructions" => "Test entered here will appear as a button overlay ontop of the slide",
						        "required" => false,
						        "placeholder" => "Type here...",
						        "charLimit" => "",
						        "multiline" => "",
						        "initialRows" => "4",
						        "columnType" => "string"
					        ]),
					        new \craft\fields\PlainText([
						        "name" => "Link",
						        "handle" => "buttonLink",
						        "instructions" => "Please enter a full url starting with http:// or https://",
						        "required" => false,
						        "placeholder" => "Type here...",
						        "charLimit" => "",
						        "multiline" => "",
						        "initialRows" => "4",
						        "columnType" => "string"
					        ])
				        ]
			        ])
		        ]
			]);
			Craft::$app->getFields()->saveField($homeBlock);
		}
		
		if (is_null(Craft::$app->getFields()->getFieldByHandle("galleryBlock"))) {
			$galleryBlock = new \craft\fields\Matrix([
			    "groupId" => $matrixGroup->id,
			    "name" => "Gallery Block",
			    "handle" => "galleryBlock",
			    "instructions" => "",
			    "translationMethod" => "none",
			    "translationKeyFormat" => NULL,
		        "minBlocks" => "",
		        "maxBlocks" => "",
		        "blockTypes" => [
			        new \craft\models\MatrixBlockType([
				        "name" => "Images",
				        "handle" => "images",
				        "fields" => [
					        new \craft\fields\Assets([
						        "name" => "Image",
						        "handle" => "image",
						        "instructions" => "Choose or upload an individual image to be added to the gallery",
						        "required" => false,
						        "useSingleFolder" => 1,
								"singleUploadLocationSource" => "folder:".$galleryFolder,
								"singleUploadLocationSubpath" => "",
								"restrictFiles" => "",
								"allowedKinds" => ["access", "audio", "compressed", "excel", "flash", "html", "illustrator", "image", "javascript", "json", "pdf", "photoshop", "php", "powerpoint", "text", "video", "word", "xml"],
								"limit" => 1,
								"viewMode" => "list",
								"selectionLabel" => ""
					        ]),
					        new \craft\fields\PlainText([
						        "name" => "Caption",
						        "handle" => "caption",
						        "instructions" => "A description of the image used for title and alt tags. Leave blank if you want the image name to be used instead",
						        "required" => false,
						        "placeholder" => "Type here...",
						        "charLimit" => "",
						        "multiline" => "",
						        "initialRows" => "4",
						        "columnType" => "string"
					        ]),
					        new \craft\fields\PlainText([
						        "name" => "Link",
						        "handle" => "buttonLink",
						        "instructions" => "Please enter a full url starting with http:// or https://",
						        "required" => false,
						        "placeholder" => "Type here...",
						        "charLimit" => "",
						        "multiline" => "",
						        "initialRows" => "4",
						        "columnType" => "string"
					        ])
				        ]
			        ]),
			        new \craft\models\MatrixBlockType([
				        "name" => "Description",
				        "handle" => "description",
				        "fields" => [
					        new \craft\redactor\Field([
						        "name" => "Text",
						        "handle" => "text",
						        "instructions" => "Text for the gallery that can sit between images to separate the content should be entered here. Formatting for titles, lists, etc can be added using the bar along the top of this field",
						        "required" => false,
						        "redactorConfig" => "",
						        "availableVolumes" => "*",
						        "cleanupHtml" => 1,
						        "purifyHtml" => 1,
						        "purifierConfig" => "",
						        "columnType" => "text"
					        ])
				        ]
			        ])
		        ]
			]);
			Craft::$app->getFields()->saveField($galleryBlock);
		}
		
		if (is_null(Craft::$app->getFields()->getFieldByHandle("contentBlock"))) {
			$contentBlock = new \craft\fields\Matrix([
			    "groupId" => $matrixGroup->id,
			    "name" => "Content Block",
			    "handle" => "contentBlock",
			    "instructions" => "",
			    "translationMethod" => "none",
			    "translationKeyFormat" => NULL,
		        "minBlocks" => "",
		        "maxBlocks" => "",
		        "blockTypes" => [
			        new \craft\models\MatrixBlockType([
				        "name" => "Content",
				        "handle" => "contentBlock",
				        "fields" => [
					        new \craft\redactor\Field([
						        "name" => "Content",
						        "handle" => "mainContent",
						        "instructions" => "Large bulk text should be entered here. Formatting for titles, lists, etc can be added using the bar along the top of this field",
						        "required" => false,
						        "redactorConfig" => "",
						        "availableVolumes" => "*",
						        "cleanupHtml" => 1,
						        "purifyHtml" => 1,
						        "purifierConfig" => "",
						        "columnType" => "text"
					        ])
				        ]
			        ]),
			        new \craft\models\MatrixBlockType([
				        "name" => "Text",
				        "handle" => "text",
				        "fields" => [
					        new \craft\fields\PlainText([
						        "name" => "Text",
						        "handle" => "text",
						        "instructions" => "Unformatted single line text should be entered here",
						        "required" => false,
						        "placeholder" => "Type here...",
						        "charLimit" => "",
						        "multiline" => "",
						        "initialRows" => "4",
						        "columnType" => "string"
					        ])
				        ]
			        ]),
			        new \craft\models\MatrixBlockType([
				        "name" => "Heading",
				        "handle" => "heading",
				        "fields" => [
					        new \craft\fields\PlainText([
						        "name" => "Heading",
						        "handle" => "heading",
						        "instructions" => "A heading to go above another block type e.g. 'Gallery'",
						        "required" => false,
						        "placeholder" => "Type here...",
						        "charLimit" => "",
						        "multiline" => "",
						        "initialRows" => "4",
						        "columnType" => "string"
					        ])
				        ]
			        ]),
			        new \craft\models\MatrixBlockType([
				        "name" => "Image",
				        "handle" => "image",
				        "fields" => [
					        new \craft\fields\Assets([
						        "name" => "Image",
						        "handle" => "image",
						        "instructions" => "Choose or upload a single image which can be used to separate the content",
						        "required" => false,
						        "useSingleFolder" => 1,
								"singleUploadLocationSource" => "folder:".$mainUploadsFolder,
								"singleUploadLocationSubpath" => "",
								"restrictFiles" => "",
								"allowedKinds" => ["access", "audio", "compressed", "excel", "flash", "html", "illustrator", "image", "javascript", "json", "pdf", "photoshop", "php", "powerpoint", "text", "video", "word", "xml"],
								"limit" => 1,
								"viewMode" => "list",
								"selectionLabel" => ""
					        ]),
					        new \craft\fields\PlainText([
						        "name" => "Caption",
						        "handle" => "caption",
						        "instructions" => "A description of the image used for title and alt tags. Leave blank if you want the image name to be used instead",
						        "required" => false,
						        "placeholder" => "Type here...",
						        "charLimit" => "",
						        "multiline" => "",
						        "initialRows" => "4",
						        "columnType" => "string"
					        ]),
					        new \craft\fields\Dropdown([
						        "name" => "Position",
						        "handle" => "position",
						        "instructions" => "The alignment of the image and how it should be displayed, the live preview button can be used to test this",
						        "required" => false,
						        "options" => [
						        	[
										"label" => "Center",
										"value" => "center",
										"default" => 1
									],[
						        		"label" => "Left",
						        		"value" => "left",
						        		"default" => ""
						        	],[
										"label" => "Right",
										"value" => "right",
										"default" => ""
									],[
										"label" => "Full",
										"value" => "full",
										"default" => ""
									]
						        ]
					        ])
				        ]
			        ]),
			        new \craft\models\MatrixBlockType([
				        "name" => "Video",
				        "handle" => "video",
				        "fields" => [
					        new \craft\fields\PlainText([
						        "name" => "Video",
						        "handle" => "video",
						        "instructions" => "Please enter the iframe code here",
						        "required" => false,
						        "placeholder" => "Type here...",
						        "charLimit" => "",
						        "multiline" => "",
						        "initialRows" => "4",
						        "columnType" => "string"
					        ]),
					        new \craft\fields\PlainText([
						        "name" => "Caption",
						        "handle" => "caption",
						        "instructions" => "A description of the video",
						        "required" => false,
						        "placeholder" => "Type here...",
						        "charLimit" => "",
						        "multiline" => "",
						        "initialRows" => "4",
						        "columnType" => "string"
					        ]),
					        new \craft\fields\Dropdown([
						        "name" => "Position",
						        "handle" => "position",
						        "instructions" => "The alignment of the image and how it should be displayed, the live preview button can be used to test this",
						        "required" => false,
						        "options" => [
						        	[
										"label" => "Center",
										"value" => "center",
										"default" => 1
									],[
						        		"label" => "Left",
						        		"value" => "left",
						        		"default" => ""
						        	],[
										"label" => "Right",
										"value" => "right",
										"default" => ""
									],[
										"label" => "Full",
										"value" => "full",
										"default" => ""
									]
						        ]
					        ])
				        ]
			        ]),
			        new \craft\models\MatrixBlockType([
				        "name" => "Gallery",
				        "handle" => "gallery",
				        "fields" => [
					        new \craft\fields\Assets([
						        "name" => "Images",
						        "handle" => "images",
						        "instructions" => "Choose or upload multiple images to create a gallery or slider",
						        "required" => false,
						        "useSingleFolder" => 1,
								"singleUploadLocationSource" => "folder:".$galleryFolder,
								"singleUploadLocationSubpath" => "",
								"restrictFiles" => "",
								"allowedKinds" => ["access", "audio", "compressed", "excel", "flash", "html", "illustrator", "image", "javascript", "json", "pdf", "photoshop", "php", "powerpoint", "text", "video", "word", "xml"],
								"limit" => 1,
								"viewMode" => "list",
								"selectionLabel" => ""
					        ])
				        ]
			        ]),
			        new \craft\models\MatrixBlockType([
				        "name" => "Quote",
				        "handle" => "quote",
				        "fields" => [
					        new \craft\redactor\Field([
						        "name" => "Quote",
						        "handle" => "quote",
						        "instructions" => "Enter the full quote you wish to display here",
						        "required" => false,
						        "redactorConfig" => "",
						        "availableVolumes" => "*",
						        "cleanupHtml" => 1,
						        "purifyHtml" => 1,
						        "purifierConfig" => "",
						        "columnType" => "text"
					        ]),
					        new \craft\fields\PlainText([
						        "name" => "Creditation",
						        "handle" => "creditation",
						        "instructions" => "Where has this source has come from e.g. website name, person, magazine, etc",
						        "required" => false,
						        "placeholder" => "Type here...",
						        "charLimit" => "",
						        "multiline" => "",
						        "initialRows" => "4",
						        "columnType" => "string"
					        ]),
					        new \craft\fields\PlainText([
						        "name" => "Link",
						        "handle" => "textLink",
						        "instructions" => "Please enter the full url of this source starting with http:// or https://",
						        "required" => false,
						        "placeholder" => "Type here...",
						        "charLimit" => "",
						        "multiline" => "",
						        "initialRows" => "4",
						        "columnType" => "string"
					        ])
				        ]
			        ])
		        ]
			]);
			Craft::$app->getFields()->saveField($contentBlock);
		}
	
	    return true;
    }

    /**
     * @inheritdoc
     */
    public function safeDown()
    {
        $slider = Craft::$app->getFields()->getFieldByHandle("slider");
        if (!is_null($slider)) {
	        Craft::$app->getFields()->deleteFieldById($slider->id);
        }
        $galleryBlock = Craft::$app->getFields()->getFieldByHandle("galleryBlock");
        if (!is_null($galleryBlock)) {
	        Craft::$app->getFields()->deleteFieldById($galleryBlock->id);
        }
        $contentBlock = Craft::$app->getFields()->getFieldByHandle("contentBlock");
        if (!is_null($contentBlock)) {
	        Craft::$app->getFields()->deleteFieldById($contentBlock->id);
        }
        
        $groups = Craft::$app->getFields()->getAllGroups();
		foreach($groups as $group) {
			if (count(Craft::$app->getFields()->getFieldsByGroupId($group->id)) == 0 && $group->name == "Matrix") {
				Craft::$app->getFields()->deleteGroupById($group->id);
			}
		}
        
        return true;
    }
}
