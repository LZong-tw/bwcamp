<?
$camp = Camp::find(102);
$action = "read";
$resource = User::find(172);
$user = User::find(126);
$user->canAccessResource(
    $user,
    "read",
    $camp,
    target: $user,
    context: "vcamp",
    probing: 1
);
