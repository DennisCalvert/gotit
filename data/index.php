<?php
require_once('../auth/enable.php');

class DAL 
{
    private $conn;

    function __construct()
    {
        try
        {
            $this->conn = new PDO( getenv( 'GotItDataSrc' ), getenv( 'GotItDataUser'), getenv( 'GotItDataPass') );                        
                        
            $this->conn->setAttribute( PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION );            
        }

        catch ( PDOException $e )
        {
            print( 'Error connecting to SQL Server.' );

            die(print_r($e));
        }
    }

    function delete($id)
    {        
        $stmt = $this->conn->prepare( 'DELETE FROM gotit WHERE ID = :id' );  
              
        $stmt->execute( array( 'id' => $id ) );
    }

    function get()
    {                
        $result = array();

        $stmt = $this->conn->prepare( 'SELECT * FROM gotit' );

        $stmt->execute();

        while ( $row = $stmt->fetch( PDO::FETCH_ASSOC ) )
        {        
            $result[] = $row;
        }        

        return $result;
    }

    function post($name, $details, $id )
    {
        if( is_numeric($id) )
        {
            $stmt = $this->conn->prepare("UPDATE gotit SET name = :name, details = :details WHERE ID = :ID");

            $stmt->bindParam(':ID', $id);

        } 
        else 
        {
            $stmt = $this->conn->prepare("INSERT INTO gotit (name, details) OUTPUT Inserted.ID VALUES (:name, :details)");               
        }        

        $stmt->bindParam( ':name', $name );

        $stmt->bindParam( ':details', $details );    

        $stmt->execute();

        $model = array(
            "id" => $stmt->fetch( PDO::FETCH_ASSOC ),
            "name" => $name,
            "details" => $details
        );
        
        return $model;        
    }
}


$data = json_decode(file_get_contents("php://input"));        

$db = new DAL;

$verb = $_SERVER['REQUEST_METHOD'];

if( $verb == 'GET' ) $response = $db->get();

if( $verb == 'POST' && is_object( $data ) ) $response = $db->post( $data->name, $data->details, $data->id );

if( $verb == 'DELETE' && is_object( $data ) ) $response = $db->delete( $data->id );

if( isset( $response )  )
{
    header( 'Cache-Control: no-cache, must-revalidate' ); // HTTP/1.1
    header( 'Expires: Sat, 26 Jul 1997 05:00:00 GMT' ); // Date in the past
    header( 'Content-Type: application/json' );
    echo json_encode( $response );   
}
?>