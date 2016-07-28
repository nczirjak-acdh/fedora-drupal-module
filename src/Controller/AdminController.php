<?php
/**
@file
Contains \Drupal\oeaw\Controller\AdminController.
 */

namespace Drupal\oeaw\Controller;

use Drupal\Core\Controller\ControllerBase;
use Drupal\Core\Url;
use Drupal\Core\Link;
use Drupal\oeaw\oeawStorage;
use Drupal\oeaw\xsltProcessor;
use Drupal\Core\Session\AccountInterface;
use Symfony\Component\HttpFoundation\Request;
use Drupal\Component\Utility\SafeMarkup;



class AdminController extends ControllerBase 
{

    function contentOriginal() 
    {
        $url = Url::fromRoute('oeaw_add');
        //$add_link = ;
        //$add_link = '<p>' . \Drupal::l(t('New message'), $url) . '</p>';

        // Table header
        $header = array( 'id' => t('Id'), 'name' => t('Submitter name'), 'message' => t('Message'), 'operations' => t('Delete'), );

        $rows = array();
        foreach(oeawStorage::getAll() as $id=>$content) 
        {
            // Row with attributes on the row and some of its cells.
            $rows[] = array( 'data' => array($id, $content->name, $content->message, l('Delete', "admin/content/oeaw/delete/$id")) );
        }

        $table = array( '#type' => 'table', '#header' => $header, '#rows' => $rows, '#attributes' => array( 'id' => 'bd-contact-table', ), );
        return $add_link . drupal_render($table);
    }

    function content() 
    {
        $url = Url::fromRoute('oeaw_add');
        //$add_link = ;
        $add_link = '<p>' . \Drupal::l(t('New message'), $url) . '</p>';

        $text = array(
          '#type' => 'markup',
          '#markup' => $add_link,
        );

        // Table header.
        $header = array(
          'id' => t('Id'),
          'name' => t('Submitter name'),
          'message' => t('Message'),
          'operations' => t('Delete'),
        );
        $rows = array();
        foreach (oeawStorage::getAll() as $id => $content) {
          // Row with attributes on the row and some of its cells.
          $editUrl = Url::fromRoute('oeaw_edit', array('id' => $id));
          $deleteUrl = Url::fromRoute('oeaw_delete', array('id' => $id));

          $rows[] = array(
            'data' => array(
              \Drupal::l($id, $editUrl),
              $content->name, $content->message,
              \Drupal::l('Delete', $deleteUrl)
            ),
          );
        }
        $table = array(
          '#type' => 'table',
          '#header' => $header,
          '#rows' => $rows,
          '#attributes' => array(
            'id' => 'bd-contact-table',
          ),
        );
        //return $add_link . ($table);
        return array(
          $text,
          $table,
        );
    }
  
    public function getDataByCurl($sql = null, $bigdataUrl = null)
    {
    
        $r = curl_init();
        curl_setopt_array($r, array(
                                    CURLOPT_HTTPGET => true,
                                    CURLOPT_RETURNTRANSFER => true,
                                    CURLOPT_HTTPHEADER => array('Accept:text/csv'),
                                    CURLOPT_URL => 'http://localhost:8080/bigdata/sparql?query=' . urlencode('select * where { ?s rdf:type ?o . }')));
        $data = trim(curl_exec($r));	
        curl_close($r);
        $data = explode("\n", $data);
        array_shift($data);
        
        return $data;
      
    }
    public function resource_frontend(AccountInterface $user, Request $request) 
    {    
        die("itt");
        die(print_r($url));
    }
    
    
    
    public function checkObjClass($obj)
    {
        $objClass = get_class($obj);
        //$p = (array)$obj;
        
        foreach((array)$obj as $key => $value)
        {
            switch ($objClass) {
                case 'EasyRdf_Resource':
                    $result = $value;
                break;
                        
            default:
                $result="sss";            
        }
     
            print_r($value);
        die();
        return $result;    
    }
        
        
    }
    
    public function createTable($header, $data, $uri = null)
    {
   
       foreach($header as $key => $value)
       {               
            foreach($data as $row) 
            {            
                $row =  (array) $row;
                $p = (array)$row[$key[0]];
                /*echo "<pre>";
                var_dump($asd = $row[$key[0]]); 
                echo "</pre>";              
die();          */
                $asd = $this->checkObjClass($row[$key[0]]);
                echo "<pre>";
                var_dump($asd); 
                echo "</pre>";              
            
                $link = Link::fromTextAndUrl('HTML', Url::fromUri('http://localhost/drupal8/oeaw_list/1'));
            
                $rows[] = array(
                        'data' => array( 'sss', $row['p'], $row['o'] ),
                        );
            }            
        }
        
           
            die();
         $table = array(
            '#type' => 'table',
            '#header' => $header,
            '#rows' => $rows,
            '#attributes' => array(
            'id' => 'oeaw-table',
            ),
        );

        return $table;
    }
    
    public function all_list()
    {
        
        //$data = oeawStorage::getAllSparqlData();
        $data = oeawStorage::getSparqlDataByUri("http://localhost:8080/fcrepo/rest/8f/6e/4f/9f/8f6e4f9f-ab6c-46b7-93c9-2c7059515986");
        
        if(empty($data))
        {
           return false; 
        }
                
        $header = array(
            'p' => t('S'),
            's' => t('P'),
            'o' => t('O'),
        );   
        $table = $this->createTable($header, $data, $uri);
       
        //var_dump($table);
        
         return array(
            $text,
            $table,
        );
        die("sss");
        
    }
    
    public function resource_list()
    {
        die("resource_list");
    }
    
    public function new_resource()
    {
        die("new_resource");
    }
  
    function frontend_list()
    {
        
        // Table header.
        $header = array(
          'id' => t('URLF1')
        );        
        $rows = array();
        
        $link = Link::fromTextAndUrl('ALL',  Url::fromRoute('oeaw_all_list'));       
            
        $rows[0] = array(
                    'data' => array(              
                                $link                                
                                    ),
                    );
            
        
        $link1 = Link::fromTextAndUrl('Resources', Url::fromRoute('oeaw_resource_list'));
        $rows[1] = array(
                    'data' => array(              
                                $link1
                                    ),
                    );
        
        $link2 = Link::fromTextAndUrl('Add New', Url::fromRoute('oeaw_new_resource'));
        $rows[2] = array(
                    'data' => array(              
                                $link2
                                    ),
                    );
        
        $table = array(
            '#type' => 'table',
            '#header' => $header,
            '#rows' => $rows,
            '#attributes' => array(
            'id' => 'oeaw-table',
            ),
        );

        return array(
            $text,
            $table,
        );
    }
        
    
    
    
    function content_frontend() 
    {   
        // Table header.
        $header = array(
          'id' => t('URL'),          
          'name' => t('RES'),
          'message' => t('RDF:Type')
          
        );
        $rows = array();

        //$data = $this->getDataByCurl();
        
        

        foreach($data as $row) {

            $row = explode(',', $row);
            
            // uncomment by Norbi
            //$xsltData =  xsltProcessor::getData($row[1], $row[0]);
            //uncomment by norbi
            //$link = Link::fromTextAndUrl('HTML VIEW',  Url::fromUri($row[2].'?dataURL='.$row[0].'&xsltURL='.$row[1]));
            //$link = Link::fromTextAndUrl('HTML VIEW',  Url::fromUri("http://localhost/drupal8/oeaw_list/".$row[0]));
            
            $link = Link::fromTextAndUrl('HTML', Url::fromUri('http://localhost/drupal8/oeaw_list/1'));
            
            //$link->getUrl()->setOption('attributes', ['target' => '_blank']);
           // $link = "http://google.hu";
            
            $rows[] = array(
                        'data' => array(              
                                    $link,
                                    $row[0], $row[1],                                    
                                        ),
                        );
        }
        $table = array(
            '#type' => 'table',
            '#header' => $header,
            '#rows' => $rows,
            '#attributes' => array(
            'id' => 'bd-contact-table',
            ),
        );

        return array(
            $text,
            $table,
        );
    }
  
}
