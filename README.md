This plugin is created for a (private repository) project of mine. I thought the problem was annoying, so I open-sourced it incase anyone encounters the same problem. It's just a quick fix so don't expect anything professional. You can use it however you want.

## The Problem
When using wordpress REST API, I encountered a problem where you can not add custom fields value (post meta) to the post directly. Forums suggest that you should 'register' them but that's not always possible since you may not be knowing what fields are avaible. However it is possible with a small plugin that 'extends' the REST API.

## The Solution 
Just upload the plugin zip file to your site and activate the plugin (or addon idk what's it called.).
This plugin offers 3 new endpoints to the REST API.

# Endpoint 1
yoursite.com/wp-json/multipost/v1/version/ [GET] [NO AUTH]: This returns version for the plugin, used for internal validations.

# Endpoint 2
yoursite.com/wp-json/multipost/v1/custom_fields/ [GET] [NO AUTH]: This returns an array for all avaible custom fields.

# Endpoint 3
yoursite.com/wp-json/multipost/v1/set_custom_fields [POST] [AUTH]: This adds the given meta data (custom fields) to given post id. Returns true no matter what.
Arguments:
> id: Post id you want to add
> fields: An array, containing 'custom field name' & 'the value' key and value pairs.
