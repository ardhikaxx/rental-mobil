<?php

it('the application returns a successful response', function () {
    $this->get('/')->assertRedirect();
});
