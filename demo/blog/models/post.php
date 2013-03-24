<?php
class Post extends MiniActiveRecord{
  public $has_many = 'comments';
  public $validations = 'presence:title; presence:teaser; presence:body';
  function create_slug(){
    //just an example of how to create a callback, this demo app doesn't use a router...
    $this->slug = $this->id . '-' . preg_replace('/\s+/', '-', strtolower(preg_replace('/[^a-z0-9]+/i',' ', $this->title)));
  }
  public before_create(){
    return $this->create_slug();
  }
}
?>