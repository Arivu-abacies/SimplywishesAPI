<?php

namespace app\controllers;

use Yii;
use yii\web\UploadedFile;
use app\models\HappyStories;
use app\models\StoryActivity;
use app\models\User;
use app\models\UserProfile;
use app\models\search\SearchHappyStories;
use yii\filters\VerbFilter;
use yii\filters\AccessControl;
use yii\helpers\Url;

class HappyStoriesController extends \yii\web\Controller
{
	 /**
     * @inheritdoc
     */
    public function behaviors()
    {
        return [
            'verbs' => [
                'class' => VerbFilter::className(),
                'actions' => [
                    'delete' => ['POST'],
                ],
            ],
            'access' => [
                'class' => AccessControl::className(), 
				'except' => ['index','story-details','like','scroll-happy'],	
                'rules' => [
                    [
                        'actions' => ['create','update','my-story','delete','permission','view','update-new','scroll-my-happy'],
                        'allow' => true,
                        'roles' => ['@'],
                    ],
                ],
            ],
        ];
    }
	
    public function actionIndex()
    {		
		/* $stories = HappyStories::find()->where(['status'=>0])->orderBy('hs_id Desc')->all();				
        return $this->render('index', ['stories' => $stories]); */
				
		$searchModel = new SearchHappyStories();
        $dataProvider = $searchModel->searchLive(Yii::$app->request->queryParams);

        return $this->render('index', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
        ]);
		
    }

	 public function actionScrollHappy($page)
    {
        $searchModel = new SearchHappyStories();
        $dataProvider = $searchModel->searchLive(Yii::$app->request->queryParams);
		$dataProvider->pagination->page = $page;
        $str = '';
        foreach($dataProvider->models as $story){
			$str .= $story->happyAsCard;
        }
        return $str;
    }
	
	
	 public function actionMyStory()
    {			
		$user = User::findOne(\Yii::$app->user->id);
		$profile = UserProfile::find()->where(['user_id'=>\Yii::$app->user->id])->one();
	
		$searchModel = new SearchHappyStories();
        $dataProvider = $searchModel->searchMystories(Yii::$app->request->queryParams);
		
        return $this->render('my-story', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
			'user' => $user,
			'profile' => $profile
        ]);
		
    }
	
	
	 public function actionScrollMyHappy($page)
    {
        $searchModel = new SearchHappyStories();
        $dataProvider = $searchModel->searchMystories(Yii::$app->request->queryParams);
		$dataProvider->pagination->page = $page;
        $str = '';
        foreach($dataProvider->models as $story){
			$str .= $story->myHappyAsCard;
        }
        return $str;
    }
	
	public function actionCreate()
    {
		 $model = new HappyStories();
		$user = User::findOne(\Yii::$app->user->id);
		$profile = UserProfile::find()->where(['user_id'=>\Yii::$app->user->id])->one();
		
		  
         if ($model->load(Yii::$app->request->post())) {
			
			/* echo "<pre>";
			print_r(Yii::$app->request->post());
			exit;
			 */
			 $model->user_id = \Yii::$app->user->id;
			$model->story_image = UploadedFile::getInstance($model, 'story_image');
				if(!empty($model->story_image)) {
					if(!$model->uploadImage())
						return;
				}else 
				{
					$model->story_image = $model->dulpicate_image;
				}
				
				if($model->save())
				{
					Yii::$app->session->setFlash('success_adminhappystory');
					$model->sendSuccessEmail(\Yii::$app->user->id);
				    return $this->redirect(['my-story']);
				}	
				else
				{					
					return $this->render('create', ['model' => $model,'user' => $user,'profile' => $profile,]);
				}	
				
        } else {
            return $this->render('create', [
                'model' => $model,
				'user' => $user,
				'profile' => $profile,
            ]);
        }
    }
	
	 public function actionStoryDetails($id)
    {		
		$model = HappyStories::findOne($id);		
        return $this->render('story_full', ['model' => $model]);
	
    }
	
	public function actionUpdate($id)
    {
        $model = HappyStories::findOne($id);
		$current_image = $model->story_image;
		//$model->scenario = 'update_by_happystory_user';
		
		$user = User::findOne(\Yii::$app->user->id);
		$profile = UserProfile::find()->where(['user_id'=>\Yii::$app->user->id])->one();
		
        if ($model->load(Yii::$app->request->post())){
			
			/**		Image Uploaded for Update function Line 
			**/		
			$model->story_image = UploadedFile::getInstance($model, 'story_image');										
				if(!empty($model->story_image)){ 
					if(!$model->uploadImage())
						return;
				}
			 else
				{					
					if(!empty($model->dulpicate_image) && ($model->dulpicate_image != $current_image ))
					{
						$model->story_image = $model->dulpicate_image;
					} else {
						$model->story_image = $current_image;
					}					
				}
				
				if($model->save())
				{	Yii::$app->session->setFlash('success_happystory');
					//return $this->redirect(['story-details', 'id' => $model->hs_id]);
					return $this->redirect(['my-story']);
				} else {
					
					return $this->render('update', ['model' => $model,'user' => $user,
				'profile' => $profile,]);
			}
        } else {
            return $this->render('update', [
                'model' => $model,
				'user' => $user,
				'profile' => $profile,
            ]);
        }
    }
	
	/**
	 * Like a story
	 * User has to be logged in to like a wish
	 * Param: wish id
	 * @return boolean
	 */
	public function actionLike($s_id,$type)
	{
		if(\Yii::$app->user->isGuest)
			return $this->redirect(['site/login','red_url'=>Yii::$app->request->referrer]);
		$story = $this->findModel($s_id);
		$activity = StoryActivity::find()->where(['story_id'=>$story->hs_id,'activity'=>$type,'user_id'=>\Yii::$app->user->id])->one();
		if($activity != null){
			$activity->delete();
			return "removed";
		}
			$activity = new StoryActivity();
		$activity->story_id = $story->hs_id;
		$activity->activity = $type;
		$activity->user_id = \Yii::$app->user->id;
		if($activity->save())
			return "added";
		else return false;
	}
	
    /**
     * Finds the story model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param integer $id
     * @return Wish the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($id)
    {
        if (($model = HappyStories::findOne($id)) !== null) {
            return $model;
        } else {
            throw new NotFoundHttpException('The requested page does not exist.');
        }
    }
	
	public function actionDelete()
    {
		$id  = \Yii::$app->request->post()['id'];	
		if($id)	
			$this->findModel($id)->delete(); 
		
        //return $this->redirect(['my-story']);  
    }
	
	 public function actionPermission()
    {		
		/* $stories = HappyStories::find()->orderBy('hs_id Desc')->all();				
        return $this->render('index', ['stories' => $stories]); */
	
		$searchModel = new SearchHappyStories();
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams);
		
        return $this->render('index_new', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
        ]);
		
    }
	
	
	 /**
     * Displays a single Editorial model.
     * @param integer $id
     * @return mixed
     */
    public function actionView($id)
    {
        return $this->render('view', [
            'model' => $this->findModel($id),
        ]);
    }
	
	
	public function actionUpdateNew($id)
    {
        $model = HappyStories::findOne($id);
		$model->scenario = 'update_by_happystory_adminuser';
		
        if ($model->load(Yii::$app->request->post())){
		
				if($model->save())
				{	
					$model->sendAdminSuccessEmail( $model->user_id);
					return $this->redirect(['view', 'id' => $model->hs_id]);
				} else {
			
				return $this->render('update_new', ['model' => $model]);
			}
			
			
        } else {
            return $this->render('update_new', ['model' => $model,]);
        }
    }
	
}
