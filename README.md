# SnceEzPublishLegacyUtilityBundle

EzPublishLegacyUtilityBundle is an extension for eZ Publish 5+ helping the development

## Installation

1) To install EzPublishLegacyUtilityBundle run the following command

```
$ php composer.phar require sncegroup/ezpublish-legacy-utility-bundle
```

2) Enable EzSystemsCommentsBundle in the kernel

```
// ezpublish/EzPublishKernel.php

public function registerBundles()
{
    $bundles = array(
        // ...
        new Snce\EzPublishLegacyUtilityBundle\SnceEzPublishLegacyUtilityBundle(),
    );
}
```

## Composer scripts

### Legacy DB settings update

Main purpose of the script is to avoid ```ezpublish_legacy/settings/override/site.ini.append.php``` versioning. The script will replace the DB infos using the Symfony parameters

#### Enable the script

1) Add the script to your Composer post-install-cmd and post-update-cmd (After the Incenteev\\ParameterHandler\\ScriptHandler::buildParameters script)

```
"post-install-cmd": [
           // ...
           "Snce\\EzPublishLegacyUtilityBundle\\Composer\\ScriptHandler::siteIniUpdate"
       ]
```

```
"post-update-cmd": [
           // ...
           "Snce\\EzPublishLegacyUtilityBundle\\Composer\\ScriptHandler::siteIniUpdate"
       ]
```

3) Add the extra parameters, replacing the parameters-map values with your Symfony parameters

```
"extra": {
           "ezpublish-legacy-utility":{
               "parameters-file": "ezpublish/config/parameters.yml",
               "legacy-site_ini": "ezpublish_legacy/settings/override/site.ini.append.php",
               "legacy-site_ini-dist": "ezpublish_legacy/settings/override/site.ini.append.php.dist",
               "parameters-map": {
                   "Server": "db_host",
                   "Port": "db_port",
                   "User": "db_user",
                   "Password": "db_password",
                   "Database": "db_dbname"
               }
           }
       }
```


## License

See [License file](LICENSE)