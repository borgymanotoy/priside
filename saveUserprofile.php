<?
    $user="root";
    $password="1234567890";
    $database="dummy_db";
    mysql_connect(localhost,$user,$password);
    @mysql_select_db($database) or die( "Unable to select database");
    
    $fname = $_POST['fname'];
    $lname = $_POST['lname'];
    $mname = $_POST['mname'];
    $email1 = $_POST['email1'];
    $contact1 = $_POST['contact1'];
    
    #$query="CREATE TABLE contacts (id int(6) NOT NULL auto_increment,first varchar(15) NOT NULL,last varchar(15) NOT NULL,phone varchar(20) NOT NULL,mobile varchar(20) NOT NULL,fax varchar(20) NOT NULL,email varchar(30) NOT NULL,web varchar(30) NOT NULL,PRIMARY KEY (id),UNIQUE id (id),KEY id_2 (id))";
    
    $query = "insert into user_profile(first_name, middle_name, last_name, email_add1, contact_no, date_added, last_modified) values ('$fname', '$mname', '$lname', '$email1', '$contact1', now(), now())";
    
    mysql_query($query);
    mysql_close();
?>