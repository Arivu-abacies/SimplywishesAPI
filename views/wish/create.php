<?php

use yii\helpers\Html;


/* @var $this yii\web\View */
/* @var $model app\models\Wish */

$this->title = 'Add a Wish';
$this->params['breadcrumbs'][] = ['label' => 'Wishes', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>

<?php echo $this->render('@app/views/account/_profilenew',['user'=>$user,'profile'=>$profile])?>

<div class= " col-md-8 wish-create">

    <h1  class="fnt-green" ><?= Html::encode($this->title) ?></h1>

    <?= $this->render('_form', [
        'model' => $model,
		'categories' => $categories,
		'countries' => $countries,
		'states' => $states,
		'cities' => $cities
    ]) ?>

</div>
</div>

<script type="text/javascript">
 
 $( document ).ready(function() {
	 $( "#draft_form" ).click(function() {	
			var auto_id = $("#wish-auto_id").val();

			if($.trim(auto_id) === "")
			{
				  $.post( "wish-autosave", function( data ) {
					$("#wish-auto_id").val(data);
					});	
			}
			$.post("wish-autosave", $("form").serialize());
		});
 });
 
</script>
