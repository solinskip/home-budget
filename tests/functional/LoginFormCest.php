<?php

class LoginFormCest
{
    public function _before(\FunctionalTester $I)
    {
        $I->amOnRoute('site/login');
    }

    public function openLoginPage(\FunctionalTester $I)
    {
        $category = $I->grabRecord('app\models\User', ['username' => 'admin']);
        codecept_debug($category);
        $I->seeElement('form#form-login');
        $I->seeElement('input', ['name' => 'Login[username]']);
        $I->seeElement('input', ['name' => 'Login[password]']);
        $I->see('Zaloguj się', 'button');
    }

    // demonstrates `amLoggedInAs` method
    public function internalLoginById(\FunctionalTester $I)
    {
        $I->amLoggedInAs(8);
        $I->amOnPage('/');
        $I->see('Piotr', 'span');
    }

    // demonstrates `amLoggedInAs` method
    public function internalLoginByInstance(\FunctionalTester $I)
    {
        $I->amLoggedInAs(\app\models\User::findByUsername('admin'));
        $I->amOnPage('/');
        $I->see('admin', 'span');
    }

    public function loginWithEmptyCredentials(\FunctionalTester $I)
    {
        $I->submitForm('#form-login', []);
        $I->expectTo('see validations errors');
        $I->see('Nazwa użytkownika nie może pozostać bez wartości.');
        $I->see('Hasło nie może pozostać bez wartości.');
    }

    public function loginWithWrongCredentials(\FunctionalTester $I)
    {
        $I->submitForm('#form-login', [
            'Login[username]' => 'Piotr',
            'Login[password]' => 'wrong-password',
        ]);
        $I->expectTo('see validations errors');
        $I->see('Nieprawidłowy login lub hasło.');
    }

    public function loginSuccessfully(\FunctionalTester $I)
    {
        $I->submitForm('#form-login', [
            'Login[username]' => 'Piotr',
            'Login[password]' => 'asdfasdf',
        ]);
        $I->see('Piotr', 'span');
        $I->see('Wyloguj się', 'a');
        $I->dontSeeElement('form#form-login');
    }

}