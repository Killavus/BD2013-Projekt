<?php
  if(!isset($_GET['qid'])) die('Błąd - brak pytania');
  if(!isset($_GET['sid'])) die('Błąd - brak sesji');
  
  $qid = (int)$_GET['qid'];
  $sid = (int)$_GET['sid'];
  $user = current_user();
  $uid = deduce_user_id($user);
  
	$question = get_question($qid);
?>

<div class="well">
	<div class="container">
		<div class="page-header">
			<h3> Pytanie "<?php echo $question['pytanie']['nazwa']; ?>" <small> którą odpowiedź wybierzesz? </small> </h3>
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
		</div>
	</div>
</div>
