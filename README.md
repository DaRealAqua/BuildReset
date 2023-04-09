# BuildReset
Description
BuildReset is a plugin that makes the blocks placed on the ground disappear after a certain time from the world where it is placed. To change some settings, you can edit config.yml or use the /buildreset command.

Features
- configurable config
- particle
- block countdown
- multiple worlds

Installation
To install BuildReset, simply follow these steps:

Download the latest version of BuildReset from the Releases page on GitHub.
Place the downloaded .phar file into your PocketMine-MP plugins directory.
Start your PocketMine-MP server and enjoy.

Configuration
ResetBuild can be configured by editing the config.yml file in the plugin's directory or using the command /buildreset. Here you can see what can be configured:
```php
# Enable build reset in worlds...
# You can add worlds manually or with the /buildreset command.
worlds:
  - world

# Edit blocks despawn countdown.
# You can manage when the blocks should disappear.
despawn_countdown: 5

# Edit blocks despawn particle.
# You can manage whether to show the block particles or not.
despawn_particle: true
```

Contributing
If you find a bug or want to help improve the plugin, join my discord server and make a suggestion.

License
BuildReset is released under the Apache license. See the LICENSE file for more information.

Support
If you need help with BuildReset, you can contact me on my discord server [AquaDevs](https://discord.gg/VFFzjceP6E) or create an issue on the GitHub repository.
