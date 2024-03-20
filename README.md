<!-- PROJECT BADGES -->
<div align="center">

[![Poggit CI][poggit-ci-badge]][poggit-ci-url]
<!--[![Poggit Version][poggit-version-badge]][poggit-release-url]
[![Poggit Downloads][poggit-downloads-badge]][poggit-release-url]-->
[![Stars][stars-badge]][stars-url]
[![License][license-badge]][license-url]

</div>


<!-- PROJECT LOGO -->
<br />
<div align="center">
  <img src="https://raw.githubusercontent.com/presentkim-pm/PluginReplacer/main/assets/icon.png" alt="Logo" width="80" height="80">
  <h3>PluginReplacer</h3>
  <p align="center">
    An plugin that replace the plugin file when the server stop!

[View in Poggit][poggit-ci-url] · [Report a bug][issues-url] · [Request a feature][issues-url]

  </p>
</div>


<!-- ABOUT THE PROJECT -->

## About The Project

Have you ever encountered problems while updating plugins while the server was running?
Or do you dislike waiting for the server to shut down to update plugins?

Then use this plugin!
This plugin automatically replaces plugin files in the `plugin_replace` folder when the server shuts down!

#### Usage

1. Place the plugin phar file to be replaced in the `plugin_replace` folder (In the same directory as `plugin`
   or `plugin_data`)
2. Just reboot the server!

> It works only phar file, not folder.

> Don't care about the file name because plugin analyze and process the Phar file.  
> Instead, the plugin file will be renamed '{Name}_v{version}.phar'

> exmaple) When server directory is configured as below,
> ```bash
> . # pmmp directory
> ├── plugin_replace
> │   └── ExamplePlugin_v1.1.0.phar
> │
> ├── plugins
> │   ├── Another_v1.0.0.phar
> │   ├── ExamplePlugin_v1.0.0.phar
> │   └── PluginReplacer_v1.0.0.phar
> │
> └── ...
> ```
>
> Then, PluginReplacer will be replace the `ExamplePlugin_v1.0.0.phar` to `ExamplePlugin_v1.1.0.phar`
> when the server stops.
>
> ```bash
> . # pmmp directory
> ├── plugin_replace
> │   └── (nothing)
> │
> ├── plugins
> │   ├── Another_v1.0.0.phar
> │   ├── ExamplePlugin_v1.1.0.phar
> │   └── PluginReplacer_v1.0.0.phar
> │
> └── ...
> ```

**-----**

## Target software:

This plugin officially only works with [`Pocketmine-MP`](https://github.com/pmmp/PocketMine-MP/).

-----

## Installation

1) Download `.phar` from [here][poggit-ci-url]
2) Move downloaded `.phar` file to server's **/plugins/** folder
3) Restart the server

-----

## Downloads

> **All released versions [here][poggit-release-url]**

> **All built versions [here][poggit-ci-url]**

-----

## License

Distributed under the **LGPL 3.0**. See [LICENSE][license-url] for more information


[poggit-ci-badge]: https://poggit.pmmp.io/ci.shield/presentkim-pm/PluginReplacer/PluginReplacer?style=for-the-badge

[poggit-version-badge]: https://poggit.pmmp.io/shield.api/PluginReplacer?style=for-the-badge

[poggit-downloads-badge]: https://poggit.pmmp.io/shield.dl.total/PluginReplacer?style=for-the-badge

[stars-badge]: https://img.shields.io/github/stars/presentkim-pm/PluginReplacer.svg?style=for-the-badge

[license-badge]: https://img.shields.io/github/license/presentkim-pm/PluginReplacer.svg?style=for-the-badge

[poggit-ci-url]: https://poggit.pmmp.io/ci/presentkim-pm/PluginReplacer/PluginReplacer

[poggit-release-url]: https://poggit.pmmp.io/p/PluginReplacer

[stars-url]: https://github.com/presentkim-pm/PluginReplacer/stargazers

[releases-url]: https://github.com/presentkim-pm/PluginReplacer/releases

[issues-url]: https://github.com/presentkim-pm/PluginReplacer/issues

[license-url]: https://github.com/presentkim-pm/PluginReplacer/blob/main/LICENSE

[project-icon]: https://raw.githubusercontent.com/presentkim-pm/PluginReplacer/main/assets/icon.png
