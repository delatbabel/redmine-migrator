#!/usr/bin/env php
<?php

/**
 * Define the servers and store into the configuration.
 *
 * @param array $config
 * @return array
 */
function defineServers(array $config)
{
    if (! isset($config['source']) || ! is_array($config['source'])) {
        echo "\n\nSource Server Configuration\n===========================\n\n";
        $config['source']['URL'] = readline('Source server URL (e.g. https://some.redmine.server.com): ');
        $config['source']['key'] = readline('Source server Key (e.g. 1234567890abcdef1234567890abcdef12345678): ');
    }
    if (! isset($config['dest']) || ! is_array($config['dest'])) {
        echo "\n\nDestination Server Configuration\n================================\n\n";
        $config['dest']['URL'] = readline('Destination server URL (e.g. https://another.redmine.server.com): ');
        $config['dest']['key'] = readline('Destination server Key (e.g. 1234567890abcdef1234567890abcdef12345678): ');
    }

    return $config;
}

/**
 * Show trackers
 *
 * @param Redmine\Client $client
 */
function showTrackers($client)
{
    $trackers = $client->tracker->all();

    print "+-------+----------------------------------------------------+\n";
    printf("| %5s | %-50s |\n", 'id', 'Tracker Name');
    print "+-------+----------------------------------------------------+\n";
    foreach ($trackers['trackers'] as $tracker) {
        printf("| %5d | %-50s |\n", $tracker['id'], $tracker['name']);
    }
    print "+-------+----------------------------------------------------+\n\n";
}

/**
 * Define the tracker mapping from source trackers to destination trackers
 *
 * @param array $config
 * @param Redmine\Client $source
 * @param Redmine\Client $dest
 * @return array
 */
function defineTrackerMapping(array $config, $source, $dest)
{
    if (isset($config['tracker_map']) && is_array($config['tracker_map'])) {
        return $config;
    }

    //
    // Show Trackers
    //
    print "\nSource Issue Types\n\n";
    showTrackers($source);

    $trackers = $source->tracker->all();
    foreach ($trackers['trackers'] as $tracker) {
        print "Source Issue Type ID " . $tracker['id'] . ", name: " . $tracker['name'] . "\n";
        print "\nDestination Issue Types\n\n";
        showTrackers($dest);
        $config['tracker_map'][$tracker['id']] = readline('Enter the destination issue type ID for source issue type ' .
            $tracker['name'] . ': ');
    }

    return $config;
}

/**
 * Show statuses
 *
 * @param Redmine\Client $client
 */
function showStatuses($client)
{
    $statuses = $client->issue_status->all();

    print "+-------+----------------------------------------------------+\n";
    printf("| %5s | %-50s |\n", 'id', 'Issue Status Name');
    print "+-------+----------------------------------------------------+\n";
    foreach ($statuses['issue_statuses'] as $status) {
        printf("| %5d | %-50s |\n", $status['id'], $status['name']);
    }
    print "+-------+----------------------------------------------------+\n\n";
}

/**
 * Define the status mapping from source issue statuses to destination issue statuses
 *
 * @param array $config
 * @param Redmine\Client $source
 * @param Redmine\Client $dest
 * @return array
 */
function defineStatusMapping(array $config, $source, $dest)
{
    if (isset($config['status_map']) && is_array($config['status_map'])) {
        return $config;
    }

    //
    // Show Statuses
    //
    print "\nSource Issue Statuses\n\n";
    showStatuses($source);

    $statuses = $source->issue_status->all();
    foreach ($statuses['issue_statuses'] as $status) {
        print "Source Issue Status ID " . $status['id'] . ", name: " . $status['name'] . "\n";
        print "\nDestination Issue Statuses\n\n";
        showStatuses($dest);
        $config['status_map'][$status['id']] = readline('Enter the destination issue status ID for source issue status ' .
            $status['name'] . ': ');
    }

    return $config;
}

/**
 * Show priorities
 *
 * @param Redmine\Client $client
 */
function showPriorities($client)
{
    $priorities = $client->issue_priority->all();

    print "+-------+----------------------------------------------------+\n";
    printf("| %5s | %-50s |\n", 'id', 'Issue Priority Name');
    print "+-------+----------------------------------------------------+\n";
    foreach ($priorities['issue_priorities'] as $priority) {
        printf("| %5d | %-50s |\n", $priority['id'], $priority['name']);
    }
    print "+-------+----------------------------------------------------+\n\n";
}

/**
 * Define the priority mapping from source issue priorities to destination issue priorities
 *
 * @param array $config
 * @param Redmine\Client $source
 * @param Redmine\Client $dest
 * @return array
 */
function definePriorityMapping(array $config, $source, $dest)
{
    if (isset($config['priority_map']) && is_array($config['priority_map'])) {
        return $config;
    }

    //
    // Show Priorities
    //
    print "\nSource Issue Priorities\n\n";
    showPriorities($source);

    $priorities = $source->issue_priority->all();
    foreach ($priorities['issue_priorities'] as $priority) {
        print "Source Issue Priority ID " . $priority['id'] . ", name: " . $priority['name'] . "\n";
        print "\nDestination Issue Priorities\n\n";
        showPriorities($dest);
        $config['priority_map'][$priority['id']] = readline('Enter the destination issue priority ID for source issue priority ' .
            $priority['name'] . ': ');
    }

    return $config;
}

/**
 * Show users
 *
 * @param Redmine\Client $client
 */
function showUsers($client)
{
    $users = $client->user->all();

    print "+-------+----------------------------------------------------+\n";
    printf("| %5s | %-50s |\n", 'id', 'User Name');
    print "+-------+----------------------------------------------------+\n";
    foreach ($users['users'] as $user) {
        printf("| %5d | %-50s |\n", $user['id'], $user['firstname'] . ' ' . $user['lastname']);
    }
    print "+-------+----------------------------------------------------+\n\n";
}

/**
 * Define the user mapping from source users to destination users
 *
 * @param array $config
 * @param Redmine\Client $source
 * @param Redmine\Client $dest
 * @return array
 */
function defineUserMapping(array $config, $source, $dest)
{
    if (isset($config['user_map']) && is_array($config['user_map'])) {
        return $config;
    }

    //
    // Show Statuses
    //
    print "\nSource Users\n\n";
    showUsers($source);

    $users = $source->user->all();
    foreach ($users['users'] as $user) {
        print "Source User ID " . $user['id'] . ", name: " . $user['firstname'] . ' ' . $user['lastname'] . "\n";
        print "\nDestination Users\n\n";
        showUsers($dest);
        $config['user_map'][$user['id']] = readline('Enter the destination user ID for source user ' .
            $user['firstname'] . ' ' . $user['lastname'] . ': ');
    }

    return $config;
}

/**
 * Show projects
 *
 * @param Redmine\Client $client
 */
function showProjects($client)
{
    $projects = $client->project->all();

    print "+-------+----------------------------------------------------+\n";
    printf("| %5s | %-50s |\n", 'id', 'Project Name');
    print "+-------+----------------------------------------------------+\n";
    foreach ($projects['projects'] as $project) {
        printf("| %5d | %-50s |\n", $project['id'], $project['name']);
    }
    print "+-------+----------------------------------------------------+\n\n";
}

/**
 * Define the source and destination projects
 *
 * @param array $config
 * @param Redmine\Client $source
 * @param Redmine\Client $dest
 * @return array
 */
function defineProjects(array $config, $source, $dest)
{
    if (isset($config['project_map']) && is_array($config['project_map'])) {
        return $config;
    }

    print "\nSource Projects\n\n";
    showProjects($source);
    $source_project_id = readline('Select a source project ID: ');
    $config['project_map']['source_project_id'] = $source_project_id;

    print "\nDestination Projects\n\n";
    showProjects($dest);

    $config['project_map'][$source_project_id] = readline('Select a destination project ID: ');
    return $config;
}

//
// Variables
//
$config_file = 'config.json';

// For Composer users (this file is generated by Composer)
require_once 'vendor/autoload.php';

// Grab the config so far, as an associative array
if (file_exists($config_file)) {
    $config = json_decode(file_get_contents($config_file), true);
} else {
    $config = [];
}

//
// Have the source and destination servers been defined?
//
$config = defineServers($config);
file_put_contents($config_file, json_encode($config, JSON_PRETTY_PRINT));

$source = new Redmine\Client($config['source']['URL'], $config['source']['key']);
$dest = new Redmine\Client($config['dest']['URL'], $config['dest']['key']);

//
// Have the tracker mappings been defined?
//
$config = defineTrackerMapping($config, $source, $dest);
file_put_contents($config_file, json_encode($config, JSON_PRETTY_PRINT));

//
// Have the status mappings been defined?
//
$config = defineStatusMapping($config, $source, $dest);
file_put_contents($config_file, json_encode($config, JSON_PRETTY_PRINT));

//
// Have the priority mappings been defined?
//
$config = definePriorityMapping($config, $source, $dest);
file_put_contents($config_file, json_encode($config, JSON_PRETTY_PRINT));

//
// Have the user mappings been defined?
//
$config = defineUserMapping($config, $source, $dest);
file_put_contents($config_file, json_encode($config, JSON_PRETTY_PRINT));

//
// Define project mapping
//
$config = defineProjects($config, $source, $dest);
file_put_contents($config_file, json_encode($config, JSON_PRETTY_PRINT));
$source_project_id = $config['project_map']['source_project_id'];
$dest_project_id = $config['project_map'][$source_project_id];

//
// Get destination server user ID to username mapping, required for setImpersonateUser()
//
$users = $dest->user->all();
$userNameMap = [];
foreach ($users['users'] as $user) {
    $userNameMap[$user['id']] = $user['login'];
}

//
// Get the list of issues
// FIXME: Only does 100 issues, does not page through the entire project.
//
$issues = $source->issue->all([
    'limit'         => 100,
    'sort'          => 'id',
    'project_id'    => $source_project_id,
]);
# print_r($issues);

foreach ($issues['issues'] as $issue) {
    print "Processing issue ID " . $issue['id'] . ', ' . $issue['subject'] . ', Status: ' . $issue['status']['name'] . "\n";

    // If you just wanted to try one issue you could find the issue ID and put it in here.
    // if ($issue['id'] != 174) {
    //     continue;
    // }

    // Get the issue data
    $issue_data = $source->issue->show($issue['id'], ['include' => 'attachments,journals']);
    $issue_data = $issue_data['issue'];

    // echo "\nSource Issue Data\n=================\n\n";
    // print_r($issue_data);

    // Create a new issue on the destination server
    $create_attributes = [
        'project_id'        => $dest_project_id,
        'tracker_id'        => $config['tracker_map'][$issue_data['tracker']['id']],
        'status_id'         => $config['status_map'][$issue_data['status']['id']],
        'priority_id'       => $config['priority_map'][$issue_data['priority']['id']],
        'subject'           => $issue_data['subject'],
        'description'       => $issue_data['description'],
        'assigned_to_id'    => $config['user_map'][$issue_data['assigned_to']['id']],
        'author_id'         => $config['user_map'][$issue_data['author']['id']],
    ];
    if (! empty($issue_data['estimated_hours'])) {
        $create_attributes['estimated_hours'] = $issue_data['estimated_hours'];
    }

    // echo "\nDestination Issue Data\n======================\n\n";
    // print_r($create_attributes);

    // Switch user and create the destination issue
    $dest->setImpersonateUser($userNameMap[$create_attributes['author_id']]);
    $postResult = $dest->issue->create($create_attributes);
    $dest->setImpersonateUser();

    // echo "\nPOST Results\n============\n\n";
    // print_r($postResult);
    /** @var int $new_issue_id */
    $new_issue_id = $postResult->id;
    echo "New issue $new_issue_id created.\n";

    // Go through all of the journals and add the notes.  At this stage I'm not really interested
    // in journals that don't have notes (e.g. status changes).
    if (! empty($issue_data['journals'])) {
        foreach ($issue_data['journals'] as $journal) {
            if (empty($journal['notes'])) {
                continue;
            }
            $private_note = false;
            $dest->issue->addNoteToIssue($new_issue_id, $journal['notes'], $private_note);
            echo "Note added to issue.\n";
        }
    }

    // Go through all of the attachments and add them ot the destination server
    if (! empty($issue_data['attachments'])) {
        foreach ($issue_data['attachments'] as $attachment) {
            $file_content = $source->attachment->download($attachment['id']);
            // To upload a file + attach it to an existing issue with $issueId
            $upload = json_decode($dest->attachment->upload($file_content));
            $dest->issue->attach($new_issue_id, [
                'token'         => $upload->upload->token,
                'filename'      => $attachment['filename'],
                'description'   => $attachment['description'],
            ]);
            echo "Attachment added to issue.\n";
        }
    }
}
