
<?php

/**
 * @class      SiteController
 *
 * This is the controller that contains the /site actions.
 *
 * @author     Developer
 * @copyright  PLS 3rd Learning, Inc. All rights reserved.
 */

class SiteController extends Controller {

    /**
     * Specifies the action filters.
     *
     * @return array action filters
     */
    public function filters() {
        return [
            'accessControl',
        ];
    }

    /**
     * Specifies the access control rules.
     *
     * @return array access control rules
     */
    public function accessRules() {
        return [
            [
                'allow',  // allow all users to access specified actions.
                'actions' => ['index', 'login', 'about', 'error'],
                'users'   => ['*'],
            ],
            [
                'allow', // allow authenticated users to access all actions
                'users' => ['@'],
            ],
            [
                'deny',  // deny all users
                'users' => ['*'],
            ],
        ];
    }

    /**
     * Initialize the controller.
     *
     * @return void
     */
    public function init() {
        $this->defaultAction = 'login';
    }

    /**
     * Renders the about page.
     *
     * @return void
     */
    public function actionAbout() {
        $this->render('about');
    }

    /**
     * Renders the login page.
     *
     * @return void
     */
    public function actionLogin() {
        if (!defined('CRYPT_BLOWFISH') || !CRYPT_BLOWFISH) {
            throw new CHttpException(500, 'This application requires that PHP was compiled with Blowfish support for crypt().');
        }
        $model = new LoginForm();
        if (isset($_POST['ajax']) && $_POST['ajax'] === 'login-form') {
            echo CActiveForm::validate($model);
            Yii::app()->end();
        }
        if (isset($_POST['LoginForm'])) {
            $model->attributes = $_POST['LoginForm'];
            if ($model->validate() && $model->login()) {
                $this->redirect(Yii::app()->user->returnUrl);
            }
        }

        $slideLatestUpdate = HelpController::getLatestRSSFeed('latestUpdatesFeedUrl', 1);
        $slideLatestBlog = HelpController::getLatestRSSFeed('latestBlogFeedUrl', 1);
        // $this->render('login', ['model' => $model]);
        $this->render('login', ['model' => $model, 'slideLatestUpdate' => $slideLatestUpdate , 'slideLatestBlog' => $slideLatestBlog]);
    }

    /**
     * Logs out the current user and redirects to homepage.
     *
     * @return void
     */
    public function actionLogout() {
        Yii::app()->user->logout();
        $this->redirect(Yii::app()->homeUrl);
    }

    /**
     * The action that handles external exceptions.
     *
     * @return void
     */
    public function actionError() {
        if ($error = Yii::app()->errorHandler->error) {
            if (Yii::app()->request->isAjaxRequest) {
                echo $error['message'];
            }
            else {
                $this->render('//site/error', $error);
            }
        }
    }
}