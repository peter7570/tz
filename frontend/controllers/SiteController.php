<?php
namespace frontend\controllers;

use Yii;
use yii\base\InvalidParamException;
use yii\data\ActiveDataProvider;
use yii\web\BadRequestHttpException;
use yii\web\Controller;
use yii\filters\VerbFilter;
use yii\filters\AccessControl;
use common\models\LoginForm;
use frontend\models\PasswordResetRequestForm;
use frontend\models\ResetPasswordForm;
use frontend\models\SignupForm;
use frontend\models\ContactForm;
use common\models\User;
use frontend\models\UserFriends;

/**
 * Site controller
 */
class SiteController extends Controller
{
    /**
     * @inheritdoc
     */
    public function behaviors()
    {
        return [
            'access' => [
                'class' => AccessControl::className(),
                'only' => ['logout', 'signup'],
                'rules' => [
                    [
                        'actions' => ['signup'],
                        'allow' => true,
                        'roles' => ['?'],
                    ],
                    [
                        'actions' => ['logout'],
                        'allow' => true,
                        'roles' => ['@'],
                    ],
                ],
            ],
            'verbs' => [
                'class' => VerbFilter::className(),
                'actions' => [
                    'logout' => ['post'],
                ],
            ],
        ];
    }

    /**
     * @inheritdoc
     */
    public function actions()
    {
        return [
            'error' => [
                'class' => 'yii\web\ErrorAction',
            ],
            'captcha' => [
                'class' => 'yii\captcha\CaptchaAction',
                'fixedVerifyCode' => YII_ENV_TEST ? 'testme' : null,
            ],
        ];
    }

    /**
     * Displays homepage.
     *
     * @return mixed
     */
    public function actionIndex()
    {
        
        $model = new User();
        return $this->render('index', [
                'model' => $model,
            ]);
    }
    
    public function actionAddfriend($fid = NULL,$uid = NULL)
    {

        $model = new UserFriends();
        $model->addFriend($fid,$uid);
        $this->redirect(['site/index']);

    }
    

    public function actionRemovefriend($fid = NULL,$uid = NULL)
    {
        
        $model = new UserFriends();
        $model->removeFriend($fid,$uid);
        $this->redirect(['site/index']);
    }

    public function actionDeleteuser($id)
    {
        
        $model = new User();
        $model->deleteUser($id);
        $this->redirect(['site/index']);
    }

    public function actionPrivateoffice($id)
    {
        if (Yii::$app->user->isGuest) {
            return $this->redirect(['/']);
        }
        
        
        $model = new User();
        $u = $model->findById($id);

        $qw = "SELECT `user_friends`.* FROM `user_friends` WHERE (user_friends.friend_id = $id) AND (user_friends.user_id != $id)";
        
        $result = UserFriends::findBySql($qw)
        ->asArray()
        ->all();
        
        foreach($result as $k=>$item) {
            
            $uid = $item['user_id'];
            
            $qw = "SELECT `user_friends`.* FROM `user_friends` WHERE `user_friends`.`user_id` = $id AND `user_friends`.`friend_id` = $uid";
        
            $result = UserFriends::findBySql($qw)->asArray()->all();
            
            if(!$result) {
             
             if($where) $where .= ' OR ';  
             $where .= ' `user`.`id` = '.$uid; 
            
            }

        }

        ($where) ? $qw = "SELECT `user`.* FROM `user` WHERE ".$where : $qw = "SELECT `user`.* FROM `user`"; 

        $dataProvider = new ActiveDataProvider([

            'query' => User::findBySql($qw),
        ]); 
        
        return $this->render('privateoffice', [
            'user'       => $u,
            'friends'    => $dataProvider,
            'loggedUser' => Yii::$app->getUser(),
        ]);
    }

    /**
     * Logs in a user.
     *
     * @return mixed
     */
    public function actionLogin()
    {
        if (!Yii::$app->user->isGuest) {
            return $this->goHome();
        }

        $model = new LoginForm();
        if ($model->load(Yii::$app->request->post()) && $model->login()) {
            return $this->goBack();
        } else {
            return $this->render('login', [
                'model' => $model,
            ]);
        }
    }

    /**
     * Logs out the current user.
     *
     * @return mixed
     */
    public function actionLogout()
    {
        Yii::$app->user->logout();

        return $this->goHome();
    }

    /**
     * Displays contact page.
     *
     * @return mixed
     */
    public function actionContact()
    {
        $model = new ContactForm();
        if ($model->load(Yii::$app->request->post()) && $model->validate()) {
            if ($model->sendEmail(Yii::$app->params['adminEmail'])) {
                Yii::$app->session->setFlash('success', 'Thank you for contacting us. We will respond to you as soon as possible.');
            } else {
                Yii::$app->session->setFlash('error', 'There was an error sending email.');
            }

            return $this->refresh();
        } else {
            return $this->render('contact', [
                'model' => $model,
            ]);
        }
    }

    /**
     * Displays about page.
     *
     * @return mixed
     */
    public function actionAbout()
    {
        return $this->render('about');
    }

    /**
     * Signs user up.
     *
     * @return mixed
     */
    public function actionSignup()
    {
        $model = new SignupForm();
        if ($model->load(Yii::$app->request->post())) {
            if ($user = $model->signup()) {
                if (Yii::$app->getUser()->login($user)) {
                    return $this->goHome();
                }
            }
        }

        return $this->render('signup', [
            'model' => $model,
        ]);
    }

    /**
     * Requests password reset.
     *
     * @return mixed
     */
    public function actionRequestPasswordReset()
    {
        $model = new PasswordResetRequestForm();
        if ($model->load(Yii::$app->request->post()) && $model->validate()) {
            if ($model->sendEmail()) {
                Yii::$app->session->setFlash('success', 'Check your email for further instructions.');

                return $this->goHome();
            } else {
                Yii::$app->session->setFlash('error', 'Sorry, we are unable to reset password for email provided.');
            }
        }

        return $this->render('requestPasswordResetToken', [
            'model' => $model,
        ]);
    }

    /**
     * Resets password.
     *
     * @param string $token
     * @return mixed
     * @throws BadRequestHttpException
     */
    public function actionResetPassword($token)
    {
        try {
            $model = new ResetPasswordForm($token);
        } catch (InvalidParamException $e) {
            throw new BadRequestHttpException($e->getMessage());
        }

        if ($model->load(Yii::$app->request->post()) && $model->validate() && $model->resetPassword()) {
            Yii::$app->session->setFlash('success', 'New password was saved.');

            return $this->goHome();
        }

        return $this->render('resetPassword', [
            'model' => $model,
        ]);
    }
}
