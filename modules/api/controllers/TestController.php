<?php 

namespace app\modules\api\controllers;

use yii\rest\ActiveController;

/**
 * Default controller for the `v1` module
 */
class TestController extends ActiveController
{
	public $modelClass = 'app\models\User';
    /**
     * Renders the index view for the module
     * @return string
     */
    public function actionApi()
    {

		$countries2 = $_REQUEST['action'];
		
		return $this->countries2();	
		
		
    }
	
	function test()
	{
		return "test";
	}
	
	function test1()
	{
		return "test1";
	}
	
}


?>