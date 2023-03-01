<?php
enum PermissionRanges: int {
    case na = 0;
    case all = 0;
    case volunteer_large_group = 1;
    case learner_group = 2;
    case person = 3;
}
