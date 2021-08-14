# `fpm` runtime type

It's just a `php-fpm` compiled on Lambda. Requires very low effort to run as there is no additional class and config required
for HTTP Requests handling.

`phuntime-fpm` runtime assumes, that the file passed in `handler` config key is the front controller of your application,
and it would be sent to fpm as script that have to be executed. 

## Caveats && things you potentially find useful

- Only HTTP Event are passed to php-fpm. The rest of them will be ignored so far. 
- You do not have to download phuntime vendors to your app - this runtime type requires no additional configuration. Just 
pass phuntime-fpm as a one of your layers and voila.

## Use Cases

- WordPress, eZ Platform 
- "I just want to deploy my app on Lambda"

