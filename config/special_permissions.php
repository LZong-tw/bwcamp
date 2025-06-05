<?php

return [
    'ceo_emails' => array_filter(explode(',', env('CEO_EMAILS', ''))),
    'ecamp_emails' => array_filter(explode(',', env('ECAMP_EMAILS', ''))),
    'utcamp_emails' => array_filter(explode(',', env('UTCAMP_EMAILS', ''))),
    'ycamp_emails' => array_filter(explode(',', env('YCAMP_EMAILS', ''))),
];
