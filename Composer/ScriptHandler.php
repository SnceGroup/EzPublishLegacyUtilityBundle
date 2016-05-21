<?php
/**
 * File containing the ScriptHandler class.
 *
 */

namespace Snce\EzPublishLegacyUtilityBundle\Composer;

use Sensio\Bundle\DistributionBundle\Composer\ScriptHandler as DistributionBundleScriptHandler;
use Composer\Script\Event;
use Symfony\Component\Yaml\Parser;

class ScriptHandler extends DistributionBundleScriptHandler
{
    /**
     * Deploy a proper settings/override/site.ini using settings/override/site.ini.dist as a template
     * Only main DB parameters (Host,Port,User,Password,Database) are replaced using the Symfony parameters
     *
     * @param $event Event A instance
     */
    public static function siteIniUpdate(Event $event)
    {
        $extras = $event->getComposer()->getPackage()->getExtra();

        if (!isset($extras['ezpublish-legacy-utility'])) {
            throw new \InvalidArgumentException('The eZ Publish Utility handler needs to be configured through the extra.ezpublish-legacy-utility setting.');
        }

        $configs = $extras['ezpublish-legacy-utility'];

        $yamlParser = new Parser();
        $parameters = $yamlParser->parse(file_get_contents($configs['parameters-file']));

        file_put_contents($configs['legacy-site_ini'], file_get_contents($configs['legacy-site_ini-dist']) );

        $siteIniArray = file($configs['legacy-site_ini']);
        $dbSection = false;
        $siteIniUpdated = array();
        foreach( $siteIniArray as $siteIniRow ){
            $siteIniRow = trim($siteIniRow);

            if( substr($siteIniRow, 0, 1) === '[' ){
                if( $siteIniRow === "[DatabaseSettings]" ){
                    $dbSection = true;
                }else{
                    $dbSection = false;
                }
            }

            if( $dbSection ){
                if( strstr($siteIniRow, '=', true) != false ){
                    $key = strstr($siteIniRow, '=', true);
                    if( array_key_exists($key, $configs['parameters-map'] ) ){
                        $siteIniRow = $key . "=" . $parameters['parameters'][$configs['parameters-map'][$key]];
                    }
                }
            }

            $siteIniUpdated[] = $siteIniRow;
        }


        file_put_contents($configs['legacy-site_ini'], "" );
        foreach( $siteIniUpdated as $siteIniUpdatedRow ){
            file_put_contents($configs['legacy-site_ini'], $siteIniUpdatedRow."\n", FILE_APPEND );
        }

    }
}
