<?php
namespace craft\contentmigrations;
use Craft;
use craft\models\Section;
use craft\models\Section_SiteSettings;
use craft\db\Migration;
/**
 * m180108_154605_news migration.
 */
class m180323_132831_adigital_pages extends Migration
{
    /**
     * @inheritdoc
     */
    public function safeUp()
    {
        $section = new Section();
        $section->name = 'Pages';
        $section->type = 'structure';
        $section->handle = 'pages';
        $section->enableVersioning = true;
        $allSiteSettings = [];
        foreach (Craft::$app->getSites()->getAllSites() as $site) {
            $siteSettings = new Section_SiteSettings();
            $siteSettings->siteId = $site->id;
            $siteSettings->hasUrls = true;
            $siteSettings->uriFormat = '{slug}';
            $siteSettings->template = 'pages/_entry';
            $allSiteSettings[$site->id] = $siteSettings;
        }
        $section->setSiteSettings($allSiteSettings);
        // Setting the saveSection runvalidation to false helps throw debugging errors
        $status = Craft::$app->getSections()->saveSection($section);
        return $status;
    }
    /**
     * @inheritdoc
     */
    public function safeDown()
    {
        // Get the section
        $section = Craft::$app->sections->getSectionByHandle('pages');
        // Delete it
        $success = Craft::$app->sections->deleteSectionById($section->id);
        return $success;
    }
}