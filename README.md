# redmine-migrator

This is a command line script to migrate a project from redmine instance to redmine instance
using the API.

Many thanks to Kevin Saliou for this package: https://github.com/kbsali/php-redmine-api

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

* Categories not supported yet
* Target versions not supported yet
* Parent issues not supported yet
* Custom fields not supported yet
* Watchers not supported yet
* is_private not supported yet

## Data Dumps

This information comes from me playing with the API provided by kbsali/php-redmine-api

    //
    // This is what one issue looks like
    //
    [issue] => Array
        (
            [created_on] => 2017-01-03T22:55:53Z
            [tracker] => Array
                (
                    [id] => 4
                    [name] => Requirement
                )

            [updated_on] => 2017-01-08T04:43:31Z
            [author] => Array
                (
                    [id] => 21
                    [name] => NAME
                )

            [journals] => Array
                (
                    [0] => Array
                        (
                            [created_on] => 2017-01-04T03:33:08Z
                            [notes] => BLABLABLA

                            [id] => 3470
                            [details] => Array
                                (
                                    [0] => Array
                                        (
                                            [property] => attachment
                                            [new_value] => Screenshot_20170104_102949.png
                                            [name] => 570
                                        )

                                    [1] => Array
                                        (
                                            [property] => attr
                                            [old_value] => 11
                                            [new_value] => 4
                                            [name] => status_id
                                        )

                                )

                            [user] => Array
                                (
                                    [id] => 3
                                    [name] => NAME
                                )

                        )

                    [1] => Array
                        (
                            [created_on] => 2017-01-08T04:43:32Z
                            [notes] =>
                            [id] => 3492
                            [details] => Array
                                (
                                    [0] => Array
                                        (
                                            [property] => attr
                                            [old_value] => 4
                                            [new_value] => 13
                                            [name] => status_id
                                        )

                                )

                            [user] => Array
                                (
                                    [id] => 3
                                    [name] => NAME
                                )

                        )

                )

            [attachments] => Array
                (
                    [0] => Array
                        (
                            [created_on] => 2017-01-04T03:32:53Z
                            [filename] => Screenshot_20170104_102949.png
                            [author] => Array
                                (
                                    [id] => 3
                                    [name] => NAME
                                )

                            [content_url] => http://redmine.MYSITE.COM/attachments/download/570/Screenshot_20170104_102949.png
                            [id] => 570
                            [description] =>
                            [filesize] => 102517
                        )

                )

            [description] => BLABLABLA
            [id] => 630
            [status] => Array
                (
                    [id] => 13
                    [name] => On Hold
                )

            [start_date] => 2017-01-04
            [assigned_to] => Array
                (
                    [id] => 3
                    [name] => NAME
                )

            [spent_hours] => 0
            [priority] => Array
                (
                    [id] => 2
                    [name] => Normal
                )

            [subject] => BLABLABLA
            [project] => Array
                (
                    [id] => 4
                    [name] => BLABLABLA
                )

            [done_ratio] => 0
        )
    )

This is how to create an issue:

    $client->issue->create([
        'project_id' => 'test',
        'subject' => 'test api (xml) 3',
        'description' => 'test api',
        'assigned_to_id' => $userId,
        'custom_fields' => [
            [
                'id' => 2,
                'name' => 'Issuer',
                'value' => $_POST['ISSUER'],
            ],
            [
                'id' => 5,
                'name' => 'Phone',
                'value' => $_POST['PHONE'],
            ],
            [
                'id' => '8',
                'name' => 'Email',
                'value' => $_POST['EMAIL'],
            ],
        ],
        'watcher_user_ids' => [],
    ]);

Here are the default values when creating an issue:

    $defaults = [
        'subject' => null,
        'description' => null,
        'project_id' => null,
        'category_id' => null,
        'priority_id' => null,
        'status_id' => null,
        'tracker_id' => null,
        'assigned_to_id' => null,
        'author_id' => null,
        'due_date' => null,
        'start_date' => null,
        'watcher_user_ids' => null,
        'fixed_version_id' => null,
    ];

Here is how to update an issue, e.g. to add a note

    $client->issue->update($issueId, [
        // 'subject'        => 'test note (xml) 1',
        // 'notes'          => 'test note api',
        // 'assigned_to_id' => $userId,
        // 'status_id'      => 2,
        'status' => 'Resolved',
        'priority_id' => 5,
        'due_date' => date('Y-m-d'),
    ]);

