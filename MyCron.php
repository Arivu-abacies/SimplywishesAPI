<?php

//file_put_contents( 'HelloController.php', '*/15 * * * * /usr/local/bin/php -q ///HelloController.php' );
//exec( 'crontab HelloController.php' );
    

//exec('wget http://localhost/simplywishes/commands/HelloController.php');
//exec('wget http://localhost/simplywishes/controllers/CronController.php');
//exec('php /localhost/simplywishes/controllers/CronController.php');

//exec("php /localhost/simplywishes/controllers/CronController.php > /dev/null");



public function actionTest(){
    $oldApp = \Yii::$app;
    $console = new \yii\console\Application([
        'id' => 'basic-console',
        'basePath' => '@app/commands',
        'components' => [
            'db' => $oldApp->db,
        ],
    ]);
    \Yii::$app->runAction('hello/index');
    \Yii::$app = $oldApp;
}



