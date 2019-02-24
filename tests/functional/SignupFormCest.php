<?php

class SignupFormCest
{
    public function _before(\FunctionalTester $I)
    {
        $I->amOnRoute('site/signup');
    }

    public function openSignupPage(\FunctionalTester $I)
    {
        $I->seeElement('form#form-signup');
        $I->seeElement('input', ['name' => 'Signup[username]']);
        $I->seeElement('input', ['name' => 'Signup[email]']);
        $I->seeElement('input', ['name' => 'Signup[password]']);
        $I->see('Rejestracja', 'button');
    }

    public function signupWithEmptyCredentials(\FunctionalTester $I)
    {
        $I->submitForm('#form-signup', []);
        $I->expectTo('see validations errors');
        $I->see('Nazwa użytkownika nie może pozostać bez wartości.');
        $I->see('Hasło nie może pozostać bez wartości.');
    }

    public function signupWithRegisteredUsernameWithoutPassword(\FunctionalTester $I)
    {
        $I->submitForm('#form-signup', [
            'Signup[username]' => 'Piotr',
            'Signup[email]' => '',
            'Signup[password]' => '',
        ]);
        $I->expectTo('see validations errors');
        $I->see('Podana nazwa użytkownika jest już zajętą.');
        $I->see('Hasło nie może pozostać bez wartości.');
    }

    public function signupWithRegisteredUsernameWithPassword(\FunctionalTester $I)
    {
        $I->submitForm('#form-signup', [
            'Signup[username]' => 'Piotr',
            'Signup[email]' => '',
            'Signup[password]' => 'password',
        ]);
        $I->expectTo('see validations errors');
        $I->see('Podana nazwa użytkownika jest już zajętą.');
    }
    
    public function signupSuccess(\FunctionalTester $I)
    {
        $I->fillField('Signup[username]', 'TestSignup');
        $I->fillField('Signup[email]', 'test@gmail.com');
        $I->fillField('Signup[password]', 'asdfasdf');
        $I->click('Rejestracja');
    }
}