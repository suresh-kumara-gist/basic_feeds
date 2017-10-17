# basic_feeds

Total time I took : 15 hour

Requirement is only with respect to node entity type and page node type to server as a json response. But I have generalized it i.e. For all Entity type and bundle type level. 

1. clone the module to https://github.com/suresh-kumara-gist/basic_feeds.git to modules/contrib directory.
2. Enable the module using drush/drupal console. drush en -y basic_feeds
3. In module page click on configure in basic feeds module row  or else  go to /admin/config/system/site-information
4. In BASIC FEEDS INFORMATION field set
   enter site api key. site api key any random string without spaces or else click on generate api button to generate random string of length 30.
   select entity types, if not selected then no restriction on entity type level.
   select bundle typee, if not select then no restriction on bundle level.

go to browser and try to access http://yourdomain.com/page_json/{repalce_siteapikey}/{repalce_entity_type}/{repalce_entity_id} to get json response.


1. link refered for overriding core form http://www.jaypan.com/tutorial/drupal-8-extending-core-configuration-extending-core-forms-and-overriding-core-routes
2. https://drupal.stackexchange.com/questions/191419/drupal-8-node-serialization-to-json  : converting entity to json array
3. https://drupal.stackexchange.com/questions/188644/how-do-i-get-the-content-type-of-a-node-entity-from-the-entity-object : getting bundle name from entity object
4. https://www.drupal.org/docs/8/api/routing-system/access-checking-on-routes : implemented simple access check

5. used drupal console to generate module , generate route alter server , generate controller


End output:

![Alt text](/basic_feeds/Basic site settings ipassio.png?raw=true "Optional Title")  
![Alt text](/basic_feeds/jsonoutput.png?raw=true "Optional Title")  
