<?php
/*
To jest kod obliczający wartość wyrażeń
Trzeba jeszcze napisać funkcję do odczytu i zapisu zmiennych oraz operator przypisania i pewnie kilka innych pierdół.
* 
Dostępne operatory z priorytetami:
5. *,/,%
4. +,-
3. <,<=,>,>=
2. ==,!=
1. &,^,|  
0. &&,||
*/
function calculate($str)
{
  $str=preg_replace('/\s+/', '', $str);
  return calculate_ex($str, 0, strlen($str)-1, 0);
}
function calculate_ex(&$str, $from, $to, $level) //kod 'MEGA GÓWNO'
{
  //print substr($str, $from, $to-$from+1).' '.$from.' '.$to.' '.$level."\n";
  if($to<$from) throw new Exception('other_error');
  if(in_array($str[$from], ['*', '/', '%', '+', '-', '^', '<', '>', '=', '!', '&', '|'])) throw new Exception('op_at_beginning');
  if(in_array($str[$to], ['*', '/', '%', '+', '-', '^', '<', '>', '=', '!', '&', '|'])) throw new Exception('op_at_end');
  
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
      if($str[$from]=='(' && $str[$to]==')') return calculate_ex($str, $from+1, $to-1, 0);
      else
      return calculate_ex($str, $from, $to, $level+1);
    }
    break;
    case 7:
    {
      if($str[$from]>='0' && $str[$from]<='9' || 
      (($str[$from]=='-' || $str[$from]=='+') && $str[$from+1]>='0' && $str[$from+1]<='9'))
      {
        //print 'lol '.substr($str, $from, $to-$from+1).' '.$from.' '.$to.' '.$level.' '.intval(substr($str, $from, $to-$from+1))."\n";
        return intval(substr($str, $from, $to-$from+1));
      }
      else
      {
        return 0; //tu będzie funkcja która pobiera zmienną
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

//Przykładowe oliczenia

//$str='(5+(1243-43)*3)*(2+2*2)';

$str='(43434%((2>4)+(3<5)+ (5<=5)+(4>=5))/2*432)+543-(23== 435645)+(4334!=43)+ ((111111^4545)|5454)'; //przykladowe obliczenia
try
{
  $out=calculate($str);
  print $out."\n";
}
catch (Exception $e) {
    echo 'Caught exception: ',  $e->getMessage(), "\n";
}

print ((43434%((2>4)+(3<5)+ (5<=5)+(4>=5))/2*432)+543-(23== 435645)+(4334!=43)+ ((111111^4545)|5454))."\n"; //tu to samo w PHP dla porównania

?>
