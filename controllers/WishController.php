<?php

namespace app\controllers;

use Yii;
use app\models\Wish;
use app\models\Activity;
use app\models\ReportWishes;
use app\models\Category;
use app\models\search\SearchWish;
use app\models\search\SearchReportWishes;
use app\models\User;
use app\models\UserProfile;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;
use yii\filters\AccessControl;
use yii\helpers\ArrayHelper;
use yii\web\UploadedFile;
use yii\data\ActiveDataProvider;
use yii\helpers\Url;
use app\models\MailContent;

use app\models\Payment;
/**
 * WishController implements the CRUD actions for Wish model.
 */
class WishController extends Controller
{
	public $enableCsrfValidation = false;
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
                'only' => ['create'],
                'rules' => [
                    [
                        'actions' => ['create'],
                        'allow' => true,
                        'roles' => ['@'],
                    ],
                ],
            ],
        ];
    }
	public function actionSearch(){
        $searchModel = new SearchWish();
		//$cat_id = null;
		//$searchModel->wish_title = Yii::$app->request->queryParams['match'];
        $dataProvider = $searchModel->searchCustom(Yii::$app->request->queryParams);

        return $this->render('searched_wishes', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
        ]);		
	}
    /**
     * Lists all Wish models.
     * @return mixed
     */
    public function actionIndex($cat_id=null)
    {
        $searchModel = new SearchWish();

        $dataProvider = $searchModel->search(Yii::$app->request->queryParams,$cat_id);
        return $this->render('current_wishes', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
			'cat_id' => $cat_id

        ]);
    }
    /**
     * Lists all Wish models when scrolls.
     * @return mixed
     */
    public function actionScroll($page,$cat_id=null)
    {
        $searchModel = new SearchWish();
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams,$cat_id);
		$dataProvider->pagination->page = $page;
        $str = '';
		//if ($dataProvider->totalCount > 0) {
        foreach($dataProvider->models as $wish){
				$str .= $wish->wishAsCard;
        }
		//}
        return $str;
    }
    /**
     * Lists all Wish models according to the number of likes.
     * @return mixed
     */
    public function actionPopular()
    {
        $searchModel = new SearchWish();
        $dataProvider = $searchModel->searchPopular(Yii::$app->request->queryParams);

        return $this->render('popular_wishes', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
        ]);
		
    }
    public function actionScrollPopular($page)
    {
        $searchModel = new SearchWish();
        $dataProvider = $searchModel->searchPopular(Yii::$app->request->queryParams);
		$dataProvider->pagination->page = $page;
        $str = '';
        foreach($dataProvider->models as $wish){
			$str .= $wish->wishAsCard;
        }
        return $str;
    }
	public function actionGranted(){
        $searchModel = new SearchWish();
        $dataProvider = $searchModel->searchGranted(Yii::$app->request->queryParams);

        return $this->render('fullfilled_wishes', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
        ]);		
	}
    public function actionScrollGranted($page)
    {
        $searchModel = new SearchWish();
        $dataProvider = $searchModel->searchGranted(Yii::$app->request->queryParams);
		$dataProvider->pagination->page = $page;
        $str = '';
        foreach($dataProvider->models as $wish){
			$str .= $wish->wishAsCard;
        }
        return $str;
    }
    /**
     * Displays a single Wish model.
     * @param integer $id
     * @return mixed
     */
    public function actionView($id)
    {
		$wish = $this->findModel($id);
		\Yii::$app->view->registerMetaTag([
			'name' => 'og:title',
			'property' => 'og:title',
			'content' =>$wish->wish_title
		]);
		\Yii::$app->view->registerMetaTag([
			'name' => 'og:description',
			'property' => 'og:description',
			'content' =>$wish->summary_title
		]);
		\Yii::$app->view->registerMetaTag([
			'name' => 'og:image',
			'property' => 'og:image',
			'content' =>Url::to([$wish->primary_image],true)
		]);
        return $this->render('view', [
            'model' => $this->findModel($id),
        ]);
    }

    /**
     * Creates a new Wish model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     * @return mixed
     */
    public function actionCreate()
    {
        $model = new Wish();
		$model->scenario = 'create';
		
		$countries = \yii\helpers\ArrayHelper::map(\app\models\Country::find()->all(),'id','name');	
		
		$states = [];			
		$cities = [];	
		
		$user = User::findOne(\Yii::$app->user->id);
		$profile = UserProfile::find()->where(['user_id'=>\Yii::$app->user->id])->one();
				
		$categories =  ArrayHelper::map(Category::find()->all(), 'cat_id', 'title');
        if ($model->load(Yii::$app->request->post())) {
			
			 $model->primary_image = UploadedFile::getInstance($model, 'primary_image');
				if(!empty($model->primary_image)) {
					if(!$model->uploadImage())
						return;
				} else 
				{
					 $model->primary_image = $model->primary_image_name;
				}
				
				$model->wished_by = \Yii::$app->user->id;
				$model->wish_status = 0;
				
			
			
			
			
			
			if(!empty($model->auto_id))
			{
			
				$model1 = Wish::findOne($model->auto_id);
				
			
				$model1->w_id = $model->auto_id;
				$model1->wished_by = \Yii::$app->user->id;					
				$model1->category = $model->category;
				$model1->wish_title = $model->wish_title;
				$model1->wish_description = $model->wish_description;
				
				$model1->primary_image = $model->primary_image;
		
				$model1->expected_cost = $model->expected_cost;
				$model1->expected_date = $model->expected_date;
				$model1->in_return = $model->in_return;
				$model1->who_can = $model->who_can;
				$model1->state = $model->state;
				$model1->country = $model->country;
				$model1->city = $model->city;
				$model1->non_pay_option = $model->non_pay_option;
				$model1->wish_status = $model->wish_status;				
				$model1->show_mail_status = $model->show_mail_status;
				$model1->show_mail = $model->show_mail;
				$model1->show_person_status = $model->show_person_status;
				$model1->show_person_location = $model->show_person_location;
				$model1->show_person_date = $model->show_person_date;
				$model1->show_reserved_status = $model->show_reserved_status;
				$model1->show_reserved_name = $model->show_reserved_name;
				$model1->show_reserved_location = $model->show_reserved_location;
				$model1->show_reserved_date = $model->show_reserved_date;
				$model1->show_other_status = $model->show_other_status;
				$model1->show_other_specify = $model->show_other_specify;
				$model1->i_agree_decide = $model->i_agree_decide;
				$model1->i_agree_decide2 = $model->i_agree_decide2;
				$model1->process_status = $model->process_status;
				$model1->process_granted_by = $model->process_granted_by;
				$model1->process_granted_date= $model->process_granted_date;			
	
				$model1->update(false);
			}
			else
			{ 				
				$model->save();
			}
			
			$model->sendCreateSuccessEmail(\Yii::$app->user->id);
            return $this->redirect(['account/my-account']);
        } else {
            return $this->render('create', [
                'model' => $model,
				'categories' => $categories,
				'countries' => $countries,
				'states' => $states,
				'cities' => $cities,
				'user' => $user,
				'profile' => $profile
				
            ]);
        }
    }

    /**
     * Updates an existing Wish model.
     * If update is successful, the browser will be redirected to the 'view' page.
     * @param integer $id
     * @return mixed
     */
    public function actionUpdate($id)
    {
        $model = $this->findModel($id);
		$model->scenario = 'update';
		$categories =  ArrayHelper::map(Category::find()->all(), 'cat_id', 'title');
		$countries = \yii\helpers\ArrayHelper::map(\app\models\Country::find()->all(),'id','name');	
		$states = \yii\helpers\ArrayHelper::map(\app\models\State::find()->where(['country_id'=>$model->country ])->all(),'id','name');	
		$cities = \yii\helpers\ArrayHelper::map(\app\models\City::find()->where(['state_id'=>$model->state])->all(),'id','name');	
		$current_image = $model->primary_image;
		
		$user = User::findOne(\Yii::$app->user->id);
		$profile = UserProfile::find()->where(['user_id'=>\Yii::$app->user->id])->one();
		
        if ($model->load(Yii::$app->request->post())) {
			//check for a new image
			$model->primary_image = UploadedFile::getInstance($model, 'primary_image');
			if(!empty($model->primary_image)) {
				if(!$model->uploadImage())
					return;
			}			
			 else
				{					
					if(!empty($model->primary_image_name) && ($model->primary_image_name != $current_image ))
					{
						$model->primary_image = $model->primary_image_name;
					} else {
						$model->primary_image = $current_image;
					}					
				}
			//save model
			
			$model->wished_by = \Yii::$app->user->id;
			$model->save();
			$model->sendUpdateSuccessEmail(\Yii::$app->user->id);
            return $this->redirect(['view', 'id' => $model->w_id]);
        } else {
            return $this->render('update', [
                'model' => $model,
				'categories' => $categories,
				'countries' => $countries,
				'states' => $states,
				'cities' => $cities,
				'user' => $user ,
				'profile' => $profile
            ]);
        }
    }

    /**
     * Deletes an existing Wish model.
     * If deletion is successful, the browser will be redirected to the 'index' page.
     * @param integer $id
     * @return mixed
     */
    public function actionDelete($id)
    {
         $this->findModel($id)->delete();
        return $this->redirect(['index']); 
    }

	
	public function actionAjaxDelete()
    {
		$id = Yii::$app->request->post('id');
		$this->findModel($id)->delete();
    }
	
	
	
	/**
	 * Like a wish
	 * User has to be logged in to like a wish
	 * Param: wish id
	 * @return boolean
	 */
	public function actionLike($w_id,$type)
	{
		if(\Yii::$app->user->isGuest)
			return $this->redirect(['site/login','red_url'=>Yii::$app->request->referrer]);
		$wish = $this->findModel($w_id);
		$activity = Activity::find()->where(['wish_id'=>$wish->w_id,'activity'=>$type,'user_id'=>\Yii::$app->user->id])->one();
		if($activity != null){
			$activity->delete();
			return "removed";
		}
			$activity = new Activity();
		$activity->wish_id = $wish->w_id;
		$activity->activity = $type;
		$activity->user_id = \Yii::$app->user->id;
		if($activity->save())
			return "added";
		else return false;
	}

	public function actionFullfilled($w_id){
		
		$wish = $this->findModel($w_id);
		//explicitly set up the granted_by to the user id
		//listen to the IPN and change back to NULL if not success.
		
		$wish->granted_by = \Yii::$app->user->id;
		$wish->granted_date = date('d-m-Y');

		if($wish->save(false))
		{			
			$this->sendEmail($wish->wished_by);		
			return $this->redirect(['wish/view','id'=>$w_id]);
		}
		
	}
    /**
     * Finds the Wish model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param integer $id
     * @return Wish the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($id)
    {
        if (($model = Wish::findOne($id)) !== null) {
            return $model;
        } else {
            throw new NotFoundHttpException('The requested page does not exist.');
        }
    }
	
	public function actionTopWishers(){
		$query = Wish::find()->select(['wishes.wished_by,count(w_id) as total_wishes'])->orderBy('total_wishes DESC');
		$query->groupBy('wished_by');
        $dataProvider = new ActiveDataProvider([
            'query' => $query,
            'pagination' => [
                'pageSize'=>50
            ]
        ]);
		return $this->render('iWish', [
			'dataProvider' => $dataProvider,
		]);		
	}
	
	public function actionTopGranters(){
		$query = Wish::find()->select(['wishes.granted_by,count(w_id) as total_wishes'])->where(['not', ['granted_by' => null]])->orderBy('total_wishes DESC');
		$query->groupBy('granted_by');
        $dataProvider = new ActiveDataProvider([
            'query' => $query,
            'pagination' => [
                'pageSize'=>50
            ]
        ]);
		return $this->render('iGrant', [
			'dataProvider' => $dataProvider,
		]);		
	}
	
	/**
	 * IPN listener for paypal
	 * Ref: http://stackoverflow.com/questions/14015144/sample-php-code-to-integrate-paypal-ipn
	 * Change the status back to not paid if not veified
	 */
	public function actionVerifyGranted(){
		// STEP 1: Read POST data

		// reading posted data from directly from $_POST causes serialization 
		// issues with array data in POST
		// reading raw POST data from input stream instead. 
		$raw_post_data = file_get_contents('php://input');
			$fh = fopen(Yii::$app->basePath."/web/uploads/paypal_log.txt", "a");
			  fwrite($fh, $raw_post_data);
			  fclose($fh);
		$raw_post_array = explode('&', $raw_post_data);
		$myPost = array();
		foreach ($raw_post_array as $keyval) {
		  $keyval = explode ('=', $keyval);
		  if (count($keyval) == 2)
			 $myPost[$keyval[0]] = urldecode($keyval[1]);
		}
		// read the post from PayPal system and add 'cmd'
		$req = 'cmd=_notify-validate';
		if(function_exists('get_magic_quotes_gpc')) {
		   $get_magic_quotes_exists = true;
		} 
		foreach ($myPost as $key => $value) {        
		   if($get_magic_quotes_exists == true && get_magic_quotes_gpc() == 1) { 
				$value = urlencode(stripslashes($value)); 
		   } else {
				$value = urlencode($value);
		   }
		   $req .= "&$key=$value";
		}


		// STEP 2: Post IPN data back to paypal to validate
		//https://www.ipnpb.sandbox.paypal.com/cgi-bin/webscr
		$ch = curl_init('https://www.ipnpb.sandbox.paypal.com/cgi-bin/webscr');
		//$ch = curl_init('https://www.ipnpb.paypal.com/cgi-bin/webscr');
		curl_setopt($ch, CURLOPT_HTTP_VERSION, CURL_HTTP_VERSION_1_1);
		curl_setopt($ch, CURLOPT_POST, 1);
		curl_setopt($ch, CURLOPT_RETURNTRANSFER,1);
		curl_setopt($ch, CURLOPT_POSTFIELDS, $req);
		curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, 1);
		curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, 2);
		curl_setopt($ch, CURLOPT_FORBID_REUSE, 1);
		curl_setopt($ch, CURLOPT_HTTPHEADER, array('Connection: Close'));

		// In wamp like environments that do not come bundled with root authority certificates,
		// please download 'cacert.pem' from "http://curl.haxx.se/docs/caextract.html" and set the directory path 
		// of the certificate as shown below.
		// curl_setopt($ch, CURLOPT_CAINFO, dirname(__FILE__) . '/cacert.pem');
		if( !($res = curl_exec($ch)) ) {
			// error_log("Got " . curl_error($ch) . " when processing IPN data");
			curl_close($ch);
			exit;
		}
		curl_close($ch);


		// STEP 3: Inspect IPN validation result and act accordingly

		if (strcmp ($res, "VERIFIED") == 0) {
			// check whether the payment_status is Completed
			// check that txn_id has not been previously processed
			// check that receiver_email is your Primary PayPal email
			// check that payment_amount/payment_currency are correct
			// process payment
			$payment = new Payment();
			// assign posted variables to local variables
			$payment->item_name = $_POST['item_name'];
			$payment->item_number = $_POST['item_number'];
			$payment->payment_status = $_POST['payment_status'];
			$payment->payment_amount = $_POST['mc_gross'];
			$payment->payment_currency = $_POST['mc_currency'];
			$payment->txn_id = $_POST['txn_id'];
			$payment->receiver_email = $_POST['receiver_email'];
			$payment->payer_email = $_POST['payer_email'];
			$payment->payment_date = $_POST['payment_date'];
			$payment->save();
			//check if success
			$wish = $this->findModel($_POST['item_number']);
			//if not fully paid or if not successful, revert the granted status
			if($payment->payment_status != "Completed"){
				$wish->granted_by = NULL;
				$wish->save();
			}

		} else if (strcmp ($res, "INVALID") == 0) {
			  // Save the output (to append or create file)
			  $fh = fopen(Yii::$app->basePath."/web/uploads/paypal_log.txt", "a");
			  fwrite($fh, $res);
			  fclose($fh);
			// log for manual investigation
		}		
	}
	
	public function actionRemoveWish($wish_id)
	{
		if(\Yii::$app->user->isGuest)
			return $this->redirect(['site/login','red_url'=>Yii::$app->request->referrer]);
		$wish = $this->findModel($wish_id);
		$activity = Activity::find()->where(['wish_id'=>$wish->w_id,'activity'=>'fav','user_id'=>\Yii::$app->user->id])->one();
		if($activity != null){
			$activity->delete();
		}
		
		 return $this->redirect(['account/my-saved']);
	}
	
	public function sendEmail($id)
    {
		
		$mailcontent = MailContent::find()->where(['m_id'=>5])->one();
		$editmessage = $mailcontent->mail_message;		
		$subject = $mailcontent->mail_subject;
		if(empty($subject))
			$subject = 	'SimplyWishes ';
		
        /* @var $user User */
        $user = User::findOne([
            'status' => User::STATUS_ACTIVE,
            'id' => $id,
        ]);
			
        if (!$user) {
            return false;
        }
      
        $message = Yii::$app
            ->mailer
            ->compose(
                ['html' => 'grantedSuccess-html'],
                ['user' => $user, 'editmessage' => $editmessage ]
            )
            ->setFrom([Yii::$app->params['supportEmail'] => 'SimplyWishes '])
            ->setTo($user->email)
            ->setSubject($subject);			
            
		$message->getSwiftMessage()->getHeaders()->addTextHeader('MIME-version', '1.0\n');
		$message->getSwiftMessage()->getHeaders()->addTextHeader('charset', ' iso-8859-1\n');
		
		return $message->send();
    }
	
	public function actionReport($w_id)
	{
		if(\Yii::$app->user->isGuest)
			return $this->redirect(['site/login','red_url'=>Yii::$app->request->referrer]);
		$wish = $this->findModel($w_id);
		$activity = ReportWishes::find()->where(['w_id'=>$wish->w_id])->one();
		if($activity){
			$activity->count = $activity->count + 1;
			$activity->save();
			return "added";			
		} else {
		$activity = new ReportWishes();
		$activity->w_id = $wish->w_id;
		$activity->count = 1;	
		$activity->save();
			return "added";
		}
	}	
	
	public function actionReportAction()
	{
	 if(isset(\Yii::$app->user->identity->role) && (\Yii::$app->user->identity->role == 'admin')){ 
		$searchModel = new SearchReportWishes();
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams);
        return $this->render('report_view', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider
        ]);
	 } else {
		 return $this->redirect(['site/index']);
	 }
	}
	
	
	public function actionReportActionView($id)
    {
		if(isset(\Yii::$app->user->identity->role) && (\Yii::$app->user->identity->role == 'admin')){ 
			$wish = Wish::find()->where(['w_id'=>$id])->one();		
			if($wish)
			{			
				return $this->render('report_full_view', ['model' => $this->findModel($id) ]);
			} else {
					return $this->redirect('report-action');
			}	
		} else {
				 return $this->redirect(['site/index']);
			 }		
    }

	
	public function actionReportDelete()
    {
	  if(isset(\Yii::$app->user->identity->role) && (\Yii::$app->user->identity->role == 'admin')){ 
			$id = Yii::$app->request->post('id');
			$this->findModel($id)->delete();
			$model = ReportWishes::find()->where(['w_id'=>$id])->one();
			$model->delete();
		 } else {
		 return $this->redirect(['site/index']);
	  }
    }
	
	 public function actionUploadFile(){

            $name =time().$_FILES['file']['name'];
			$name = preg_replace('/\s+/', '', $name);
			
  		    $filetype = array('jpeg','jpg','png','gif','bmp','tif','tiff');
            $path=\Yii::$app->basePath.'/web/uploads/'.$name;
            $file_ext =  pathinfo($name, PATHINFO_EXTENSION);
            if(in_array(strtolower($file_ext), $filetype))
            {
			  $filepath = $tmp_filename = $_FILES['file']['tmp_name'];
              @move_uploaded_file($tmp_filename,$path);
			  
			 return 'uploads/'.$name;
  		  }

  	}
	
	public function actionWishAutosave()
    {
		
		if(Yii::$app->request->post())
		{
			
			$models2 = Yii::$app->request->post();			
			$models =  Wish::find()->where(['w_id'=>$models2['Wish']['auto_id']])->one();
			$models->category = $models2['Wish']['category'];
			$models->wish_title = $models2['Wish']['wish_title'];		
			$models->wish_description = $models2['Wish']['wish_description'];
			
			$models->primary_image = $models2['Wish']['primary_image_name'];
		
			$models->expected_cost = $models2['Wish']['expected_cost'];
			$models->expected_date = $models2['Wish']['expected_date'];
			$models->in_return = $models2['Wish']['in_return'];
			$models->who_can = $models2['Wish']['who_can'];
			$models->state = $models2['Wish']['state'];
			$models->country = $models2['Wish']['country'];
			$models->city = $models2['Wish']['city'];
			$models->non_pay_option = $models2['Wish']['non_pay_option'];
		
		
				$models->show_mail_status = $models2['Wish']['show_mail_status'];
				$models->show_mail = $models2['Wish']['show_mail'];
				$models->show_person_status = $models2['Wish']['show_person_status'];
				$models->show_person_location = $models2['Wish']['show_person_location'];
				$models->show_person_date = $models2['Wish']['show_person_date'];
				$models->show_reserved_status = $models2['Wish']['show_reserved_status'];
				$models->show_reserved_name = $models2['Wish']['show_reserved_name'];
				$models->show_reserved_location = $models2['Wish']['show_reserved_location'];
				$models->show_reserved_date = $models2['Wish']['show_reserved_date'];
				$models->show_other_status = $models2['Wish']['show_other_status'];
				$models->show_other_specify = $models2['Wish']['show_other_specify'];
				
			
			
			if($models->save(false))
			{
				
				echo "Success1";
			}
			else
			{				
				echo "failed2";
			}
			
		} else 
		{
			$models = new Wish();
			$models->wished_by = \Yii::$app->user->id;
			$models->wish_status = 1;
			$models->save(false);
			$models->auto_id = $models->w_id;
			return json_encode($models->auto_id);
		}
		
    }
	
	
		/**
     * Lists all Editorial models.
     * @return mixed
     */
    public function actionMyDrafts()
    {
        $searchModel = new SearchWish();
        $dataProvider = $searchModel->searchDrafts(Yii::$app->request->queryParams);

        return $this->render('my_drafts', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
        ]);
    }
	
	
	 public function actionDeleteDraft($id)
    {
		$this->findModel($id)->delete();
        return $this->redirect(['my-drafts']); 
    }
	
	 public function actionViewDraft($id)
    {
        return $this->render('view_draft', [
            'model' => $this->findModel($id),
        ]);
    }
	
	
	 public function actionUpdateDraft($id)
    {
   	
		 $model = $this->findModel($id);
		$model->scenario = 'update';
		$categories =  ArrayHelper::map(Category::find()->all(), 'cat_id', 'title');
		$countries = \yii\helpers\ArrayHelper::map(\app\models\Country::find()->all(),'id','name');	
		$states = \yii\helpers\ArrayHelper::map(\app\models\State::find()->where(['country_id'=>$model->country ])->all(),'id','name');	
		$cities = \yii\helpers\ArrayHelper::map(\app\models\City::find()->where(['state_id'=>$model->state])->all(),'id','name');	
		$current_image = $model->primary_image;
		
		$user = User::findOne(\Yii::$app->user->id);
		$profile = UserProfile::find()->where(['user_id'=>\Yii::$app->user->id])->one();
		
        if ($model->load(Yii::$app->request->post())) {
			//check for a new image
			$model->primary_image = UploadedFile::getInstance($model, 'primary_image');
			if(!empty($model->primary_image)) {
				if(!$model->uploadImage())
					return;
			}
			else
				$model->primary_image = $current_image;
			//save model
			
			$model->wished_by = \Yii::$app->user->id;
			$model->wish_status = 0;
			$model->save();
			$model->sendUpdateSuccessEmail(\Yii::$app->user->id);
            return $this->redirect(['my-drafts']);
        } else {
            return $this->render('update_draft', [
                'model' => $model,
				'categories' => $categories,
				'countries' => $countries,
				'states' => $states,
				'cities' => $cities,
				'user' => $user ,
				'profile' => $profile
            ]);
        }
		
    }
	
	 public function actionProcessWish()
    {
		
        $w_id = \Yii::$app->request->post()['wish_id'];
		$processstatus = \Yii::$app->request->post()['processstatus'];
		$send_message = \Yii::$app->request->post()['send_message'];
		
		$wish = $this->findModel($w_id);
		//explicitly set up the granted_by to the user id
		//listen to the IPN and change back to NULL if not success.		
		$wish->process_granted_by = \Yii::$app->user->id;
		$wish->process_granted_date = date('Y-m-d');
		$wish->process_status = $processstatus;

		if($wish->save(false))
		{		
			
			if($send_message == 1)			
				$wish->sendGrantWishNonFinancialEmail(\Yii::$app->user->id,$wish);		
			echo "Success";
		}
		
    }
		
	 public function actionResubmitProcessWish()
    {
		
        $w_id = \Yii::$app->request->post()['wish_id'];
		$userid = \Yii::$app->request->post()['userid'];
		
	if(isset(\Yii::$app->user->identity->role) && (\Yii::$app->user->identity->role == 'admin'))		
		$wish =  Wish::find()->where(['w_id'=>$w_id])->one();
	else
		$wish =  Wish::find()->where(['w_id'=>$w_id,'wished_by'=>$userid])->one();

		if($wish)
		{
			$wish->process_granted_by = "";
			$wish->process_granted_date = "0000-00-00";
			$wish->process_status = 0;
			$wish->email_status = 0;

			if($wish->save(false))
			{		
				echo "Success";
			}
		}
		
    }
	
    public function actionGrantProcessWish()
    {	
		 $w_id = \Yii::$app->request->post()['wish_id'];
		$userid = \Yii::$app->request->post()['userid'];
		
		if(isset(\Yii::$app->user->identity->role) && (\Yii::$app->user->identity->role == 'admin'))		
		  $wish =  Wish::find()->where(['w_id'=>$w_id])->one();
		else
		  $wish =  Wish::find()->where(['w_id'=>$w_id,'wished_by'=>$userid])->one();

		if($wish)
		{
			$wish->granted_by = $wish->process_granted_by;
			$wish->granted_date = date('d-m-Y');
			$wish->process_granted_by = "";
			$wish->process_granted_date = "0000-00-00";
			$wish->process_status = 0;

			if($wish->save(false))
			{			
				$this->sendEmail($wish->wished_by);		
				return $this->redirect(['wish/view','id'=>$w_id]);
			}
		}	
	}	
	
	 public function actionMultiDeleteWishes()
	 {    

			$w_id = Yii::$app->request->post()['w_id'];	
			if($w_id)
			{
				 foreach($w_id as $tmp)
				 {										
					$this->findModel($tmp)->delete();
				 }
			}  			
	}
	
}
