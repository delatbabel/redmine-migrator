# redmine-migrator

Migrate a project from redmine instance to redmine instance using the API

## Installation

Just copy the files to a location on your machine and run:

    composer install

then

    ./runme.php

... and follow the instructions.

## Overview

`runme.php` reads configuration from the file config.json and performs the migration.
Configuration is written back to the file config.json as the script progresses, so each
time you run the script, configuration is read back from the file.  To start from scratch,
just delete the config.json file.

## TODO

Turn this into a web application using a framework like Laravel, storing the configuration
in the session.
