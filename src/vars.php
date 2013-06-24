<?php
/*
Dostępne operatory z priorytetami:
6. !,~,-,+
5. *,/,%
4. +,-
3. <,<=,>,>=
2. ==,!=
1. &,^,|  
0. &&,||
*/
$GLOBALS['g_last_calculate_error']=null;


function get_variable($name, $session_id) {
  $db = user_database();
  $stmt = $db->prepare("SELECT srodowisko.wartosc FROM srodowisko WHERE id_sesji=:id_sesji 
                                                                  AND nazwa=:nazwa");

  $stmt->execute([':id_sesji' => $session_id, ':nazwa' => $name]);
  $result = $stmt->fetchColumn();
  $stmt->closeCursor();

  return intval($result);
}

function set_variable($name, $value, $session_id) {
  $db = user_database();
  $db->beginTransaction();
  $stmt = $db->prepare("DELETE FROM srodowisko WHERE id_sesji=:id_sesji 
                                               AND nazwa=:nazwa");

  $stmt->execute([':id_sesji' => $session_id, ':nazwa' => $name]);
  $stmt->closeCursor();
  
  $stmt = $db->prepare("INSERT INTO srodowisko(id_sesji,nazwa,wartosc)
                                    values(:id_sesji, :nazwa, :wartosc)");

  $stmt->execute([':id_sesji' => $session_id, ':nazwa' => $name, ':wartosc' => $value]);
  $stmt->closeCursor();
  
  $db->commit();
}


function get_environment($session_id) {
  $db = user_database();
  $stmt = $db->prepare("SELECT srodowisko.nazwa,srodowisko.wartosc FROM srodowisko WHERE id_sesji=:id_sesji ORDER BY srodowisko.nazwa");

  $stmt->execute([':id_sesji' => $session_id]);
  
  $result = $stmt->fetchAll(PDO::FETCH_ASSOC);
  $stmt->closeCursor();
  
  return $result;
}


function calculate($str)
{
  return calculate_ex($str, 0, strlen($str)-1, 0);
}
function calculate_ex(&$str, $from, $to, $level) //kod 'MEGA GÓWNO'
{
  //print substr($str, $from, $to-$from+1).' '.$from.' '.$to.' '.$level."\n";
  
  if(in_array($str[$from], ['*', '/', '%', '^', '<', '>', '=', '&', '|']) || ($str[$from]=='!' && $str[$from+1]=='='))
    throw new Exception('op_at_beginning');
  if(in_array($str[$to], ['*', '/', '%', '+', '-', '^', '<', '>', '=', '!', '&', '|'])) 
    throw new Exception('op_at_end');
  
  while(in_array($str[$from], [' ', "\n". "\t"])) ++$from;
  while(in_array($str[$to], [' ', "\n". "\t"])) --$to;
  
  if($to<$from) throw new Exception('other_error');
  
  $checkop=''; $setopandlast=''; $calculate='';
  
  switch($level)
  {
    case 0:
    {
      
      $checkop='$out=(($str[$i]==\'&\' && $str[$i+1]==\'&\') || 
                     ($str[$i]==\'|\' && $str[$i+1]==\'|\'));';
      $setopandlast='$op=$str[$i];
                     $last=$i+2;';
      $calculate='if($op==\'&\')
                  $value=($value && calculate_ex($str, $last, $i-1, $level+1));
                  else
                  $value=($value || calculate_ex($str, $last, $i-1, $level+1));';
    }
    break;
    case 1:
    {
      
      $checkop='$out=(($str[$i]==\'&\') || 
                     ($str[$i]==\'|\') ||
                     ($str[$i]==\'^\'));';
      $setopandlast='$op=$str[$i];
                     $last=$i+1;';
      $calculate='if($op==\'&\')
                  $value=($value & calculate_ex($str, $last, $i-1, $level+1));
                  elseif($op==\'|\')
                  $value=($value | calculate_ex($str, $last, $i-1, $level+1));
                  else
                  $value=($value ^ calculate_ex($str, $last, $i-1, $level+1));';
    }
    break;
    case 2:
    {
      
      $checkop='$out=(($str[$i]==\'=\' && $str[$i+1]==\'=\') || 
                     ($str[$i]==\'!\' && $str[$i+1]==\'=\'));';
      $setopandlast='$op=$str[$i];
                     $last=$i+2;';
      $calculate='if($op==\'=\')
                  $value=($value == calculate_ex($str, $last, $i-1, $level+1));
                  else
                  $value=($value != calculate_ex($str, $last, $i-1, $level+1));';
    }
    break;
    case 3:
    {
      
      $checkop='$out=(($str[$i]==\'>\')||
                     ($str[$i]==\'<\'));';
      $setopandlast='if($str[$i+1]==\'=\')
                     {
                       $last=$i+2; 
                       if($str[$i]==\'>\')
                       $op=\'g\';
                       else
                       $op=\'l\';
                     }
                     else
                     {$last=$i+1; $op=$str[$i];}';
      $calculate='if($op==\'<\')
                  $value=($value < calculate_ex($str, $last, $i-1, $level+1));
                  elseif($op==\'>\')
                  $value=($value > calculate_ex($str, $last, $i-1, $level+1));
                  elseif($op==\'g\')
                  $value=($value >= calculate_ex($str, $last, $i-1, $level+1));
                  else
                  $value=($value <= calculate_ex($str, $last, $i-1, $level+1));';
    }
    break;
    case 4:
    {
      
      $checkop='$out=(($str[$i]==\'+\') || 
                     ($str[$i]==\'-\'));';
      $setopandlast='$op=$str[$i];
                     $last=$i+1;';
      $calculate='if($op==\'+\')
                  $value=($value + calculate_ex($str, $last, $i-1, $level+1));
                  else
                  $value=($value - calculate_ex($str, $last, $i-1, $level+1));';
    }
    break;
    case 5:
    {
      
      $checkop='$out=(($str[$i]==\'*\') || 
                     ($str[$i]==\'/\') ||
                     ($str[$i]==\'%\'));';
      $setopandlast='$op=$str[$i];
                     $last=$i+1;';
      $calculate='if($op==\'*\')
                  $value=($value * calculate_ex($str, $last, $i-1, $level+1));
                  elseif($op==\'%\')
                  $value=($value % calculate_ex($str, $last, $i-1, $level+1));
                  else
                  $value=($value / calculate_ex($str, $last, $i-1, $level+1));';
    }
    break;
    case 6:
    {
      if($str[$from]=='!') return !calculate_ex($str, $from+1, $to, $level+1);
      elseif($str[$from]=='~') return ~calculate_ex($str, $from+1, $to, $level+1);
      elseif($str[$from]=='-') return -calculate_ex($str, $from+1, $to, $level+1);
      elseif($str[$from]=='+') return calculate_ex($str, $from+1, $to, $level+1);
      
    }
    case 7:
    {
      if($str[$from]=='(' && $str[$to]==')') return calculate_ex($str, $from+1, $to-1, 0);
      else
      return calculate_ex($str, $from, $to, $level+1);
    }
    break;
    case 8:
    {
      if($str[$from]>='0' && $str[$from]<='9' || 
      (($str[$from]=='-' || $str[$from]=='+') && $str[$from+1]>='0' && $str[$from+1]<='9'))
      {
        //print 'lol '.substr($str, $from, $to-$from+1).' '.$from.' '.$to.' '.$level.' '.intval(substr($str, $from, $to-$from+1))."\n";
        return intval(substr($str, $from, $to-$from+1));
      }
      else
      {
        $name=substr($str, $from, $to-$from+1);
        //print 'mam zmienna '.$name.' '.$from.' '.$to.' '.$level."\n";
        if($name=='true') return 1;
        elseif($name=='false') return 0;
        else
        {
         return get_variable($name, get_current_session_id());
         //return 0;
        }
      }
    }
    break;
  }
  
  if($checkop!='')
  {
    if($from==$to) return calculate_ex($str, $from, $to, $level+1);
    
    $value=0;
    $op='u';
    $i=0;
    
    
    $i=$from+1;
    $last=0;
    $brackets=0;
    if($str[$from]=='(') ++$brackets;
    
    for(;$i<$to;++$i)
    {
      if($str[$i]=='(') ++$brackets;
      else
      if($str[$i]==')') --$brackets;
      if($brackets<0) throw new Exception('bad_brackets');
      eval($checkop);
      if($out && $brackets==0)
      {
        eval($setopandlast);
        --$i;
        break;
      }
    }
    if($i==$to) 
    {
      if($str[$to]==')') --$brackets;
      if($brackets!=0) throw new Exception('bad_brackets');
      return calculate_ex($str, $from, $to, $level+1);
    } //brak operatorów
    
    $value=calculate_ex($str, $from, $i, $level+1);
    
    $i=$last;
    for(;$i<$to;++$i)
    {
      if($str[$i]=='(') ++$brackets;
      else
      if($str[$i]==')') --$brackets;
      if($brackets<0) throw new Exception('bad_brackets');
      eval($checkop);
      if($out && $brackets==0)
      {
        
        eval($calculate);
        eval($setopandlast);
        $i=$last-1;
      }
    }
    if($str[$to]==')') --$brackets;
    if($brackets!=0) throw new Exception('bad_brackets');
    $i=$to+1;
    eval($calculate);
    //print 'lol2 '.substr($str, $from, $to-$from+1).' '.$from.' '.$to.' '.$level.' '.intval($value)."\n";
    return intval($value);
  }
  throw new Exception('other_error2');
  return 0;
}

function set_assignments($str)
{
  $assignments=explode(';', $str);
  foreach($assignments as $assign)
  {
    $asstab=explode(':=', $assign);
    
    $size=count($asstab);
    
    if($size<2) continue;
    if($size>2) throw new Exception('too_many_:=');
    
    $asstab[0]=trim($asstab[0]);
    $asstab[1]=trim($asstab[1]);
    set_variable($asstab[0], calculate($asstab[1]), get_current_session_id());
    //print $asstab[0].' = '.calculate($asstab[1])."\n";
  }
}

function check_assignments($str)
{
  $assignments=explode(';', $str);
  foreach($assignments as $assign)
  {
    $asstab=explode(':=', $assign);
    
    $size=count($asstab);
    
    if($size<2) 
    {
      $GLOBALS['g_last_calculate_error']=7; 
      return false;
    }
    if($size>2) 
    {
      $GLOBALS['g_last_calculate_error']=6; 
      return false;
    }
    
    $asstab[1]=trim($asstab[1]);
    if(!check_expression($asstab[1])) return false;
  }
  return true;
}

//zwraca prawdę jeżeli wyrażenie jest poprawne
function check_expression($str)
{
  try
  {
    calculate($str);
  }
  catch (Exception $e) {
    switch($e->getMessage())
    {
      case 'op_at_beginning':
        $GLOBALS['g_last_calculate_error']=1; 
      return false;
      case 'op_at_end':
        $GLOBALS['g_last_calculate_error']=2; 
      return false;
      case 'bad_brackets':
        $GLOBALS['g_last_calculate_error']=3;
      return false;
      case 'other_error':
        $GLOBALS['g_last_calculate_error']=4;
      return false;
      case 'other_error2':
        $GLOBALS['g_last_calculate_error']=5; 
      return false;
    }
  }
  return true;
}


function get_last_error()
{
  return $GLOBALS['g_last_calculate_error'];
}

function get_error_message($id)
{
  switch($id)
  {
    case 1:
    return 'Operator na początku wyrażenia lub podwyrażenia';
    case 2:
    return 'Operator na końcu wyrażenia lub podwyrażenia';
    case 3:
    return 'Złe nawiasowanie';
    case 4:
    return 'Inny błąd';
    case 5:
    return 'Dziwny błąd';
    case 6:
    return 'Za dużo operatorów \':=\''; 
    case 7:
    return 'Brak operatora \':=\''; 
  }
}


//Przykładowe oliczenia

//$str='(5+(1243-43)*3)*(2+2*2)';
/*
$str='true!=!false';

$str=' (43434%((2>4 )+(3<5)+ !zmienna bardzo zmienna+ (5 <=5)+ (4>=5))/2*432)+543-(23== 435645)+(4334!=43)+ ((111111^4545)|5454)'; //przykladowe obliczenia

$str='';

try
{
  $out=calculate($str);
  print $out."\n";
}
catch (Exception $e) {
    echo 'Caught exception: ',  $e->getMessage(), "\n";
}

print ((43434%((2>4)+(3<5)+ (5<=5)+(4>=5))/2*432)+543-(23== 435645)+(4334!=43)+ ((111111^4545)|5454))."\n"; //tu to samo w PHP dla porównania


set_variables('kalafjor:=true!=!false ; bakuazan:=2+5/3 ; kalalepa:=bakuazan+5');
*/
?>
