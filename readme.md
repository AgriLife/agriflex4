# AgriFlex4 (WordPress Theme) by AgriLife Communications
[![Codeship Status for AgriLife/agriflex4/master](https://app.codeship.com/projects/084f08f0-b3b0-0136-4070-6a06c3a18961/status?branch=master)](https://app.codeship.com/projects/311058)

## WordPress Requirements

1. Genesis theme
2. Advanced Custom Fields 5+ plugin
3. PHP 5.6+, tested with PHP 7.2

## Installation

1. [Download the latest release](https://github.com/agrilife/agriflex4/releases/latest)
2. Upload the theme to your site

## Features

* Responsive layout and navigation for desktop, tablet, and mobile devices
* Multiple page layouts
* Post and page archives that show full or excerpted content, with or without a featured image
* Widget Areas
    * Primary Sidebar: Positioned next to a page's content depending on which page layout it uses

## Development Installation

1. Copy this repo to the desired location.
2. In your terminal, navigate to the plugin location 'cd /path/to/the/plugin'
3. Run "npm start" to configure your local copy of the repo, install dependencies, and build files

## Development Notes

When you stage changes to this repository and initiate a commit, they must pass PHP and Sass linting tasks before they will complete the commit step. Release tasks can only be used by the repository's owners.

## Development Tasks

1. Run "grunt develop" to compile the css when developing the plugin.
3. Run "grunt watch" to automatically compile the css after saving a *.scss file.
3. Run "grunt" to compile the css when publishing the plugin.
5. Run "npm run checkwp" to check PHP files against WordPress coding standards.

## Development Requirements

* Node: http://nodejs.org/
* NPM: https://npmjs.org/
* Ruby: http://www.ruby-lang.org/en/
* Ruby Gems: http://rubygems.org/
