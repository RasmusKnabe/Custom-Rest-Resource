<?php

namespace Drupal\custom_rest_resource\Plugin\rest\resource;

use Drupal\rest\Plugin\ResourceBase;
use Drupal\rest\ResourceResponse;
use Drupal\paragraphs\Entity\Paragraph;
use \Drupal\node\Entity\Node;
use Drupal\media\Entity\Media;
use Drupal\Core\File\FileSystemInterface;

/**
 * Provides a Demo Resource
 *
 * @RestResource(
 *   id = "custom_resource",
 *   label = @Translation("Custom Resource"),
 *   uri_paths = {
 *     "create" = "/custom_rest_resource/custom_resource"
 *   }
 * )
 */

class CustomResource extends ResourceBase {


  	public function post($data) {
	 
	$account = \Drupal::currentUser();
	
	$paragraphs = array();
	
	foreach($data['paragraphs'] as $key => $paragraph){
		
		if( $paragraph['type'] == 'image' ){
			$paragraphs[$key] = paragraphImage($paragraph);
		}elseif($paragraph['type'] == 'text'){
			$paragraphs[$key] = paragraphText($paragraph);
		}
	}
	
	
	$contentType  = 'article';
	$node = Node::create(['type' => $contentType]);
	$node->langcode = "en";
	$node->uid = $account->id();
	$node->promote = 0;
	$node->sticky = 0;
	$node->title=  $data['node']["title"];
 	$node->content = $paragraphs;
	
	$node->save();
	$nid = $node->id();
    return new ResourceResponse($node);
    
    
    }
  
}



function paragraphImage($data) {
    

		$image_data = file_get_contents($data['url']);
		$file_repository = \Drupal::service('file.repository');
		$image = $file_repository->writeData($image_data, "public://" . $data['name'], FileSystemInterface::EXISTS_REPLACE);
		
		
		$media = Media::create([
		  'bundle'=> 'image',
		  'uid' => \Drupal::currentUser()->id(),
		  'field_media_image' => [
		    'target_id' => $image->id(),
		  ],
		]);
		
		$media->setName($data['name'])
		  ->setPublished(TRUE)
		  ->save();
		  
		$textparagraph = Paragraph::create([
		  'type' => 'image',
		  'image' => array(
		    "target_id"  =>  $media->id(),
		  ),
		]);
		$textparagraph->save();
		  
	    return $textparagraph;
  }
  
  function paragraphText($data) {
    
	    $textparagraph = Paragraph::create([
		  'type' => 'text',
		  'title' => array(
		    "value"  =>  $data['title'],
		  ),
		  'text' => array(
			"value" => $data['text'],
			"format" => 'wysiwyg'
		  ),
		]);
		$textparagraph->save();
		
		$result = array();
		$result['target_id'] = $textparagraph->id();
		$result['target_revision_id'] = $textparagraph->getRevisionId();
		
		
		  
	    return $result;
  }
