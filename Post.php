<?php

class Post extends Repository 
{

    protected function provideName() 
    {
        return 'post';
    }

    protected function provideExtensions()
    {
        return [
           (new DefaultOrder)->setOrder('post_id DESC'),
           (new SerializeFields)->setFields(['post_attr']),
           new Vcs(),
        ];
        
    }

    protected function provideOperations()
    {

    }

}
