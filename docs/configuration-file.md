# The mysterious .phuntime.php file

File with this name in your task root is used to configure runtime integration with your app. By using this file, you can
override the handler file, pass event dispatcher to support non-http event handling etc. 
By default phuntime tries to find this file first. If not found, then depending on used runtime:
- fpm version will assume that there is no additional config required, and will use the handler value as the script to execute;
- default version will check if there is any configuration returned in your handler file. If yes then it will use them, otherwise 
he will use default values.

## Config nodes reference:

### `handler` - string
Allows to override your `handler` file passed in your function definition. Path must be relative to your task root.
