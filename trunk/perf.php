<?php
class Perf
  {
  private $start;
  private $cur;
  private $entries;
  private $item = 0;
  
  public function perf()
    {
    $this->start = microtime(true);
    $this->cur = $this->start;
    }
  
  public function mark($name)
    {
    $now = microtime(true);
    $this->entries[$this->item]['name'] = $name;
    $this->entries[$this->item]['step'] = round($now - $this->cur, 4);
    $this->entries[$this->item]['start'] = round($now - $this->start, 4);
    $this->item++;
    $this->cur = $now;
    }
   
   public function show($name)
     {
     $this->mark($name);
     
     return ($this->entries);
     } 
  }
?>