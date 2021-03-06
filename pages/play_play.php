<?php
  if(!isset($_GET['qid'])) die('Błąd - brak pytania');
  if(!isset($_GET['sid'])) die('Błąd - brak sesji');
  
  if(isset($_GET['aid'])) 
  {
    $oldanswer=get_answer($_GET['aid']);
    if(!empty($oldanswer['stan']))
    set_assignments($oldanswer['stan']);
  }
  $qid = (int)$_GET['qid'];
  $sid = (int)$_GET['sid'];
  $user = current_user();
  $uid = deduce_user_id($user);
  
	$question = get_question($qid);
  
  update_question_in_session($sid, $qid);
  
  if(!empty($question['pytanie']['stan']))
  set_assignments($question['pytanie']['stan']);
  
  foreach($question['odpowiedzi'] as $id => $answer)
  {
    if(!empty($answer['warunek']) && calculate($answer['warunek'])==0)  {unset($question['odpowiedzi'][$id]); continue;}
    
    $q=get_question($answer['id_pytania']);
    if(!empty($q['pytanie']['warunek']) && calculate($q['pytanie']['warunek'])==0)  {unset($question['odpowiedzi'][$id]); continue;}
  }

  if(empty($question['odpowiedzi'])) {$empty=true; end_game_by_session($sid);} else $empty=false;
  
  $env=get_environment($sid);
?>



<div class="well">
	<div class="container">
		<div class="page-header">
    <?php
    if($empty) {
    ?>
        <h3>Koniec gry!</h3>
        <a href="?page=play"><small>Powrót do strony gier</small></a>
    <?php
    }
    else {
    ?>
      <h3>Scena #<?php echo $question['pytanie']['id_pytania']; ?>:</h3>
    <?php
    }
    ?>
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
						<a href="?page=play&action=play&sid=<?php echo $sid; ?>&qid=<?php echo $answer['id_pytania']; ?>&aid=<?php echo $answer['id_odpowiedzi']; ?>">
						<?php echo $answer['tekst']; ?> </a>
					</li>
				<?php
				}
      }
			?>
			</ul>
		</div>
	</div>
  <?php if(!empty($env)) { ?>
  <div class="container">
		<div class="page-header">
			<h4> Przedmioty i zasoby: </h4>
      <ul>
      <?php 
        foreach($env as $var)
        {
          if($var['nazwa'][0]=='@')
          echo '<li>' . substr($var['nazwa'], 1)."</li>\n";
        }
        ?>
      </ul>
      <ul>
        <?php
        foreach($env as $var)
        {
          if($var['nazwa'][0]=='#')
          echo '<li>' . substr($var['nazwa'], 1).' &times; '.$var['wartosc']."</li>\n";
        }
        ?>
      </ul>
      <ul>
        <?php
        foreach($env as $var)
        {
          if($var['nazwa'][0]!='$' && $var['nazwa'][0]!='@' && $var['nazwa'][0]!='#')
          {
            if($var['wartosc']!=0)
            echo '<li>' . $var['nazwa'].' &times; '.$var['wartosc']."</li>\n";
          }
        }
        ?>
      </ul>
		</div>
	</div>
  <?php } ?>
</div>
