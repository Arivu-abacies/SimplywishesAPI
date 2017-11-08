<?php

namespace app\controllers;

use Yii;
use app\models\Editorial;
use app\models\EditorialComments;
use app\models\search\SearchEditorial;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\web\UploadedFile;
use yii\filters\VerbFilter;
use Embed\Embed;

/**
 * EditorialController implements the CRUD actions for Editorial model.
 */
class EditorialController extends Controller
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
        ];
    }

    /**
     * Lists all Editorial models.
     * @return mixed
     */
    public function actionIndex()
    {
        $searchModel = new SearchEditorial();
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams);

        return $this->render('index', [
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

    /**
     * Creates a new Editorial model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     * @return mixed
     */
    public function actionCreate()
    {
        $model = new Editorial();

         if ($model->load(Yii::$app->request->post())) {
			
			$model->e_image = UploadedFile::getInstance($model, 'e_image');
				if(!empty($model->e_image)) {
					if(!$model->uploadImage())
						return;
				}
			$model->created_by = \Yii::$app->user->id;
			
				if($model->save())
				    return $this->redirect(['view', 'id' => $model->e_id]);
				else 
					return $this->render('create', [ 'model' => $model,]);
					
        } else {
            return $this->render('create', [
                'model' => $model,
            ]);
        }
    }

    /**
     * Updates an existing Editorial model.
     * If update is successful, the browser will be redirected to the 'view' page.
     * @param integer $id
     * @return mixed
     */
    public function actionUpdate($id)
    {
        $model = $this->findModel($id);
		$current_image = $model->e_image;
		$model->scenario = 'update_by_editorial_admin';
        if ($model->load(Yii::$app->request->post())){
			
			
			
			/**		Image Uploaded for Update function Line 
			**/		
			$model->e_image = UploadedFile::getInstance($model, 'e_image');										
				if(!empty($model->e_image)){ 
					if(!$model->uploadImage())
						return;
				}else
					$model->e_image = $current_image;
				
				$model->updated_by = \Yii::$app->user->id;
				$model->updated_at = date('Y-m-d H:i:s');

				if($model->save())
				{				
					return $this->redirect(['view', 'id' => $model->e_id]);
				} else {
					return $this->render('update', ['model' => $model,]);
			}
        } else {
            return $this->render('update', [
                'model' => $model,
            ]);
        }
    }

    /**
     * Deletes an existing Editorial model.
     * If deletion is successful, the browser will be redirected to the 'index' page.
     * @param integer $id
     * @return mixed
     */
    public function actionDelete($id)
    {
        $this->findModel($id)->delete();

        return $this->redirect(['index']);
    }

    /**
     * Finds the Editorial model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param integer $id
     * @return Editorial the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($id)
    {
        if (($model = Editorial::findOne($id)) !== null) {
            return $model;
        } else {
            throw new NotFoundHttpException('The requested page does not exist.');
        }
    }
	
	public function actionEditorial()
    {
        $model = Editorial::find()->orderBy('e_id Desc')->all();
        return $this->render('editorial_page', ['model' =>$model]);
    }
	
	public function actionEditorialPage($id)
    {
         $model = Editorial::findOne($id);
         $listcomments = new EditorialComments();
         $comments = $listcomments->find()->where(['e_id'=>$id,'parent_id'=>0])->orderBy('e_comment_id Desc')->all();		 
        return $this->render('editorial_comments', ['model' =>$model,'comments'=>$comments,'listcomments'=>$listcomments]); 
    }
	
	public function actionEditorialComments()
    {		
		 $model = new EditorialComments();
         if($model->load(Yii::$app->request->post()))
		 {
			if(\Yii::$app->user->isGuest){			
				Yii::$app->session->setFlash('login_to_comment');
				return $this->redirect(['editorial/editorial-page?id='.$model->e_id]);
			}
			 $model->user_id = \Yii::$app->user->id;
			   if($model->save())
			   {
				 return $this->redirect(['editorial/editorial-page?id='.$model->e_id]);
			   }else{
				   Yii::$app->session->setFlash('error_comments');
				   return $this->redirect(['editorial/editorial-page?id='.$model->e_id]);
			   }
		 }
    }
	
	public function actionCommentreply()
    {		
		$model = new EditorialComments();
         if($model->load(Yii::$app->request->post()))
		 {				
			 if(\Yii::$app->user->isGuest){			
				Yii::$app->session->setFlash('login_to_comment');
				return $this->redirect(['editorial/editorial-page?id='.$model->e_id]);
			}
			 $model->user_id = \Yii::$app->user->id;
			   if($model->save())
			   {
				 return $this->redirect(['editorial/editorial-page?id='.$model->e_id]);
			   }else{
				   Yii::$app->session->setFlash('error_comments');
				   return $this->redirect(['editorial/editorial-page?id='.$model->e_id]);
			   } 
		 }
    }
	
	
	public function actionEmbed($url){
		
		//Load any url:
		$info = Embed::create($url);				
		//var_dump($info->code);die;
		//echo preg_match_all('/src="([\s\S]*?)"/', $info->code,$src[], PREG_SET_ORDER);die;
		if($info && $info->code){			
			$xpath = new \DOMXPath(@\DOMDocument::loadHTML($info->code));
			$src = $xpath->evaluate("string(//iframe/@src)");	
			if(!$src)
				return $url;			
		}
		if (preg_match('/youtube.com/',$info->code))
			return $src."&rel=0";
		else
			return $src;
	}
	
	
	public function actionUpload(){
		//$name = uniqid();
		$name = preg_replace( 
                     array("/\s+/", "/[^-\.\w]+/"), 
                     array("_", ""), 
                     trim($_FILES["media"]["name"])); 
		$dir = "uploads/media/";
		move_uploaded_file($_FILES["media"]["tmp_name"], $dir. $name);
		return $url = Yii::$app->urlManager->createAbsoluteUrl([$dir.$name]);
	}
	
}
