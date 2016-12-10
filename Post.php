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
            (new Fogio\Repository\Operation\OperationExtension())->setOperations(

            ),

            (new Fogio\Repository\Operation\DbTransaction()),

            (new Fogio\Repository\Operation\EntityBase()),
            (new Fogio\Repository\Operation\EntityMain())->setKey('post_id'),

            (new Fogio\Repository\Operation\Table()),

            
        ];
        
    }


}
