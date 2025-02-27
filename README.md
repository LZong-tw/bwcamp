# 福智綜合資料庫（formerly known as 福智營隊統計報名管理系統）

## Introduction

This repository contains the source code for the bwcamp project, written primarily in Laravel, Vue, Bootstrap, Tailwind and jQuery.

## Table of Contents

-   [Initialization](#initialization)
-   [Volunteer Management](#volunteer-management)
-   [Student Registration](#student-registration)
-   [Authorization and Permissions](#authorization-and-permissions)
-   [Interface Issues](#interface-issues)
-   [Future Enhancements](#future-enhancements)
-   [Usage Instructions](#usage-instructions)
-   [Completed Features](#completed-features)

## Initialization

1. Establish the camp.
2. Organize the structure and related positions (volunteer groups, job groups).
3. Set permissions for each position.

## Volunteer Management

1. Registration
2. Confirmation email
3. Backend record retrieval and checks for registration status, displaying options (linking existing accounts or generating new ones) and grouping (job groups).
4. On-site sign-in / course sign-in.

## Student Registration

1. Registration (general channel), backend registration (special channel, invitation system).
2. Confirmation email.
3. Backend record retrieval and grouping (student groups).
4. Send record retrieval notification email.
5. Response to participation intention.
6. On-site sign-in / course sign-in.

## Authorization and Permissions

-   Different permissions for each interface need to be determined.

## Interface Issues

-   Comprehensive interface issues to be corrected.

## Future Enhancements

1. Display teacher names in the list and enter batch record retrieval list after selecting personnel.
2. Adjust the framework for transportation data.
3. Backend changes to registration payer information.
4. Backend data that cannot be changed dynamically.
5. Countdown timer: https://motionmailapp.com

## Usage Instructions

-   Commands to be executed after each update:
    -   `php artisan migrate`
    -   `php artisan config:cache`
    -   `php artisan queue:restart` (this command should be executed as `su`)

## Completed Features

-   Display sales account data.
-   Registration system.
-   Immediate registration statistics showing the number of attendees/absentees for each session on the day.
-   Dynamic email configuration.
-   Custom email notification settings.
-   Development of teacher camps.
-   Various other completed tasks.

## Additional Information

-   Repository: [bwcamp](https://github.com/LZong-tw/bwcamp)
-   Created: August 5, 2020
-   Default Branch: master
-   Primary Language: PHP
-   Public Repository
-   Open Issues: 636

For more information, visit the [GitHub repository](https://github.com/LZong-tw/bwcamp).
