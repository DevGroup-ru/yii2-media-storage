<?php
$I = new AcceptanceTester($scenario);

$I->wantTo('ensure iframe is protected by login form');
$I->amOnPage('/media/media/all-files');

$I->switchToIFrame("all-files");

if (method_exists($I, 'wait')) {
    $I->wait(2); // only for selenium
}

$I->see('Login');

$I->submitForm(
    '#login-form',
    [
        'LoginForm[username]' => 'admin',
        'LoginForm[password]' => 'admin',
    ]
);

if (method_exists($I, 'wait')) {
    $I->wait(2); // only for selenium
}

$I->switchToIFrame();
$I->switchToIFrame("all-files");
$I->wantTo('ensure i see elfinder frame');
$I->see('protected');

$I->click('.elfinder-button-icon-upload');
$I->attachFile('.elfinder-upload-dialog-wrapper input', 'img.png');

if (method_exists($I, 'wait')) {
    $I->wait(20); // only for selenium
}
#cert, login method