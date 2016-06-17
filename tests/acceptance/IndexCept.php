<?php
$I = new AcceptanceTester($scenario);
$I->wantTo('ensure test is work');
$I->amOnPage('/');

$I->wantTo('login');
$I->amOnPage('/media/media/all-files');
$I->see('Login');

$I->fillField('#loginform-username', 'admin');
$I->fillField('#loginform-password', 'admin');

$I->see('protected');