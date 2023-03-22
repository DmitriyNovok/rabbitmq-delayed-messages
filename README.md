# rabbitmq-delayed-messages
Delayed messaging for RabbitMQ on PHP

# Installation
- Requires: php 8.1, Composer, [RabbitMQ Delayed Message Plugin](https://github.com/DmitriyNovok/rabbitmq-delayed-message-exchange), Erlang 23.2 or later versions, same as RabbitMQ 3.8.16+.
- After clone this repo move to directory and install all requires dependencies: run command `composer install`
- [Download a Binary Build](https://github.com/rabbitmq/rabbitmq-delayed-message-exchange/releases), the .ez file must be placed into a node's plugins directory and be readable by the effective user of the RabbitMQ process.
- To find out what the plugins directory is, use rabbitmq-plugins directories: run in command line `rabbitmq-plugins directories -s`
- After download binary build move his to Plugin archives directory the .ez file.

# Enabling the Plugin
- Run in command line `rabbitmq-plugins enable rabbitmq_delayed_message_exchange`
- Then check that the plugin is indeed activated. Run command `rabbitmq-plugins list`
- If plugin activated successfully it will appear in the list of plugins with a flag `[E*] rabbitmq_delayed_message_exchange`

# Example Usage
- Open two windows in your command line.
- In first window run command `php worker.php`, then in second window run command `php send.php`

# Disabling the Plugin
- You can disable this plugin by calling `rabbitmq-plugins disable rabbitmq_delayed_message_exchange` but note that ALL DELAYED MESSAGES THAT HAVEN'T BEEN DELIVERED WILL BE LOST.

# LICENSE
See the LICENSE file.