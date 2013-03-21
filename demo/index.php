<?php
/**
 * I'm doing this all in one file, not because it's a great way to build an app, 
 * but for clarity and conciseness. 
 *
 */
require_once('../config.inc.php');
/**
 * example showing two different ways to call validate_presence, with
 * and without a custom error message
 *
 */
class Comment extends MiniActiveRecord{
  public $validations = 'presence:name; presence:body:Didn\'t you have anything to say?';
}
// used for visible error message later on
$errors = '';
// create a new blank instance of Comment to use everywhere
$comment = new Comment();

// if we are editing, then switch $comment to that instance (if it exists)
if(isset($_GET['id'])){
  if($edit = $comment->find($_GET['id'])){
    $comment = $edit;
  }
}

// handle a post
if(isset($_POST['commit'])){
  $comment->populate($_POST['comment']);
  // save, which calls validate()
  $comment->save();
  $errors = $comment->get_errors();
  // errors will either be false or an array
  if($errors){
    // pretty-print the errors and fall through
    $errors = '<ul><li>' . implode('</li><li>', (array)$errors) . '</li></ul>';
  }else{
    // redirect
    header('Location: index.php');
    exit;
  }
}
// load all existing comments
$comments = $comment->find_all(a('order: created_at DESC'));
?>
<!DOCTYPE html>
<html>
<head>
  <meta charset="utf-8" />
  <title>MiniActiveRecord Example</title>
  <link rel="stylesheet" type="text/css" media="screen" href="css/style.css" />
  <!--[if IE]>
  <script src="http://html5shiv.googlecode.com/svn/trunk/html5.js"></script>
  <![endif]-->
</head>
<body>
  <div id="PageDiv">
    <h1 id="miniactiverecord_example">MiniActiveRecord Example</h1>
    <p>This is a simple example of a form handler made with MiniActiveRecord.</p>
    <ul class="comments">
      <?php
      foreach($comments as $c){
        // create a LI for each comment
        print '<li><span class="name">' . h($c->name) . '</span>' . h($c->body) . '<span class="metadata"><span class="date">(' . $c->created_at . ')</span> <a href="?id=' . $c->id . '" class="edit_link">edit</a></span></li>';
      }
      ?>
    </ul>
    <div id="flash">
      <?= $errors ?>
    </div>
    <form action="" method="post" accept-charset="utf-8">
      <p><label for="comment_name">Name</label><input type="text" name="comment[name]" value="<?= h($comment->name) ?>" id="comment_name"/></p>
      <p><label for="comment_body">Add a Comment</label><textarea id="comment_body" name="comment[body]" rows="8" cols="40"><?= h($comment->body) ?></textarea></p>
      <p><input type="submit" name="commit" value="Say it!"/></p>
    </form>
  </div>
</body>
</html>