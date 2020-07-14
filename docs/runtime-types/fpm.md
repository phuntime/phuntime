# `fpm` runtime type

It's just a `php-fpm` compiled on Lambda. Requires very low effort to run as there is no additional class and config required
for HTTP Requests handling.

`phuntime-fpm` runtime assumes, that the file passed in `handler` config key is the front controller of your application,
and it would be sent to fpm as script that have to be executed. There is also a way to override this value by using phuntime
configuration file - see docs/configuration-file.md for details.

## Caveats && things you potentially find useful

- Only HTTP Event are passed to php-fpm. Any event passed to your Event Dispatcher will be running in separate process,
not in PHP-FPM one. 
- You do not have to download phuntime vendors in your app to return RuntimeConfiguration objects - you can return a 
array filled with config nodes and values, and phuntime will convert them to configuration object automatically;

## Use Cases

- "I just want to deploy my app on Lambda"

