<?php

class SplitName
{
    private $search;

    public function __construct($search) {
        $this->search = $search;
    }

    public function buildSplit()
    {
        $w_query = [];
        $b_query = [];
        $binds   = [];
        $regex   = [];
        $split   = preg_split('/[\s,]/', $this->search, null, PREG_SPLIT_NO_EMPTY);
        
        foreach ($split as $key => $name) {
            $w_query[]               = 'white_player LIKE :'.$key.'wname';
            $b_query[]               = 'black_player LIKE :'.$key.'bname';
            $binds[':'.$key.'bname'] = '%'.$name.'%';
            $binds[':'.$key.'wname'] = '%'.$name.'%';
            $regex[]                 = "(?=.*$name)";
        }

        $final_query = '('.implode(' AND ', $w_query). ') OR ('.implode(' AND ', $b_query).')';
        $re          = '^'.implode('', $regex).'.*';
        
        return [
            'regex' => $re,
            'query' => $final_query,
            'binds' => $binds
        ];
    }
}
