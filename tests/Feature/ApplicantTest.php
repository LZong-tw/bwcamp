<?php

test('學員正確錄取', function () {
    $response = $this->get('/');

    $response->assertStatus(200);
});
