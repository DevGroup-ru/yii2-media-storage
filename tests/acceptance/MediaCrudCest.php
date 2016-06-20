<?php


use DevGroup\MediaStorage\models\Media;
use yii\helpers\FileHelper;

class PageCrudCest
{
    public function _before(AcceptanceTester $I)
    {
        Media::deleteAll();
        FileHelper::removeDirectory(implode(DIRECTORY_SEPARATOR, [__DIR__, '..', 'testapp', 'media']));
    }

    public function _after(AcceptanceTester $I)
    {
    }

    // tests
    public function tryToTest(AcceptanceTester $I)
    {
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
        #login method mb remove to before
        if (method_exists($I, 'wait')) {
            $I->wait(2); // only for selenium
        }

        $I->switchToIFrame();
        $I->switchToIFrame("all-files");
        $I->wantTo('ensure i see elfinder frame');
        $I->see('protected');

        $I->wantTo('upload file');
        $I->click('.elfinder-button-icon-upload');
        $I->attachFile('.elfinder-upload-dialog-wrapper input', 'img.png');

        if (method_exists($I, 'wait')) {
            $I->wait(2); // only for selenium
        }

        $I->see('img.png');

        $I->wantTo('ensure that DB record is created and file uploaded to correct place');
        $media = Media::findOne(['path' => 'img.png']);
        $I->assertInstanceOf(Media::class, $media);
        $I->assertFileExists(implode(DIRECTORY_SEPARATOR, [__DIR__, '..', 'testapp', 'media', 'img.png']));



    }
}
