<?php 
$I = new AcceptanceTester($scenario);
$I->wantTo('ensure that backoffice asks for authentication');
$I->amOnPage('/backoffice');
$I->see('Sign in');
