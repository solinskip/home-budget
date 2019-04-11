<?php

class CategoryFormCest
{
    public function _before(\FunctionalTester $I)
    {
        $I->amOnRoute('/category/index');
    }

    public function openCategoryPageAsGuest(\FunctionalTester $I)
    {
        $I->seeResponseCodeIsSuccessful();
        $I->see('Budżet domowy', 'span');
    }

    public function openCategoryPageAsLoggedUser(\FunctionalTester $I)
    {
        $I->amLoggedInAs(8);
        $I->amOnPage('/category/index');
    }

    public function seeElementsOnPage(\FunctionalTester $I)
    {
        $I->amLoggedInAs(8);
        $I->amOnPage('/category/index');
        $I->see('Kategorie finansów', 'h1');
        $I->see('Kategoria', 'th');
        $I->see('Podkategoria', 'th');
        $I->see('Opis', 'th');
        $I->see('Słowa filtrujące', 'th');
        $I->see('Akcje', 'th');
    }

    public function openEachUpdatePageCategory(\FunctionalTester $I)
    {
        $I->amLoggedInAs(8);

        $categories = \app\models\Category::find()->select('id, parent')->asArray()->all();

        foreach ($categories as $category) {
            $I->amOnPage(\yii\helpers\Url::to(['/category/update',
                'id' => $category['id'],
                'type' => ($category['parent'] === '0' ? 'category' : 'subcategory')]));
            $I->seeResponseCodeIsSuccessful();
        }
    }
}