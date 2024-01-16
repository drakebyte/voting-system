# Test Project

Test project for senior dev

## Requirements

- [PHP](http://php.net/) >= 7.2
- [WordPress](https://wordpress.org/) >= 5.0
- Composer
- Node.js
- Npm
- Git

## Before activation
- Development only:
  - run `composer install`
  - run `npm install`
  - run `npm run build`
- Go to WP Admin and make sure the plugin is enabled and all necessary plugins are installed and activated
- composer and other dev files are not needed for distro package, we include the vendors in the final package

### Documentation

This theme uses a few frameworks and packages. Here are some useful links for you to get up to speed on the most
important ones. I recommend going through them.

- [WordPress PHP Coding Standards](https://make.wordpress.org/core/handbook/best-practices/coding-standards/php/): the
  PHP style and formatting is validated against these standards.
- [Sass Guidelines](https://sass-guidelin.es/): the SCSS syntax and formatting is validated according to these
  guidelines. In general, the entire guide is very useful, but there are some things we handle differently (excluding
  the syntax and formatting).
