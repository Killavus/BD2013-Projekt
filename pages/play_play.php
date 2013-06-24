<?php
  if(!isset($_GET['qid'])) die('Błąd - brak pytania');
  if(!isset($_GET['sid'])) die('Błąd - brak sesji');
  
  $qid = (int)$_GET['qid'];
  $sid = (int)$_GET['sid'];
  $user = current_user();
  $uid = deduce_user_id($user);
  
	$question = get_question($qid);
  
  foreach($question['odpowiedzi'] as $id => $answer)
  {
    if(!empty($answer['warunek']) && calculate($answer['warunek'])==0)  unset($question['odpowiedzi'][$id]);
  }

  if(empty($question['odpowiedzi'])) {$empty=true; end_game_by_session($sid);} else $empty=false;
?>

<div class="well">
	<div class="container">
		<div class="page-header">
			<h3> Pytanie "<?php echo $question['pytanie']['nazwa']; ?>" 
      <?php if(!$empty) { ?>
      <small> którą odpowiedź wybierzesz? </small> </h3>
      <?php } ?>
		</div>
		<?php
		if(!empty($question['pytanie']['src'])) { ?>
			<div class="pull-right">
				<img src="<?php echo $question['pytanie']['src']; ?>" class="img-polaroid" >
			</div>
		<?php
		} ?>
		<div>
      
			<p> <?php echo $question['pytanie']['tekst']; ?> </p>
      <?php if(!$empty) { ?>
			<ul style="list-style-type: none">
			<?php
				foreach($question['odpowiedzi'] as $answer){ ?>
					<li>
						<i class="icon-chevron-right"></i>
						<a href="?page=play&action=play&sid=<?php echo $sid; ?>&qid=<?php echo $answer['id_pytania']; ?>">
						<?php echo $answer['tekst']; ?> </a>
					</li>
				<?php
				}
			?>
			</ul>
      <?php } else { ?>
        <h1>Koniec gry</h1>
        <a href="?page=play"><small>Powrót do strony gier</small></a>
      <?php } ?>
		</div>
	</div>
</div>
