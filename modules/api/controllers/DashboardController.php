<?php

namespace app\modules\api\controllers;

use Yii;
use yii\data\ActiveDataProvider;
use yii\rest\ActiveController;
use yii\filters\auth\HttpBearerAuth;
use app\models\Dashboard as DashboardModel;
use app\models\Workspace as WorkspaceModel;
use app\models\Collection as CollectionModel;
use app\models\Reports as ReportsModel;
use yii\web\UploadedFile;
use app\models\User;
use app\models\Customer;

class DashboardController extends ActiveController
{
    public $modelClass = 'app\models\Dashboard';
	
	public function behaviors()
	{
		$behaviors = parent::behaviors();
		$behaviors['authenticator'] = [
			'class' => HttpBearerAuth::className(),
		];
		return $behaviors;
	}
	public function actions()
	{
		$actions = parent::actions();
		// disable the "index" actions
		unset($actions['index']);
		return $actions;
	}
	
	/*
	 * Action for getting dashboards
	 * Params: $page.If null, first 10 results will be showed
	 * Returns Json
	 */
	Public function actionIndex($page=null){
        $query = DashboardModel::find();
		$dataProvider = new ActiveDataProvider([
            'query' => $query,
			'pagination' => [
				'pageSize' => '10',
				'page' => $page,
			],
        ]);
		return $dataProvider;
	}
	
	/*
	 * Action for getting single dashboard embed details
	 * Params: $id which is the dashboard id
	 * Returns Json
	 */
	Public function actionEmbed($id){
		//Return, array of collection name,access key, workspace id, report id
		$dashboard 		= DashboardModel::findOne($id);
		$embed = [
			'collection_name'=>$dashboard->workspace->collection->collection_name,
			'access_key'	 =>$dashboard->workspace->collection->AppKey,
			'workspace_id'	 =>$dashboard->workspace->workspace_id,
			'report_id'	 	 =>$dashboard->report->report_guid,
		];  
		return $embed;
	}
	
	/*
	 * Action for importing data to the dashboard
	 * Params: $dashboard_id,$cutomer_id , file to be imported
	 * Returns Json
	 */	
	public function actionImport($dashboard_id){
		
		$model =  DashboardModel::findOne($dashboard_id);
		//print_r($_FILES['file']);die;
		$model->file = UploadedFile::getInstanceByName('file');
		$model->file->saveAs(\Yii::$app->basePath."/web/uploads/" . $model->file->baseName . '.' . $model->file->extension);
		//process excel
		$data = \app\components\PBI_Excel::import(\Yii::$app->basePath."/web/uploads/". $model->file->baseName . '.' . $model->file->extension, [
			'setFirstRecordAsKeys' => true, 
			'setIndexSheetByName' => true, 
		]);
		//To remove empty sheets
		$data=array_filter(array_map('array_filter', $data));
		foreach($data as $key=>$sheet){
			foreach($sheet as $header=>$data){
				foreach($data as $column=>$d){
					//eliminate the null keys
					if($key == '')
						unset($data[$column]);
				}
				$data['eq_customer_id'] = $_POST['customer_id'];
				\Yii::$app->db->createCommand()
					->insert($model->prefix."_".$key, $data)->execute();
			} 		
		}
	}
	
	
	/*
	 * Action for getting update dashboard Form Generator Details
	 * Params: $value which is the dashboard Form Generator value
	 * Returns JSON
	 */
	 // Pending Function
	Public function actionUpdateFormGenerator(){
		
		$tablename = "2_Risk";
		$tableSchema = Yii::$app->db->schema->getTableSchema($tablename);
		if ($tableSchema === null) {
			return ["Error"=>"Table Does Not Exits"];			
		} else {
			/* $columnNames = Yii::$app->db->schema->getTableSchema($tablename)->getColumnNames();		
			return $columnNames;  */
			
			$newcpy_blockid = 10;

			
			
			$data['Risk Score'] = 21700;
			
			
			$connection = Yii::$app->getDb(); 
			$connection	->createCommand()
			->update($tablename,$data, "eq_id=".$newcpy_blockid." and eq_customer_id=".\Yii::$app->user->id)
			->execute();
			
			//21700
			return ["Success"=>"Success Table processed"];
			
		}
	}
	
	/*
	 * Action for getting delete dashboard Form Generator Details
	 * Params: $value which is the dashboard Form Generator value
	 * Returns JSON
	 */
	// Pending Function
	Public function actionDeleteFormGenerator(){
		$tablename = "2_Risk";
		$tableSchema = Yii::$app->db->schema->getTableSchema($tablename);
		if ($tableSchema === null) {
			return ["Error"=>"Table Does Not Exits"];			
		} else {
			/* $columnNames = Yii::$app->db->schema->getTableSchema($tablename)->getColumnNames();		
			return $columnNames;  */
			
			$newcpy_blockid = 8;
			
			$connection = Yii::$app->getDb(); 
			$connection	->createCommand()
			->delete($tablename,  "eq_id=".$newcpy_blockid." and eq_customer_id=".\Yii::$app->user->id)
			->execute();
			
			return ["Success"=>"Success Table processed"];
		}

	}

}