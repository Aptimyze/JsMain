<?php
//include('/var/www/html/sfproject/lib/model/lib/DBConnection.class.php');
/**
 * testMod actions.
 *
 * @package    MYPROJECT
 * @subpackage testMod
 * @author     Your name here
 * @version    SVN: $Id: actions.class.php 23810 2009-11-12 11:07:44Z Kris.Wallsmith $
 */

class bharmoduleActions extends sfActions
{
 /**
  * Executes index action
  *
  * @param sfRequest $request A request object
  */
  public function executeIndex(sfWebRequest $request)
  {   
    $db = new newjs_bharat();
    if($request->isMethod('post')){
        $name =   $request->getPostParameter('name'); 
        //die("bharat149");
        $result = $db->select($name);
        //die("bharat149");
        $length = count($result);
        print_r($length);
        for($i=0;$i<$length;$i++){
            echo $result[$i]['email'];
        }
    }

    if($request->isMethod('post')){
      if($request->getPostParameter('delete')){
         $name =   $request->getPostParameter('name');
         $db->delete($name);
      }
               
    } 
  }

  public function executeProfile(sfWebRequest $request){
    $db = new newjs_bharat();
    
    if($request->isMethod('post') && isset($_POST['submit'])){
      $name = $request->getPostParameter('name');
      $email = $request->getPostParameter('email');
      $website = $request->getPostParameter('website');
      $comment = $request->getPostParameter('comment');
      $gender = $request->getPostParameter('gender');
      if($name == ""){
        echo "Name is required";
      }else if($email == ""){
        echo "Email is required";
      }else if($gender == ""){
        echo "Gender is required";
      }else{
        $db->insert($name,$email,$website,$comment,$gender);
      }
    }
  }

  public function executeCookies(sfWebRequest $request){
    
    $myemail = "bharat149";
    $mypassword = "12345";
    if( $request->isMethod('post') ){
      //echo "bharat";
      $email = $request->getPostParameter('email');
      $password = $request->getPostParameter('password');
      $remember = $request->getPostParameter('remember');
      if($email == $myemail && $password == $mypassword){

         if(isset($_POST['remember'])){
          setcookie('email',$email,time()+600*60+2);
          setcookie('password',$password,time()+600*60+2);
          //echo "cookies saved successfully";     
         }
          sfContext::getInstance()->getRequest()->setAttribute('email',"bharat");
          $result =  sfContext::getInstance()->getRequest()->getAttribute("email");
          echo $result;
         //$this->getUser()->setAttribute('email',"bcbcb");
         //echo  $this->getUser()->getAttribute('email');
         //$result = $this->getUser();
         //print_r($result);
         //$this->getUser()->setAttribute('password',$password);
          //$this->get('session')->set('email', $email);
      } else{
        echo "Invalid Credential";
      }
    } else{
      setcookie('email',$email,time()-1);
      setcookie('password',$password,time()-1);
    }
  }

  public function executeValidate(sfWebRequest $request){
      
      echo "Using session varibale in different page <br>";
      $result =  sfContext::getInstance()->getRequest()->getAttribute("email");
      //print_r(sfContext::getInstance()->getUser());
      echo $result;   
      //$this->get('session')->get('email');
      //$result =   $this->getUser()->getAttribute('email');
      //$result = $this->getUser()->getAttribute('email');
      //die("123");
      //print_r($result);
    }

}
