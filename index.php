<?php
//header("Access-Control-Allow-Origin: http://localhost:8888");
require 'vendor/autoload.php';
// require 'Slim/Slim.php';

// \Slim\Slim::registerAutoloader();
$app = new \Slim\Slim ();

// GET route
$app->get ( '/', function () {
    $sql = "select * from school_course order by course_id desc limit 10";
    try {
        $pdo = getConnect ();
        $stmt = $pdo->query ( $sql );
        $data = $stmt->fetchAll ( PDO::FETCH_ASSOC );
        $pdo = null;
        // json不支持中文,使用前先转码
        foreach ( $data as $key => $value ) {
            foreach ( $value as $k => $v ) {
                $data [$key] [$k] = urlencode ( $v );
            }
        }
        echo urldecode ( json_encode ( $data ) );
    } catch ( PDOException $e ) {
        echo '{"err":' . $e->getMessage () . '}';
    }
} );

$app->get ( '/school/:id', function ($id) {
    $sql = "select * from school_course where school_id=:id";
    $pdo = getConnect ();
    $st = $pdo->prepare($sql);
    $st->bindParam(":id",$id);
    $result = $st->execute();
    $row = $st->fetchAll(PDO::FETCH_ASSOC);
    echo json_encode($row);
} );


// POST route
$app->post ( '/post', function () {
    echo 'This is a POST route';
} );

// PUT route
$app->put ( '/put', function () {
    echo 'This is a PUT route';
} );

// PATCH route
$app->patch ( '/patch', function () {
    echo 'This is a PATCH route';
} );

// DELETE route
$app->delete ( '/delete', function () {
    echo 'This is a DELETE route';
} );

// 连接数据库
function getConnect($h = 'localhost', $u = "root", $p = "root", $db = "myedu_rest") {
    $pdo = new PDO ( "mysql:host=$h;dbname=$db", $u, $p, array (
            PDO::MYSQL_ATTR_INIT_COMMAND => "set names utf8" 
    ) );
    $pdo->setAttribute ( PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION );
    
    return $pdo;
}
/**
 * Step 4: Run the Slim application
 *
 * This method should be called last. This executes the Slim application
 * and returns the HTTP response to the HTTP client.
 */
$app->run ();


