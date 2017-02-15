WP CLI Zip Package
=====================

Zip up themes or folders for delivery elsewhere!

Often times, I’m working on a quick project for a client who just needs the zip folder. Nothing fancy, just in and out ZIP delivery.

[![Build Status](https://travis-ci.org/annedorko/zip-package.svg?branch=master)](https://travis-ci.org/annedorko/zip-package)

Quick links: [Using](#using) | [Installing](#installing) | [Contributing](#contributing)

## Using

`wp zip <plugin|theme> <project-slug>`

The resulting zip folder will be saved to /wp-content/project-slug.zip

More arguments will eventually be accepted to customize the output directory and more. If you have your own parameter to add, simply pull a new branch and let me know what you have in mind! You can submit feature requests under Issues.

## Installing

The official name of this package is `annedorko/zip-package`. I'm still learning how to add this anywhere, so for now you may need to ignore the following installation directions and simply download and install it locally.

Installing this package requires WP-CLI v0.23.0 or greater. Update to the latest stable release with `wp cli update`.

Once you've done so, you can install this package with `wp package install annedorko/zip-package`.

## Contributing

We appreciate you taking the initiative to contribute to this project.

Contributing isn’t limited to just code. We encourage you to contribute in the way that best fits your abilities, by writing tutorials, giving a demo at your local meetup, helping other users with their support questions, or revising our documentation.

### Roadmap

A few of the parameters I will personally want to add over time include:

1. Ability to set destination directory
2. Ability to exclude files or filetypes

### Reporting a bug

Think you’ve found a bug? We’d love for you to help us get it fixed.

Before you create a new issue, you should [search existing issues](https://github.com/annedorko/zip-package/issues?q=label%3Abug%20) to see if there’s an existing resolution to it, or if it’s already been fixed in a newer version.

Once you’ve done a bit of searching and discovered there isn’t an open or fixed issue for your bug, please [create a new issue](https://github.com/annedorko/zip-package/issues/new) with the following:

1. What you were doing (e.g. "When I run `wp post list`").
2. What you saw (e.g. "I see a fatal about a class being undefined.").
3. What you expected to see (e.g. "I expected to see the list of posts.")

Include as much detail as you can, and clear steps to reproduce if possible.

### Creating a pull request

Want to contribute a new feature? Please first [open a new issue](https://github.com/annedorko/zip-package/issues/new) to discuss whether the feature is a good fit for the project.

Once you've decided to commit the time to seeing your pull request through, please follow our guidelines for creating a pull request to make sure it's a pleasant experience:

1. Create a feature branch for each contribution.
2. Submit your pull request early for feedback.
3. Include functional tests with your changes. [Read the WP-CLI documentation](https://wp-cli.org/docs/pull-requests/#functional-tests) for an introduction.
4. Follow the [WordPress Coding Standards](http://make.wordpress.org/core/handbook/coding-standards/).


*This README.md is generated dynamically from the project's codebase using `wp scaffold package-readme` ([doc](https://github.com/wp-cli/scaffold-package-command#wp-scaffold-package-readme)). To suggest changes, please submit a pull request against the corresponding part of the codebase.*
