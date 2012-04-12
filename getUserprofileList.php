<?

    $dsn = "mysql:host=phpmyadmin.tsab.local;dbname=dummy_db";
    $username = "root";
    $password = "1234567890";
    #$pdo = new PDO($dsn, $username, $password);

    #Start: test
    $database="dummy_db";
    mysql_connect(localhost,$username,$password);
    @mysql_select_db($database) or die( "Unable to select database");

    $sql="SELECT * FROM show_contacts";
    $rows = mysql_query($sql);
    #End: Test
    
    #$rows = array();
    #$searchKey = $_POST['search_key']
    #if(isset($searchKey)) {
    #    $stmt = $pdo->prepare("select * from show_contacts where name like :searchKey");
    #    $stmt->execute(array(':searchKey' => '%'.$searchKey.'%'));
    #    $rows = $stmt->fetchAll(PDO::FETCH_ASSOC);
    #}

    

    echo json_encode($rows);

?>