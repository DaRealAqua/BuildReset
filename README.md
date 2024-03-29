# BuildReset
### Description
BuildReset is a plugin that makes the blocks placed on the ground disappear after a certain time from the world where it is placed. You can add in which worlds the build reset should work, after how long time the blocks to disappear from the world and whether the particles of the respective block appear or not. If the server shuts down, all blocks that have been saved will be automatically deleted.

### Features
- configurable config
- support worlds
- particle
- countdown

### Installation
To install BuildReset, simply follow these steps:

Download the latest version of BuildReset from the Releases page on GitHub / poggit.
Place the downloaded .phar file into your PocketMine-MP plugins directory.
Start your PocketMine-MP server and enjoy.

### Configuration
ResetBuild can be configured by editing the ``config.yml`` file in the plugin's directory or using the command /buildreset. Here you can see what can be configured:
```php
# Build Reset Configuration
# Author: @DaRealAqua
# Twitter: @DaRealAqua_

# Worlds where build reset is enabled
enabled_worlds:
  - world

# Sets the countdown for when blocks should disappear after being broken. Default is 5 seconds.
despawn_countdown: 5

# Determines whether to show particles when blocks despawn after being broken. Default is true.
despawn_particle: true
```

### Contributing
If you find a bug or want to help improve the plugin, join my discord server and make a suggestion.

### License
BuildReset is released under the Apache license. See the LICENSE file for more information.

### Support
If you need help with BuildReset, you can contact me on my discord server [AquaDevs](https://discord.gg/VFFzjceP6E) or create an issue on the GitHub repository.
